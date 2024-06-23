<?php

namespace FS\RegistrationSteps\XF\Admin\Controller;

use XF\Mvc\ParameterBag;

class User extends XFCP_User
{
	protected function userSaveProcess(\XF\Entity\User $user)
	{
        $parent = parent::userSaveProcess($user);
        
        
        $featured = $this->filter(
			[
		    'user'=>[
		
				'is_featured'=>'uint'
			]
			]
	);
   
		$parent->basicEntitySave($user, $featured['user']);
    
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