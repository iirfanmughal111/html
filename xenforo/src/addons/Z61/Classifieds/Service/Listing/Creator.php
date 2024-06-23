<?php

namespace Z61\Classifieds\Service\Listing;

use XF\Service\AbstractService;
use Z61\Classifieds\Entity\Category;
use Z61\Classifieds\Entity\Condition;
use Z61\Classifieds\Entity\Listing;
use Z61\Classifieds\Entity\ListingType;
use Z61\Classifieds\Entity\Package;

class Creator extends AbstractService
{
    use \XF\Service\ValidateAndSavableTrait;
    use ListingShared;

    /**
     * @var \XF\Service\Tag\Changer;
     */
    protected $tagChanger;

    /**
     * @var \XF\Service\Thread\Creator|null
     */
    protected $threadCreator;


    public function __construct(\XF\App $app, Category $category)
    {
        parent::__construct($app);
        $this->category = $category;
        $this->setupDefaults();
    }

    public function setupDefaults()
    {
        $this->listing = $this->category->getNewListing();

        $this->listingPreparer = $this->service('Z61\Classifieds:Listing\Preparer', $this->listing);

        $this->tagChanger = $this->service('XF:Tag\Changer', 'classifieds_listing', $this->category);

        $user = \XF::visitor();

        $this->listing->user_id = $user->user_id;
        $this->listing->username = $user->username;

        $this->listing->listing_state = $this->category->getNewContentState($this->listing);
    }


    public function setIsAutomated()
    {
        $this->logIp(false);
        $this->setPerformValidations(false);
    }

    public function setPackage(Package $package)
    {
        $this->listing->package_id = $package->package_id;

        if ($package->cost_amount > 0.00)
        {
            // expiry date gets set after approved payment
            $this->listing->listing_status = 'awaiting_payment';
        }
        else
        {
            if (!$package->length_unit)
            {
                $endDate = 0;
            }
            else
            {
                $endDate = strtotime('+' . $package->length_amount . ' ' . $package->length_unit);
            }

            $this->listing->expiration_date = $endDate;
        }
    }

    public function setCustomFields(array $customFields)
    {
        $listing = $this->listing;

        /** @var \XF\CustomField\Set $fieldSet */
        $fieldSet = $listing->custom_fields;
        $fieldDefinition = $fieldSet->getDefinitionSet()
            ->filterOnly($this->category->field_cache);

        $customFieldsShown = array_keys($fieldDefinition->getFieldDefinitions());

        if ($customFieldsShown)
        {
            $fieldSet->bulkSet($customFields, $customFieldsShown);
        }
    }

    public function setListingContent($title, $content, $format = true)
    {
        $this->setTitle($title);

        return $this->listingPreparer->setContent($content, $format, $this->performValidations);
    }

    public function setTags($tags)
    {
        if ($this->tagChanger->canEdit())
        {
            $this->tagChanger->setEditableTags($tags);
        }
    }

    public function logIp($logIp)
    {
        $this->listingPreparer->logIp($logIp);
    }

    public function getMentionedUserIds($limitPermissions = true)
    {
        return array_keys($this->listingPreparer->getMentionedUsers($limitPermissions));
    }

    protected function _validate()
    {
        $this->finalSetup();

        $listing = $this->listing;

        if (!$listing->user_id)
        {
            /** @var \XF\Validator\Username $validator */
            $validator = $this->app->validator('Username');
            $listing->username = $validator->coerceValue($listing->username);

            if ($this->performValidations && !$validator->isValid($listing->username, $error))
            {
                return [
                    $validator->getPrintableErrorValue($error)
                ];
            }
        }

        $listing->preSave();
        $errors = $listing->getErrors();

        if ($this->performValidations)
        {
            if (!$this->listingPreparer->validateFiles($imageError))
            {
                $errors[] = $imageError;
            }

            if ($this->tagChanger->canEdit())
            {
                $tagErrors = $this->tagChanger->getErrors();
                if ($tagErrors)
                {
                    $errors = array_merge($errors, $tagErrors);
                }
            }
        }

        return $errors;
    }

    protected function _save()
    {
        $category = $this->category;
        $listing = $this->listing;

        $db = $this->db();
        $db->beginTransaction();

        $listing->save(true, false);

        $this->listingPreparer->postInsert();

        if ($this->tagChanger->canEdit())
        {
            $this->tagChanger
                ->setContentId($listing->listing_id, true)
                ->save($this->performValidations);
        }

        if ($category->node_id && $category->Forum)
        {
            $creator = $this->setupListingThreadCreation($category->Forum);
            if ($creator && $creator->validate())
            {
                $thread = $creator->save();
                $listing->fastUpdate('discussion_thread_id', $thread->thread_id);
                $this->threadCreator = $creator;

                $this->afterListingThreadCreated($thread);
            }
        }


        $db->commit();

        return $listing;
    }

    public function sendNotifications()
    {
        if ($this->listing->isVisible())
        {
            /** @var \Z61\Classifieds\Service\Listing\Notify $notifier */
            $notifier = $this->service('Z61\Classifieds:Listing\Notify', $this->listing, 'classifieds_listing');
            $notifier->setMentionedUserIds($this->listingPreparer->getMentionedUserIds());
            $notifier->notifyAndEnqueue(3);
        }

        if ($this->threadCreator)
        {
            $this->threadCreator->sendNotifications();
        }
    }





    protected function afterListingThreadCreated(\XF\Entity\Thread $thread)
    {
        $this->repository('XF:Thread')->markThreadReadByVisitor($thread);
        $this->repository('XF:ThreadWatch')->autoWatchThread($thread, \XF::visitor(), true);
    }

    protected function setupListingThreadCreation(\XF\Entity\Forum $forum)
    {
        /** @var \XF\Service\Thread\Creator $creator */
        $creator = $this->service('XF:Thread\Creator', $forum);
        $creator->setIsAutomated();

        $creator->setContent($this->listing->getExpectedThreadTitle(), $this->getThreadMessage(), false);
        $creator->setPrefix($this->category->thread_prefix_id);

        $thread = $creator->getThread();
        $thread->bulkSet([
            'discussion_type' => 'classifieds_listing',
            'discussion_state' => $this->listing->listing_state
        ]);

        return $creator;
    }

    public function setListingOpen($listingOpen)
    {
        $this->listing->listing_open = $listingOpen;
    }

    protected function getThreadMessage()
    {
        $listing = $this->listing;

        $snippet = $this->app->bbCode()->render(
            $this->app->stringFormatter()->wholeWordTrim($listing->content, 500),
            'bbCodeClean',
            'post',
            null
        );

        $phrase = \XF::phrase('z61_classifieds_listing_thread_create', [
            'title' => $listing->title,
            'username' => $listing->User ? $listing->User->username : $listing->username,
            'snippet' => $snippet,
            'listing_link' => $this->app->router('public')->buildLink('canonical:classifieds', $this->listing)
        ]);

        return $phrase->render('raw');
    }
}