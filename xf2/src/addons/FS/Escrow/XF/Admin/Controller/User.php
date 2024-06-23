<?php

namespace FS\Escrow\XF\Admin\Controller;

use XF\Mvc\ParameterBag;

class User extends XFCP_User
{
	protected function userSaveProcess(\XF\Entity\User $user)
	{
        $parent = parent::userSaveProcess($user);
        
        
        $amount = $this->filter(
			
			[
		    'user'=>[
		
				'deposit_amount'=>'uint'
			]
				
			]
		
	);
							
        if ($amount['user']['deposit_amount']<0){
            throw $this->exception(
                $this->error(\XF::phrase("fs_escrow_amount_required"))
            );
        }
        elseif($amount['user']['deposit_amount']>0) {
        $escrowService = \xf::app()->service('FS\Escrow:Escrow\EscrowServ');
        $dec_user_amount = $escrowService->decrypt($user->deposit_amount);
        if ($dec_user_amount){
            $user_amount = $dec_user_amount['amount'];
        }else{
            $user_amount = 0;
            
        }
        $total = $amount['user']['deposit_amount']+$user_amount;
        $this->insertTransaction($user, $amount['user']['deposit_amount'],$total);
        
        $enc_amount = $escrowService->encrypt($escrowService->encyptData($total));
        $amount['user']['deposit_amount'] = $enc_amount;
		$parent->basicEntitySave($user, $amount['user']);
    }
        
        return $parent;
        
    }

    protected function insertTransaction($user,$amount,$total)
    {

		if(!$amount){
		
			return ;
		}

        $escrowService = \xf::app()->service('FS\Escrow:Escrow\EscrowServ');
        $transaction = $escrowService->escrowTransaction($user->user_id, $amount, $total, 'Deposit by Admin', 0,2);
        
        return true;
    }
}