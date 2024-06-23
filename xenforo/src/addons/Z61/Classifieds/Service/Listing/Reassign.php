<?php

namespace Z61\Classifieds\Service\Listing;

use XF\Service\AbstractService;
use Z61\Classifieds\Entity\Listing;

class Reassign extends AbstractService
{
    /**
     * @var \Z61\Classifieds\Entity\Listing
     */
    protected $listing;

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

    public function setSendAlert($alert, $reason = null)
    {
        $this->alert = (bool)$alert;
        if ($reason !== null)
        {
            $this->alertReason = $reason;
        }
    }

    public function reassignTo(\XF\Entity\User $newUser)
    {
        $listing = $this->listing;
        $oldUser = $listing->User;
        $reassigned = ($listing->user_id != $newUser->user_id);

        $listing->user_id = $newUser->user_id;
        $listing->username = $newUser->username;
        $listing->save();

        if ($reassigned && $listing->isVisible() && $this->alert)
        {
            if (\XF::visitor()->user_id != $oldUser->user_id)
            {
                /** @var \Z61\Classifieds\Repository\Listing $listingRepo */
                $listingRepo = $this->repository('Z61\Classifieds:Listing');
                $listingRepo->sendModeratorActionAlert(
                    $this->listing, 'reassign_from', $this->alertReason, ['to' => $newUser->username], $oldUser
                );
            }

            if (\XF::visitor()->user_id != $newUser->user_id)
            {
                /** @var \Z61\Classifieds\Repository\Listing $listingRepo */
                $listingRepo = $this->repository('Z61\Classifieds:Listing');
                $listingRepo->sendModeratorActionAlert(
                    $this->listing, 'reassign_to', $this->alertReason, [], $newUser
                );
            }
        }

        return $reassigned;
    }
}