<?php

namespace Z61\Classifieds\Notifier\Listing;

use XF\Notifier\AbstractNotifier;

abstract class AbstractWatch extends AbstractNotifier
{
    /**
     * @var \Z61\Classifieds\Entity\Listing
     */
    protected $listing;
    protected $actionType;
    protected $isApplicable;
    abstract protected function getDefaultWatchNotifyData();
    abstract protected function getWatchEmailTemplateName();
    public function __construct(\XF\App $app, \Z61\Classifieds\Entity\Listing $listing)
    {
        parent::__construct($app);

        $this->listing = $listing;
        $this->isApplicable = $this->isApplicable();
    }

    protected function isApplicable()
    {
        if (!$this->listing->isVisible())
        {
            return false;
        }

        return true;
    }

    public function canNotify(\XF\Entity\User $user)
    {
        if (!$this->isApplicable)
        {
            return false;
        }

        $listing = $this->listing;

        if ($user->user_id == $listing->user_id || $user->isIgnoring($listing->user_id))
        {
            return false;
        }

        return true;
    }

    public function sendAlert(\XF\Entity\User $user)
    {
        $listing = $this->listing;

        return $this->basicAlert(
            $user, $listing->user_id, $listing->username, 'classifieds_listing', $listing->listing_id, 'insert'
        );
    }

    public function sendEmail(\XF\Entity\User $user)
    {
        if (!$user->email || $user->user_state != 'valid')
        {
            return false;
        }

        $listing = $this->listing;

        $params = [
            'listing' => $listing,
            'category' => $listing->Category,
            'receiver' => $user
        ];

        $template = $this->getWatchEmailTemplateName();

        $this->app()->mailer()->newMail()
            ->setToUser($user)
            ->setTemplate($template, $params)
            ->queue();

        return true;
    }

    public function getDefaultNotifyData()
    {
        if (!$this->isApplicable)
        {
            return [];
        }

        return $this->getDefaultWatchNotifyData();
    }
}