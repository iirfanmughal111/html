<?php

namespace FS\Escrow\Notifier\Transaction;

use XF\Notifier\AbstractNotifier;
use FS\Escrow\Entity\Transaction;

class TransactionAlert extends AbstractNotifier
{
       /** @var Escrow $esction */
       private $transaction,$escrowService;

       public function __construct(\XF\App $app, Transaction $transaction)
       {
           parent::__construct($app);
   
           $this->transaction = $transaction;
           $this->escrowService = $app->service('FS\Escrow:Escrow\EscrowServ');

       }
   
       public function canNotify(\XF\Entity\User $user)
       {
           return true;
       }

       public function escrowDepositConfirmAlert($status)
       {
            $app = \XF::app();
   
           $transaction = $this->transaction;
           $dec_trans_amount = $this->escrowService->decrypt($transaction->transaction_amount);

           $admin = $app->finder('XF:User', ['user_id' => intval($this->app()->options()->fs_escrow_admin_Id)])->fetchOne();
           return $this->basicAlert(
               $transaction->User,
               intval($this->app()->options()->fs_escrow_admin_Id),
               $admin->username,
               'fs_escrow_transaction',
               $transaction->transaction_id,
               'deposit_confirm',
               [
                   'amount'=>$dec_trans_amount['amount'],
                   'username'=>$transaction->User->username,
                   'status'=>$status
               ]
           );
       }

       public function escrowWithdrawConfirmAlert($status)
       {
            $app = \XF::app();
   
           $transaction = $this->transaction;
            $dec_trans_amount = $this->escrowService->decrypt($transaction->transaction_amount);

           $admin = $app->finder('XF:User', ['user_id' => intval($this->app()->options()->fs_escrow_admin_Id)])->fetchOne();
           return $this->basicAlert(
               $transaction->User,
               intval($this->app()->options()->fs_escrow_admin_Id),
               $admin->username,
               'fs_escrow_transaction',
               $transaction->transaction_id,
               'withdraw_confirm',
               [
                   'amount'=>$dec_trans_amount['amount'],
                   'username'=>$transaction->User->username,
                   'status'=>$status
               ]
           );
       }
    
}