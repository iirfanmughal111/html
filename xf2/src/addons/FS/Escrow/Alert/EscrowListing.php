<?php

namespace FS\Escrow\Alert;

use XF\Mvc\Entity\Entity;
use XF\Alert\AbstractHandler;

class EscrowListing extends AbstractHandler
{
    public function getOptOutActions()
    {
        return [
            'escrow_cancel',
            'escrow_approve',
            'escrow_payment',
            'escrow_percentage',
        ];
    }

    public function getOptOutDisplayOrder()
    {
        return 200;
    }

    public function canViewContent(Entity $entity, &$error = null)
    {
        return true;
    }
}
