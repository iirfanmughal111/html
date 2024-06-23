<?php

namespace Z61\Classifieds\Service\Listing;


use XF\Service\AbstractService;
use Z61\Classifieds\Entity\Listing;

class Preparer extends AbstractService
{
    /**
     * @var Listing
     */
    protected $listing;

    protected $attachmentHash;

    protected $logIp = true;

    protected $mentionedUsers = [];

    public function __construct(\XF\App $app, Listing $listing)
    {
        parent::__construct($app);
        $this->setListing($listing);
    }

    public function setListing(Listing $listing)
    {
        $this->listing = $listing;
    }

    public function getListing()
    {
        return $this->listing;
    }

    public function logIp($logIp)
    {
        $this->logIp = $logIp;
    }

    public function getMentionedUsers($limitPermissions = true)
    {
        if ($limitPermissions)
        {
            /** @var \XF\Entity\User $user */
            $user = $this->listing->User ?: $this->repository('XF:User')->getGuestUser();
            return $user->getAllowedUserMentions($this->mentionedUsers);
        }
        else
        {
            return $this->mentionedUsers;
        }
    }

    public function getMentionedUserIds($limitPermissions = true)
    {
        return array_keys($this->getMentionedUsers($limitPermissions));
    }

    public function setContent($content, $format = true, $checkValidity = true)
    {
        $preparer = $this->getMessagePreparer($format);
        $this->listing->content = $preparer->prepare($content, $checkValidity);
        $this->listing->embed_metadata = $preparer->getEmbedMetadata();
        $this->mentionedUsers = $preparer->getMentionedUsers();

        return $preparer->pushEntityErrorIfInvalid($this->listing);
    }

    public function setAttachmentHash($hash)
    {
        $this->attachmentHash = $hash;
    }

    public function associateAttachments($hash)
    {
        $listing = $this->listing;

        /** @var \XF\Service\Attachment\Preparer $inserter */
        $inserter = $this->service('XF:Attachment\Preparer');
        $associated = $inserter->associateAttachmentsWithContent($hash, 'classifieds_listing', $listing->listing_id);
        if ($associated)
        {
            $listing->fastUpdate('attach_count', $listing->attach_count + $associated);
        }
    }

    public function checkForSpam()
    {
        $listing = $this->listing;
        if ($this->listing->listing_state == 'visible' && \XF::visitor()->isSpamCheckRequired())
        {

            /** @var \XF\Entity\User $user */
            $user = $listing->User ?: $this->repository('XF:User')->getGuestUser($listing->username);

            $message = $listing->title . "\n" . $listing->content;

            $checker = $this->app->spam()->contentChecker();
            $checker->check($user, $message, [
                'permalink' => $this->app->router('public')->buildLink('canonical:listing', $listing),
                'content_type' => 'classifieds_listing'
            ]);

            $decision = $checker->getFinalDecision();
            switch ($decision)
            {
                case 'moderated':
                    $listing->listing_state = 'moderated';
                    break;
                case 'denied':
                    $checker->logSpamTrigger('classifieds_listing', null);
                    $listing->error(\XF::phrase('your_content_cannot_be_submitted_try_later'));
                    break;
            }
        }
    }

    public function validateFiles(&$error = null)
    {
        $listing = $this->listing;
        $category = $listing->Category;
        if (!$category)
        {
            throw new \LogicException("Could not find category for classifieds listing");
        }

        if ($this->attachmentHash && $category->require_listing_image)
        {
            $totalImageAttachments = $this->finder('XF:Attachment')
                ->with('Data', true)
                ->where('temp_hash', $this->attachmentHash)
                ->where('Data.width', '>', 0)
                ->total();

            if (!$totalImageAttachments)
            {
                $error = \XF::phrase('z61_classifieds_you_must_upload_at_least_one_image_attachment');
                return false;
            }
        }

        return true;
    }

    public function writeIpLog($ip)
    {
        $listing = $this->listing;

        /** @var \XF\Repository\IP $ipRepo */
        $ipRepo = $this->repository('XF:Ip');
        $ipEnt = $ipRepo->logIp($listing->user_id, $ip, 'classifieds_listing', $listing->listing_id);
        if ($ipEnt)
        {
            $listing->fastUpdate('ip_id', $ipEnt->ip_id);
        }
    }

    public function postInsert()
    {
        if ($this->attachmentHash)
        {
            $this->associateAttachments($this->attachmentHash);
        }

        $this->updateCoverImageIfNeeded();

        if ($this->logIp)
        {
            $ip = ($this->logIp === true ? $this->app->request()->getIp() : $this->logIp);
            $this->writeIpLog($ip);
        }

        $checker = $this->app->spam()->contentChecker();

        $listing = $this->listing;

        $checker->logContentSpamCheck('classifieds_listing', $listing->listing_id);
        $checker->logSpamTrigger('classifieds_listing', $listing->listing_id);
        $this->repository('Z61\Classifieds:ListingWatch')->autoWatchListing($listing, \XF::visitor(), true);
    }

    public function postUpdate()
    {
        if ($this->attachmentHash)
        {
            $this->associateAttachments($this->attachmentHash);
        }

        if ($this->logIp)
        {
            $ip = ($this->logIp === true ? $this->app->request()->getIp() : $this->logIp);
            $this->writeIpLog($ip);
        }

        $this->updateCoverImageIfNeeded();

        $listing = $this->listing;
        $checker = $this->app->spam()->contentChecker();

        $checker->logSpamTrigger('classifieds_listing', $listing->listing_id);
    }

    public function updateCoverImageIfNeeded()
    {
        $listing = $this->listing;
        $attachments = $this->listing->Attachments;

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

        if (!$this->listing->cover_image_id)
        {
            // Things to do if no cover image id is set

            if ($imageAttachments)
            {
                foreach ($imageAttachments AS $imageAttachment)
                {
                    $coverImageId = $imageAttachment['attachment_id'];
                    break;
                }

                if ($coverImageId)
                {
                    $listing->fastUpdate('cover_image_id', $coverImageId);
                }
            }
        }
        elseif ($this->listing->cover_image_id)
        {
            // Things to check/do if a cover image id is set

            if (!$imageAttachments)
            {
                // if there are no longer any image attachments, then there can't be a cover image, so set the cover image id to 0

                $listing->fastUpdate('cover_image_id',0);
            }
            elseif (array_key_exists($this->listing->cover_image_id, $imageAttachments))
            {
                // do nothing as the cover image exists.
            }
            else
            {
                // if it gets to this point, lets set the first attachment as the cover image id since the old cover image has been removed!

                foreach ($imageAttachments AS $imageAttachment)
                {
                    $coverImageId = $imageAttachment['attachment_id'];
                    break;
                }

                if ($coverImageId)
                {
                    $listing->fastUpdate('cover_image_id', $coverImageId);
                }
            }
        }
    }

    /**
     * @param bool $format
     *
     * @return \XF\Service\Message\Preparer
     */
    protected function getMessagePreparer($format = true)
    {

        // TODO: make it an option
        $maxImages = 20;
        $maxMedia = 30;

        /** @var \XF\Service\Message\Preparer $preparer */
        $preparer = $this->service('XF:Message\Preparer', 'classifieds_listing', $this->listing);
        $preparer->setConstraint('maxImages', $maxImages);
        $preparer->setConstraint('maxMedia', $maxMedia);

        if (!$format)
        {
            $preparer->disableAllFilters();
        }

        return $preparer;
    }

}