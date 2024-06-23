<?php

namespace Z61\Classifieds\Notifier\Listing;


use XF\Notifier\AbstractNotifier;
use Z61\Classifieds\Entity\Listing;

class Sold extends AbstractNotifier
{
    /** @var Listing $listing */
    private $listing;

    public function __construct(\XF\App $app, Listing $listing)
    {
        parent::__construct($app);

        $this->listing = $listing;
    }

    public function canNotify(\XF\Entity\User $user)
    {
        return ($this->listing->isVisible() && $user->user_id != $this->listing->user_id) && $this->listing->canView($user);
    }

    public function sendAlert(\XF\Entity\User $user)
    {
        $listing = $this->listing;

        return $this->basicAlert(
            $user, $listing->user_id, $listing->username, 'classifieds_listing', $listing->listing_id, 'marked_sold'
        );
    }


}