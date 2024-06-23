<?php

namespace FS\Escrow\Alert;

use XF\Mvc\Entity\Entity;
use XF\Alert\AbstractHandler;

class EscrowTransaction extends AbstractHandler
{
    public function getOptOutActions()
    {
        return [
            'deposit_confirm',
            'withdraw_confirm',

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