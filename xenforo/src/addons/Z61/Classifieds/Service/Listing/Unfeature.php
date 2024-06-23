<?php

namespace Z61\Classifieds\Service\Listing;

use XF\Entity\User;
use XF\Service\AbstractService;
use Z61\Classifieds\Entity\Listing;

class Unfeature extends AbstractService
{
    /** @var Listing */
    protected $listing;

    /** @var User */
    protected $user;

    protected $sendAlert = true;

    public function __construct(\XF\App $app, Listing $listing, User $user)
    {
        parent::__construct($app);

        $this->user = $user;
        $this->listing = $listing;
    }

    public function setSendAlert($sendAlert = true)
    {
        $this->sendAlert = $sendAlert;
    }

    public function unfeature()
    {
        $user = $this->user;
        $listing = $this->listing;

        $db = $this->db();
        $db->beginTransaction();

        if ($this->listing->Featured)
        {
            /** @var \Z61\Classifieds\Repository\Listing $listingRepo */
            $listingRepo = $this->repository('Z61\Classifieds:Listing');
            $listingRepo->unfeatureListing($listing);

            if ($listing->Category->paid_feature_enable && $this->sendAlert)
            {
                /** @var \XF\Repository\UserAlert $alertRepo */
                $alertRepo = $this->app->repository('XF:UserAlert');
                $alertRepo->alert($user, $user->user_id, $user->username, 'classifieds_listing', $user->user_id, 'feature_end');
            }
        }

        $db->commit();

        return true;
    }
}