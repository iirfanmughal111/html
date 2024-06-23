<?php

namespace Z61\Classifieds\Service\Listing;

use XF\Entity\User;
use XF\Service\AbstractService;
use Z61\Classifieds\Entity\Listing;

class Delete extends AbstractService
{
    /**
     * @var Listing
     */
    protected $listing;

    /** @var \XF\Entity\User|null */
    protected $user;

    protected $alert = false;
    protected $alertReason = '';

    public function __construct(\XF\App $app, Listing $listing)
    {
        parent::__construct($app);

        $this->listing = $listing;
    }

    public function getListing()
    {
        return $this->listing;
    }

    public function setUser(User $user = null)
    {
        $this->user = $user;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setSendAlert($alert, $reason = null)
    {
        $this->alert = (bool)$alert;
        if ($reason !== null)
        {
            $this->alertReason = $reason;
        }
    }

    public function delete($type, $reason = '')
    {
        $user = $this->user ?: \XF::visitor();
        $wasVisible = $this->listing->isVisible();

        if ($type == 'soft')
        {
            $result = $this->listing->softDelete($reason, $user);
        }
        else
        {
            $result = $this->listing->delete();
        }

        $this->updateListingThread();

        if ($result && $wasVisible && $this->alert && $this->listing->user_id != $user->user_id)
        {
            /** @var \Z61\Classifieds\Repository\listing $listingRepo */
            $listingRepo = $this->repository('Z61\Classifieds:Listing');
            $listingRepo->sendModeratorActionAlert($this->listing, 'delete', $this->alertReason);
        }

        return $result;
    }

    /**
     * @throws \Exception
     */
    protected function updateListingThread()
    {
        $listing = $this->listing;
        $thread = $listing->Discussion;
        if (!$thread)
        {
            return;
        }

        $asUser = $listing->User ?: $this->repository('XF:User')->getGuestUser($listing->username);

        \XF::asVisitor($asUser, function() use ($thread)
        {
            $replier = $this->setupListingThreadReply($thread);
            if ($replier && $replier->validate())
            {
                $existingLastPostDate = $replier->getThread()->last_post_date;

                $post = $replier->save();
                $this->afterListingThreadReplied($post, $existingLastPostDate);

                \XF::runLater(function() use ($replier)
                {
                    $replier->sendNotifications();
                });
            }
        });
    }

    protected function setupListingThreadReply(\XF\Entity\Thread $thread)
    {
        /** @var \XF\Service\Thread\Replier $replier */
        $replier = $this->service('XF:Thread\Replier', $thread);
        $replier->setIsAutomated();
        $replier->setMessage($this->getThreadReplyMessage(), false);

        return $replier;
    }

    protected function getThreadReplyMessage()
    {
        $listing = $this->listing;

        $phrase = \XF::phrase('z61_classifieds_listing_thread_delete', [
            'title' => $listing->title,
            'username' => $listing->User ? $listing->User->username : $listing->username
        ]);

        return $phrase->render('raw');
    }

    protected function afterListingThreadReplied(\XF\Entity\Post $post, $existingLastPostDate)
    {
        $thread = $post->Thread;

        if (\XF::visitor()->user_id && $post->Thread->getVisitorReadDate() >= $existingLastPostDate)
        {
            $this->repository('XF:Thread')->markThreadReadByVisitor($thread);
        }
    }
}