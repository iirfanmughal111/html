<?php

namespace FS\Escrow\XF\Entity;

use XF\Mvc\Entity\Structure;

class User extends XFCP_User
{

    public static function getStructure(Structure $structure)
    {
        $structure = parent::getStructure($structure);

      //  $structure->columns['deposit_amount'] =  ['type' => self::FLOAT, 'default' => 0];
        $structure->columns['deposit_amount'] =  ['type' => self::STR, 'default' => 0];

        $structure->columns['crypto_address'] =  ['type' => self::STR,'default' => NULL];
     //   $structure->columns['escrow_otp'] =  ['type' => self::INT,'default' => 0];
    //    $structure->columns['last_otp_time'] =  ['type' => self::INT,'default' => 0];
    $structure->columns['public_key'] = ['type' => self::STR, 'default' => null];

    $structure->columns['encrypt_message'] = ['type' => self::STR, 'default' => null];

    $structure->columns['random_message'] = ['type' => self::STR, 'default' => null];

        

        return $structure;
    }
    
    public function hasEscrowAllow(){
        
       return $this->hasPermission('fs_esrow', 'allow_use_escrow');
    }

    public function getOrignolAmount(){
        $escrowService = \xf::app()->service('FS\Escrow:Escrow\EscrowServ');
        if ($this->deposit_amount){
        $data= $escrowService->decrypt($this->deposit_amount);
        $amount =  $data['amount'];
        
        }else{
            $amount = 0;
        }
        return   number_format((float)$amount, 2, '.', '');
        //return $amount;
     }
}