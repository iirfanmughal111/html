<?php

namespace Z61\Classifieds\Entity;

use XF\Entity\ApprovalQueue;
use XF\Entity\Attachment;
use XF\Entity\BookmarkTrait;
use XF\Entity\DeletionLog;
use XF\Entity\ReactionTrait;
use XF\Entity\Thread;
use XF\Entity\User;
use XF\Mvc\Entity\ArrayCollection;
use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;
use XF\Repository\NewsFeed;
use Z61\Classifieds\Repository\ListingLocation;

/**
 * COLUMNS
 * @property int listing_id
 * @property int listing_type_id
 * @property int condition_id
 * @property int package_id
 * @property int category_id
 * @property string title
 * @property string content
 * @property int listing_date
 * @property int user_id
 * @property string username
 * @property int discussion_thread_id
 * @property int ip_id
 * @property int view_count
 * @property float price
 * @property int likes
 * @property array like_users
 * @property string currency
 * @property int last_edit_date
 * @property int last_edit_user_id
 * @property string listing_state
 * @property bool listing_open
 * @property string listing_location
 * @property array listing_location_data
 * @property int warning_id
 * @property string warning_message
 * @property int prefix_id
 * @property int attach_count
 * @property array custom_fields_
 * @property array tags
 * @property array embed_metadata
 * @property int expiration_date
 * @property string contact_email
 * @property string contact_custom
 * @property bool contact_email_enable
 * @property bool contact_conversation_enable
 * @property string listing_status
 * @property double location_lat
 * @property double location_long
 * @property int cover_image_id
 * @property int sold_user_id
 * @property string sold_username
 *
 * GETTERS
 * @property mixed custom_fields
 * @property mixed expired
 *
 * RELATIONS
 * @property \Z61\Classifieds\Entity\Category Category
 * @property \Z61\Classifieds\Entity\ListingType Type
 * @property \Z61\Classifieds\Entity\User User
 * @property \Z61\Classifieds\Entity\ListingPrefix Prefix
 * @property \XF\Entity\Thread Discussion
 * @property \XF\Entity\Attachment[] Attachments
 * @property \XF\Entity\ApprovalQueue ApprovalQueue
 * @property \XF\Entity\DeletionLog DeletionLog
 * @property \Z61\Classifieds\Entity\ListingWatch[] Watch
 * @property \Z61\Classifieds\Entity\ListingFeature Featured
 * @property \Z61\Classifieds\Entity\ListingRead[] Read
 * @property \Z61\Classifieds\Entity\Condition Condition
 * @property \Z61\Classifieds\Entity\Package Package
 * @property Attachment CoverImage
 * @property User SoldUser
 */
class Listing extends Entity implements \XF\BbCode\RenderableContentInterface
{
    use ReactionTrait, BookmarkTrait;

    public function canView(&$error = null)
    {
        if (!$this->Category || !$this->Category->canView())
        {
            return false;
        }

        $visitor = \XF::visitor();

        if (!$this->hasPermission('view'))
        {
            return false;
        }

        if ($this->listing_state == 'moderated')
        {
            if (
                !$this->hasPermission('viewModerated')
                && (!$visitor->user_id || $visitor->user_id != $this->user_id)
            )
            {
                return false;
            }
        }
        else if ($this->listing_state == 'deleted')
        {
            if (!$this->hasPermission('viewDeleted'))
            {
                return false;
            }
        }

        return true;
    }

    public function canViewAttachments()
    {
        return $this->hasPermission('viewAttachment');
    }

    public function canEdit(&$error = null)
    {
        $visitor = \XF::visitor();
        if (!$visitor->user_id)
        {
            return false;
        }

        if ($this->hasPermission('editAny'))
        {
            return true;
        }

        return (
            $this->user_id == $visitor->user_id
            && $this->hasPermission('updateOwn')
        );
    }

    public function canPurchase()
    {
        return true;
    }

    public function canApproveUnapprove(&$error = null)
    {
        return $this->Category->canApproveUnapprove();
    }

    public function canOpen(&$error)
    {
        return $this->canEdit($error) && !$this->listing_open;
    }

    public function canLockUnlock(&$error = null)
    {
        /** @var \Z61\Classifieds\XF\Entity\User $visitor */
        $visitor = \XF::visitor();
        return ($visitor->user_id && $visitor->hasClassifiedsCategoryPermission($this->category_id, 'lockUnlock'));
    }

    public function canFeatureUnfeature(&$error = null)
    {
        /** @var \Z61\Classifieds\XF\Entity\User $visitor */
        $visitor = \XF::visitor();
        return $visitor->user_id && $visitor->hasClassifiedsCategoryPermission($this->category_id, 'featureUnfeature');
    }

    public function canEditTags(&$error = null)
    {
        /** @var Category $category */
        $category = $this->Category;
        return $category ? $category->canEditTags($this, $error) : false;
    }

    public function canDelete($type = 'soft', &$error = null)
    {
        $visitor = \XF::visitor();

        if ($type != 'soft')
        {
            return $this->hasPermission('hardDeleteAny');
        }

        if ($this->hasPermission('deleteAny'))
        {
            return true;
        }

        return (
            $this->user_id == $visitor->user_id
            && $this->hasPermission('deleteOwn')
        );
    }

    public function canUndelete(&$error = null)
    {
        $visitor = \XF::visitor();
        return $visitor->user_id && $this->hasPermission('undelete');
    }

    public function canWatch(&$error = null)
    {
	    $visitor = \XF::visitor();

	    return (
		    $visitor->user_id
		    && $visitor->user_id != $this->user_id
	    );
    }

    public function canMove(&$error = null)
    {
        $visitor = \XF::visitor();

        return (
            $visitor->user_id
            && $this->hasPermission('editAny')
        );
    }

    public function canReassign(&$error = null)
    {
        $visitor = \XF::visitor();

        return (
            $visitor->user_id
            && $this->hasPermission('reassign')
        );
    }

    public function canUseInlineModeration(&$error = null)
    {
        $visitor = \XF::visitor();
        return ($visitor->user_id && $this->hasPermission('inlineMod'));
    }

    public function canReport(&$error = null, \XF\Entity\User $asUser = null)
    {
        $asUser = $asUser ?: \XF::visitor();
        return $asUser->canReport($error);
    }

    public function canWarn(&$error = null)
    {
        $visitor = \XF::visitor();

        if ($this->warning_id
            || !$this->user_id
            || !$visitor->user_id
            || $this->user_id == $visitor->user_id
            || !$this->hasPermission('warn')
        )
        {
            return false;
        }

        $user = $this->User;
        return ($user && $user->isWarnable());
    }

    public function canReact(&$error = null)
    {
        $visitor = \XF::visitor();
        if (!$visitor->user_id)
        {
            return false;
        }

        if ($this->listing_state != 'visible')
        {
            return false;
        }

        if ($this->user_id == $visitor->user_id)
        {
            $error = \XF::phraseDeferred('reacting_to_your_own_content_is_considered_cheating');
            return false;
        }

        return $this->hasPermission('react');
    }

    protected function canBookmarkContent(&$error = null)
    {
        return $this->isVisible();
    }

    public function canSendModeratorActionAlert()
    {
        $visitor = \XF::visitor();

        return (
            $visitor->user_id
            && $visitor->user_id != $this->user_id
            && $this->listing_state == 'visible'
        );
    }

    public function canShowPrice()
    {
        return $this->Category->allow_paid;
    }

    public function canClearSold()
    {
        return $this->hasPermission('clearSold') && $this->listing_status == 'sold';
    }

    public function canMarkSoldOwnListing()
    {
        return ($this->listing_status != 'sold'
            && $this->hasPermission('markSold')
            && \XF::visitor()->user_id == $this->user_id
        );
    }

    public function canClose()
    {
        if ($this->hasPermission('closeAny'))
        {
            return true;
        }

        return $this->listing_open == true && $this->hasPermission('closeOwnListing') && \XF::visitor()->user_id == $this->user_id;
    }

    public function canOpenListing()
    {
        return !$this->listing_open && $this->hasPermission('lockUnlock');
    }

    public function canContactOwner()
    {
        return $this->hasPermission('contactOwner');
    }

    public function canViewPurchaseInfo()
    {
        return $this->user_id == \XF::visitor()->user_id || $this->hasPermission('viewPurchaseInfo');
    }

    public function canSetCoverImage(&$error = null)
    {
        if (!$this->hasImageAttachments())
        {
            return false;
        }

        return $this->canEdit($error);
    }

    public function canGiveFeedback()
    {
        $feedbackForListing = $this->finder('Z61\Classifieds:Feedback')
            ->where('from_user_id', \XF::visitor()->user_id)
            ->where('listing_id', $this->listing_id)->total();
        return $this->listing_status == 'sold' &&
               $this->SoldUser &&
               $feedbackForListing == 0 &&
               ($this->user_id == \XF::visitor()->user_id || $this->sold_user_id == \XF::visitor()->user_id);

    }

    public function canAskQuestion()
    {
        $visitor = \XF::visitor();
        if (!$visitor->user_id)
        {
            return false;
        }

        if ($this->listing_state != 'visible')
        {
            return false;
        }

        return $this->listing_open && $this->hasPermission('ask_question');
    }

    public function isPurchasable()
    {
        return $this->listing_open == true
            && $this->listing_status != 'awaiting_payment'
            && $this->Category->canPurchaseFeature()
            && $this->hasPermission('purchaseFeature')
            && $this->user_id == \XF::visitor()->user_id;
    }

    public function isAwaitingPayment()
    {
        return $this->listing_status == 'awaiting_payment';
    }

    public function isAttachmentEmbedded($attachmentId)
    {
        if (!$this->embed_metadata)
        {
            return false;
        }

        if ($attachmentId instanceof \XF\Entity\Attachment)
        {
            $attachmentId = $attachmentId->attachment_id;
        }

        return isset($this->embed_metadata['attachments'][$attachmentId]);
    }

    public function isIgnored()
    {
        return \XF::visitor()->isIgnoring($this->user_id);
    }

    public function isLiked()
    {
        $visitor = \XF::visitor();
        if (!$visitor->user_id)
        {
            return false;
        }

        return isset($this->Likes[$visitor->user_id]);
    }

    public function isVisible()
    {
        return ($this->listing_state == 'visible');
    }

    public function isWatched()
    {
        return isset($this->Watch[\XF::visitor()->user_id]);
    }

    protected function adjustUserListingCountIfNeeded($direction, $forceChange = false)
    {

        if ($forceChange || !empty($this->Category->listing_count))
        {
            $updates = $this->db()->fetchPairs("
				SELECT user_id, COUNT(*)
				FROM xf_z61_classifieds_listing
				WHERE category_id = ?
					AND user_id > 0
					AND listing_state = 'visible'
				GROUP BY user_id
			", $this->category_id);

            $operator = $direction > 0 ? '+' : '-';
            foreach ($updates AS $userId => $adjust)
            {
                $this->db()->query("
					UPDATE xf_user
					SET z61_classifieds_listing_count = GREATEST(0, z61_classifieds_listing_count {$operator} ?)
					WHERE user_id = ?
				", [$adjust, $userId]);
            }
        }
    }

    public function getExpired()
    {
        return ($this->listing_status != 'awaiting_payment' && !empty($this->expiration_date) &&
	        $this->expiration_date < \XF::$time
        );
    }

	/**
	 * @return string
	 */
	public function getPurchasableTypeId()
	{
		return 'z61_classifieds_listing';
	}

    public function getCustomFields()
    {
        /** @var \XF\CustomField\DefinitionSet $fieldDefinitions */
        $fieldDefinitions = $this->app()->container('customFields.classifiedsListings');

        return new \XF\CustomField\Set($fieldDefinitions, $this);
    }

    public function getVisitorReadDate()
    {
        $visitor = \XF::visitor();
        if (!$visitor->user_id)
        {
            return null;
        }

        $listingRead = $this->Read[$visitor->user_id];
        $categoryRead = $this->Category ? $this->Category->Read[$visitor->user_id] : null;

        $dates = [\XF::$time - $this->app()->options()->readMarkingDataLifetime * 86400];
        if ($listingRead)
        {
            $dates[] = $listingRead->listing_read_date;
        }
        if ($categoryRead)
        {
            $dates[] = $categoryRead->category_read_date;
        }

        return max($dates);
    }

    public function hasPermission($permission)
    {
        /** @var \Z61\Classifieds\XF\Entity\User $visitor */
        $visitor = \XF::visitor();
        return $visitor->hasClassifiedsCategoryPermission($this->category_id, $permission);
    }

    public function getBreadcrumbs($includeSelf = true)
    {
        /** @var \XF\Mvc\Router $router */
        $router = $this->app()->container('router.public');

        $output = $this->Category->getBreadcrumbs();

        if ($includeSelf)
        {
            $output[] = [
                'value' => $this->title,
                'href' => $router->buildLink('classifieds', $this)
            ];
        }

        return $output;
    }

    public function getExpectedThreadTitle($currentValues = true)
    {
        $title = $currentValues ? $this->getValue('title') : $this->getExistingValue('title');
        $state = $currentValues ? $this->getValue('listing_state') : $this->getExistingValue('listing_state');

        $template = '';
        $options = $this->app()->options();

        if ($state != 'visible' && $options->z61ClassifiedsListingDeleteThreadAction['update_title'])
        {
            $template = $options->z61ClassifiedsListingDeleteThreadAction['title_template'];
        }

        if (!$template)
        {
            $template = '{title}';
        }

        $threadTitle = str_replace('{title}', $title, $template);
        return $this->app()->stringFormatter()->wholeWordTrim($threadTitle, 100);
    }

    public function getFieldEditMode()
    {
        $visitor = \XF::visitor();

        $isSelf = ($visitor->user_id == $this->user_id || !$this->category_id);
        $isMod = ($visitor->user_id && $this->hasPermission('editAny'));

        if ($isMod || !$isSelf)
        {
            return $isSelf ? 'moderator_user' : 'moderator';
        }

        return 'user';
    }

    public function hasViewableDiscussion()
    {
        if (!$this->discussion_thread_id)
        {
            return false;
        }

        $thread = $this->Discussion;
        if (!$thread)
        {
            return false;
        }

        return $thread->canView();
    }

    public function hasExtraInfoTab()
    {
        if (!$this->getValue('custom_fields'))
        {
            // if they haven't set anything, we can bail out quickly
            return false;
        }

        /** @var \XF\CustomField\Set $fieldSet */
        $fieldSet = $this->custom_fields;
        $definitionSet = $fieldSet->getDefinitionSet()
            ->filterOnly($this->Category->field_cache)
            ->filterGroup('extra')
            ->filterWithValue($fieldSet);

        return ($definitionSet->count() > 0);
    }

    public function hasImageAttachments()
    {
        if (!$this->attach_count)
        {
            return false;
        }

        $attachments = $this->Attachments;

        foreach ($attachments AS $attachment)
        {
            if ($attachment['thumbnail_url'])
            {
                return true;
            }
        }

        return false;
    }

    protected function _preSave()
    {
        if (count($this->Category->condition_ids) == 0 && empty($this->condition_id))
        {
            $this->set('condition_id', 0);
        }
    }

    protected function _postSave()
    {
        $visibilityChange = $this->isStateChanged('listing_state', 'visible');
        $approvalChange = $this->isStateChanged('listing_state', 'moderated');
        $deletionChange = $this->isStateChanged('listing_state', 'deleted');

        if ($approvalChange == 'enter')
        {
            $approvalQueue = $this->getRelationOrDefault('ApprovalQueue', false);
            $approvalQueue->content_date = $this->listing_date;
            $approvalQueue->save();
        }
        else if ($deletionChange == 'enter' && !$this->DeletionLog)
        {
            $delLog = $this->getRelationOrDefault('DeletionLog', false);
            $delLog->setFromVisitor();
            $delLog->save();
        }

        if ($this->isUpdate())
        {
            if ($visibilityChange == 'enter')
            {
                $this->listingMadeVisible();

                if ($approvalChange)
                {
                    $this->submitHamData();
                }
            }
            else
            {
                if ($visibilityChange == 'leave')
                {
                    $this->listingHidden();
                }
            }

            if ($this->isChanged('node_id'))
            {
                /** @var Category $oldCategory */
                $oldCategory = $this->getExistingRelation('Category');
                if ($oldCategory && $this->Category)
                {
                    $this->listingMoved($oldCategory, $this->Category);
                }
            }

            if ($deletionChange == 'leave' && $this->DeletionLog)
            {
                $this->DeletionLog->delete();
            }

            if ($approvalChange == 'leave' && $this->ApprovalQueue)
            {
                $this->ApprovalQueue->delete();
            }
        }

        $this->updateCategoryRecord();

        if ($this->isUpdate() && $this->getOption('log_moderator'))
        {
            $this->app()->logger()->logModeratorChanges('classifieds_listing', $this);
        }

        $apiKey = \XF::options()->z61ClassifiedsGoogleApi;

        if ($apiKey && ($this->isInsert() || ($this->isUpdate() && $this->isChanged('listing_location'))))
        {
            $eventLocationData = [];
            $eventLocation = $this->listing_location;

            if ($eventLocation)
            {
                /** @var ListingLocation $locationRepo */
                $locationRepo = $this->repository('Z61\Classifieds:ListingLocation');

                list($response, $status) = $locationRepo->getLocationDataForAddress($eventLocation, $apiKey);

                if ($status == 'OK')
                {
                    $eventLocationData = [
                        'latitude' => $response->results[0]->geometry->location->lat,
                        'longitude' => $response->results[0]->geometry->location->lng,
                        'formatted_address' => $response->results[0]->formatted_address,
                    ];

                    $this->fastUpdate([
                        'location_lat' => $eventLocationData['latitude'],
                        'location_long' => $eventLocationData['longitude'],
                    ]);
                }
            }

            \XF::db()->update('xf_z61_classifieds_listing', [
                'listing_location_data' => @serialize($eventLocationData)
            ], 'listing_id = ' . $this->listing_id);
        }

        if ($this->isChanged('listing_status') && $this->listing_status == 'sold')
        {
            /** @var NewsFeed $newsFeedRepo */
            $newsFeedRepo = $this->repository('XF:NewsFeed');
            switch($this->listing_status)
            {
                case 'expired':
                    $newsFeedRepo->publish('classifieds_listing', $this->listing_id, 'expired', $this->user_id, $this->username);
                    break;
                case 'sold':
                    $newsFeedRepo->publish('classifieds_listing', $this->listing_id, 'sold', $this->user_id, $this->username);
                    break;
                default:
                    break;
            }
        }
    }

    protected function _postDelete()
    {
        parent::_postDelete();
        if ($this->Category)
        {
            if ($this->Category->listing_count > 0)
            {
                $this->Category->listing_count--;
                $this->Category->save();
            }
        }
    }

    protected function updateCategoryRecord()
    {
        if (!$this->Category)
        {
            return;
        }

        /** @var \Z61\Classifieds\Entity\Category $category */
        $category = $this->Category;

        if ($this->isUpdate() && $this->isChanged('category_id'))
        {
            // listing moved, trumps the rest
            if ($this->listing_state == 'visible')
            {
                $category->listingAdded($this);
                $category->save();
            }

            if ($this->getExistingValue('listing_state') == 'visible')
            {
                /** @var \Z61\Classifieds\Entity\Category $oldCategory */
                $oldCategory = $this->getExistingRelation('Category');
                if ($oldCategory)
                {
                    $oldCategory->listingRemoved($this);
                    $oldCategory->save();
                }
            }

            return;
        }

        // check for thread entering/leaving visible
        $visibilityChange = $this->isStateChanged('listing_state', 'visible');
        if ($visibilityChange == 'enter' && $this->Category)
        {
            $category->listingAdded($this);
            $category->save();
            return;
        }
        else if ($visibilityChange == 'leave' && $this->Category)
        {
            $category->listingRemoved($this);
            $category->save();
            return;
        }

        // general data changes
        if ($this->listing_state == 'visible'
            && $this->isChanged(['last_edit_date', 'user_id', 'title'])
        )
        {
            $category->listingDataChanged($this);
            $category->save();
        }
    }

    protected function listingMadeVisible()
    {
        $this->adjustUserListingCountIfNeeded(1);

        /** @var \XF\Repository\Reaction $reactionRepo */
        $reactionRepo = $this->repository('XF:Reaction');
        $reactionRepo->recalculateReactionIsCounted('classifieds_listing', $this->listing_id);
    }

    protected function listingHidden($hardDelete = false)
    {
        $this->adjustUserListingCountIfNeeded(-1);

        if (!$hardDelete)
        {
            // on hard delete the likes will be removed which will do this
            /** @var \XF\Repository\Reaction $reactionRepo */
            $reactionRepo = $this->repository('XF:Reaction');
            $reactionRepo->fastUpdateReactionIsCounted('classifieds_listing', $this->listing_id, false);
        }

        /** @var \XF\Repository\UserAlert $alertRepo */
        $alertRepo = $this->repository('XF:UserAlert');
        $alertRepo->fastDeleteAlertsForContent('classifieds_listing', $this->listing_id);

    }

    protected function listingMoved(Category $from, Category $to)
    {
        if (!$this->isStateChanged('listing_state', 'visible'))
        {
            $newCounts = $to->listing_count;
            $oldCounts = $from->listing_count;
            if ($newCounts != $oldCounts)
            {
                $this->adjustUserListingCountIfNeeded($newCounts ? 1 : -1, true);
            }
        }
    }

    protected function submitHamData()
    {
        /** @var \XF\Spam\ContentChecker $submitter */
        $submitter = $this->app()->container('spam.contentHamSubmitter');
        $submitter->submitHam('classifieds_listing', $this->listing_id);
    }

    public function softDelete($reason = '', \XF\Entity\User $byUser = null)
    {
        $byUser = $byUser ?: \XF::visitor();

        if ($this->listing_state == 'deleted')
        {
            return false;
        }

        $this->listing_state = 'deleted';

        /** @var \XF\Entity\DeletionLog $deletionLog */
        $deletionLog = $this->getRelationOrDefault('DeletionLog');
        $deletionLog->setFromUser($byUser);
        $deletionLog->delete_reason = $reason;

        $this->save();

        return true;
    }

    public function updateCoverImageIfNeeded()
    {
        $attachments = $this->Attachments;

        $imageAttachments = array();
        $fileAttachments = array();

        foreach ($attachments AS $key => $attachment)
        {
            if ($attachment['thumbnail_url'])
            {
                $imageAttachments[$key] = $attachment;
            }
            else
            {
                $fileAttachments[$key] = $attachment;
            }
        }

        if (!$this->cover_image_id)
        {
            if ($imageAttachments)
            {
                foreach ($imageAttachments AS $imageAttachment)
                {
                    $coverImageId = $imageAttachment['attachment_id'];
                    break;
                }

                if ($coverImageId)
                {
                    $this->db()->query("
						UPDATE xf_z61_classifieds_listing
						SET cover_image_id = ?
						WHERE listing_id = ?
					", [$coverImageId, $this->listing_id]);
                }
            }
        }
        elseif ($this->cover_image_id)
        {
            if (!$imageAttachments || !$imageAttachments[$this->cover_image_id])
            {
                $this->db()->query("
					UPDATE xf_z61_classifieds_listing
					SET cover_image_id = 0
					WHERE listing_id = ?
				", $this->listing_id);
            }
        }

    }

    public function isUnread()
    {
        if ($this->listing_state == 'deleted')
        {
            return false;
        }

        if ($this->listing_state == 'redirect')
        {
            return false;
        }

        $readDate = $this->getVisitorReadDate();
        if ($readDate === null)
        {
            return false;
        }

        return $readDate < $this->listing_date;
    }

    public static function getStructure(Structure $structure)
    {
        $structure->table = 'xf_z61_classifieds_listing';
        $structure->shortName = 'Z61\Classifieds:Listing';
        $structure->contentType = 'classifieds_listing';
        $structure->primaryKey = 'listing_id';
        $structure->columns = [
            'listing_id' => ['type' => self::UINT, 'autoIncrement' => true],
            'listing_type_id' => ['type' => self::UINT,
                'required' => 'z61_classifieds_please_enter_valid_type'
            ],
            'condition_id' => ['type' => self::UINT,
                'required' => 'z61_classifieds_please_enter_valid_condition'
            ],
            'package_id' => ['type' => self::UINT,
                'required' => 'z61_classifieds_please_enter_valid_package'
            ],
            'category_id' => ['type' => self::UINT,
                'required' => 'z61_classifieds_please_enter_valid_category'
            ],
            'title' => ['type' => self::STR, 'maxLength' => 100,
                'required' => 'please_enter_valid_title'
            ],
            'content' => ['type' => self::STR,
                'required' => 'please_enter_valid_message',
                'censor' => true
            ],
            'listing_date' => ['type' => self::UINT, 'required' => true, 'default' => \XF::$time],
            'user_id' => ['type' => self::UINT, 'required' => true],
            'username' => ['type' => self::STR, 'maxLength' => 50,
                'required' => 'please_enter_valid_name'
            ],
            'discussion_thread_id' => ['type' => self::UINT, 'default' => 0],
            'ip_id' => ['type' => self::UINT, 'default' => 0],
            'view_count' => ['type' => self::UINT, 'default' => 0],
            'price' => ['type' => self::FLOAT, 'default' => 0, 'max' => 99999999, 'min' => 0],
            'currency' => ['type' => self::STR, 'default' => '', 'maxLength' => 3],
            'last_edit_date' => ['type' => self::UINT, 'default' => 0],
            'last_edit_user_id' => ['type' => self::UINT, 'default' => 0],
            'listing_state' => ['type' => self::STR, 'default' => 'visible',
                'allowedValues' => ['visible', 'moderated', 'deleted']
            ],
            'listing_status' => ['type' => self::STR, 'default' => 'active',
                'allowedValues' => ['active', 'awaiting_payment', 'sold', 'expired']
            ],
            'listing_open' => ['type' => self::BOOL, 'default' => true],
            'listing_location' => ['type' => self::STR, 'maxLength' => 255],
            'listing_location_data' => ['type' => self::JSON_ARRAY, 'default' => []],
            'warning_id' => ['type' => self::UINT, 'default' => 0],
            'warning_message' => ['type' => self::STR, 'default' => ''],
            'prefix_id' => ['type' => self::UINT, 'default' => 0],
            'attach_count' => ['type' => self::UINT, 'default' => 0],
            'custom_fields' => ['type' => self::JSON_ARRAY, 'default' => []],
            'tags' => ['type' => self::JSON_ARRAY, 'default' => []],
            'embed_metadata' => ['type' => self::JSON_ARRAY, 'default' => []],
            'expiration_date' => ['type' => self::UINT, 'default' => 0],
            'contact_email' => ['type' => self::STR, 'default' => '', 'maxLength' => 150],
            'contact_custom' => ['type' => self::STR, 'default' => '', 'maxLength' => 150],
            'contact_email_enable' => ['type' => self::BOOL, 'default' => true],
            'contact_conversation_enable' => ['type' => self::BOOL, 'default' => true],
            'location_lat' => ['type' => self::FLOAT, 'default' => 0.0, 'nullable' => true],
            'location_long' => ['type' => self::FLOAT, 'default' => 0.0, 'nullable' => true],
            'cover_image_id' => ['type' => self::INT, 'nullable' => true],
            'sold_user_id' => ['type' => self::UINT, 'nullable' => true],
            'sold_username' => ['type' => self::STR, 'nullable' => true]
        ];
        $structure->behaviors = [
            'XF:Reactable' => ['stateField' => 'listing_state'],
            'XF:Taggable' => ['stateField' => 'listing_state'],
            'XF:Indexable' => [
                'checkForUpdates' => ['title', 'content', 'category_id', 'user_id', 'discussion_thread_id', 'listing_date', 'listing_state', 'listing_status', 'tags']
            ],
            'XF:NewsFeedPublishable' => [
                'usernameField' => 'username',
                'dateField' => 'listing_date'
            ],
            'XF:CustomFieldsHolder' => [
                'valueTable' => 'xf_z61_classifieds_listing_field_value',
                'checkForUpdates' => ['category_id'],
                'getAllowedFields' => function($listing) { return $listing->Category ? $listing->Category->field_cache : []; }
            ]
        ];
        $structure->getters = [
            'custom_fields' => true,
            'expired' => true,
	        'purchasable_type_id' => true
        ];
        $structure->relations = [
            'Category' => [
                'entity' => 'Z61\Classifieds:Category',
                'type' => self::TO_ONE,
                'conditions' => 'category_id',
                'primary' => true,
            ],
            'Type' => [
                'entity' => 'Z61\Classifieds:ListingType',
                'type' => self::TO_ONE,
                'conditions' => 'listing_type_id',
                'primary' => true,
            ],
            'User' => [
                'entity' => 'XF:User',
                'type' => self::TO_ONE,
                'conditions' => 'user_id',
                'primary' => 'true'
            ],
            'SoldUser' => [
                'entity' => 'XF:User',
                'type' => self::TO_ONE,
                'conditions' => [['user_id', '=', '$sold_user_id']],
                'primary' => 'true'
            ],
            'Prefix' => [
                'entity' => 'Z61\Classifieds:ListingPrefix',
                'type' => self::TO_ONE,
                'conditions' => 'prefix_id',
                'primary' => true
            ],
            'Discussion' => [
                'entity' => 'XF:Thread',
                'type' => self::TO_ONE,
                'conditions' => [['thread_id', '=', '$discussion_thread_id']],
                'primary' => true
            ],
            'CoverImage' => [
                'entity' => 'XF:Attachment',
                'type' => self::TO_ONE,
                'conditions' => [
                    ['content_type', '=', 'classifieds_listing'],
                    ['content_id', '=', '$listing_id'],
                    ['attachment_id', '=', '$cover_image_id']
                ],
                'with' => 'Data',
                'order' => 'attach_date'
            ],
            'Attachments' => [
                'entity' => 'XF:Attachment',
                'type' => self::TO_MANY,
                'conditions' => [
                    ['content_type', '=', 'classifieds_listing'],
                    ['content_id', '=', '$listing_id']
                ],
                'with' => 'Data',
                'order' => 'attach_date'
            ],
            'ApprovalQueue' => [
                'entity' => 'XF:ApprovalQueue',
                'type' => self::TO_ONE,
                'conditions' => [
                    ['content_type', '=', 'classifieds_listing'],
                    ['content_id', '=', '$listing_id']
                ],
                'primary' => true
            ],
            'DeletionLog' => [
                'entity' => 'XF:DeletionLog',
                'type' => self::TO_ONE,
                'conditions' => [
                    ['content_type', '=', 'classifieds_listing'],
                    ['content_id', '=', '$listing_id']
                ],
                'primary' => true
            ],
            'Watch' => [
                'entity' => 'Z61\Classifieds:ListingWatch',
                'type' => self::TO_MANY,
                'conditions' => 'listing_id',
                'key' => 'user_id'
            ],
            'Featured' => [
                'entity' => 'Z61\Classifieds:ListingFeature',
                'type' => self::TO_ONE,
                'conditions' => 'listing_id',
                'primary' => true
            ],
            'Read' => [
                'entity' => 'Z61\Classifieds:ListingRead',
                'type' => self::TO_MANY,
                'conditions' => 'listing_id',
                'key' => 'user_id'
            ],
            'Condition' => [
                'entity' => 'Z61\Classifieds:Condition',
                'type' => self::TO_ONE,
                'conditions' => 'condition_id',
                'primary' => true,
            ],
            'Package' => [
                'entity' => 'Z61\Classifieds:Package',
                'type' => self::TO_ONE,
                'conditions' => 'package_id',
                'primary' => true,
            ],
            'Questions' => [
                'entity' => 'Z61\Classifieds:Question',
                'type' => self::TO_MANY,
                'conditions' => [
                    ['listing_id', '=', '$listing_id']
                ],
                'primary' => true,
            ],
        ];
        $structure->defaultWith = [
            'Category', 'User'
        ];
        $structure->options = [
            'log_moderator' => true
        ];

        static::addReactableStructureElements($structure);
        static::addBookmarkableStructureElements($structure);

        return $structure;
    }

    public function getBbCodeRenderOptions($context, $type)
    {
        return [
            'entity' => $this,
            'user' => $this->User,
            'attachments' => $this->attach_count ? $this->Attachments : [],
            'viewAttachments' => $this->canViewAttachments()
        ];
    }
}