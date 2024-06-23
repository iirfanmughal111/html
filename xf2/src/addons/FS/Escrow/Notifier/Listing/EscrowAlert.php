<?php

namespace FS\Escrow\Notifier\Listing;

use XF\Notifier\AbstractNotifier;
use FS\Escrow\Entity\Escrow;

class EscrowAlert extends AbstractNotifier
{
    /** @var Escrow $esction */
    private $esc;

    public function __construct(\XF\App $app, Escrow $esc)
    {
        parent::__construct($app);

        $this->esc = $esc;
    }

    public function canNotify(\XF\Entity\User $user)
    {
        return true;
    }

    public function escrowCancelAlert()
    {

        $visitor = \XF::visitor();

        $esc = $this->esc;


        return $this->basicAlert(
            $esc->Thread->User,
            $visitor->user_id,
            $visitor->username,
            'fs_escrow',
            $esc->escrow_id,
            'escrow_cancel',
            [
                'thread_id' => $esc->Thread->thread_id,
                'thread_title' => $esc->Thread->title,
            ]
        );
    }

    public function escrowApproveAlert()
    {

        $visitor = \XF::visitor();

        $esc = $this->esc;

        return $this->basicAlert(
            $esc->Thread->User,
            $visitor->user_id,
            $visitor->username,
            'fs_escrow',
            $esc->escrow_id,
            'escrow_approve',
            [
                'thread_id' => $esc->Thread->thread_id,
                'thread_title' => $esc->Thread->title,
            ]
        );
    }

    public function escrowPaymentAlert()
    {

        $esc = $this->esc;

        return $this->basicAlert(
            $esc->User,
            $esc->Thread->User->user_id,
            $esc->Thread->User->username,
            'fs_escrow',
            $esc->escrow_id,
            'escrow_payment',
            [
                'thread_id' => $esc->Thread->thread_id,
                'thread_title' => $esc->Thread->title,
            ]
        );
    }

    public function escrowCancelByOwnerAlert()
    {

        $esc = $this->esc;

        return $this->basicAlert(
            $esc->User,
            $esc->Thread->User->user_id,
            $esc->Thread->User->username,
            'fs_escrow',
            $esc->escrow_id,
            'escrow_cancel',
            [
                'thread_id' => $esc->Thread->thread_id,
                'thread_title' => $esc->Thread->title,
            ]
        );
    }

    public function escrowPercentageHolderUserAlert()
    {

        $percentageUser = \XF::em()->findOne('XF:User', ['user_id' => intval($this->app()->options()->fs_escrow_admin_Id)]);

        $esc = $this->esc;

        return $this->basicAlert(
            $percentageUser,
            $esc->Thread->User->user_id,
            $esc->Thread->User->username,
            'fs_escrow',
            $esc->escrow_id,
            'escrow_percentage',
            [
                'thread_id' => $esc->Thread->thread_id,
                'thread_title' => $esc->Thread->title,
            ]
        );
    }


    public function escrowDepositAmountAlert()
    {

        $visitor = \XF::visitor();

        $esc = $this->esc;

        return $this->basicAlert(
            $esc->Thread->User,
            $visitor->user_id,
            $visitor->username,
            'fs_escrow',
            $esc->escrow_id,
            'escrow_approve',
            [
                'thread_id' => $esc->Thread->thread_id,
                'thread_title' => $esc->Thread->title,
            ]
        );
    }

   
}