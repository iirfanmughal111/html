<?php

namespace FS\Escrow\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

class Transaction extends Entity
{

    public static function getStructure(Structure $structure)
    {

        /**
         * status
         *  0 = default
         *  1 = Withdraw
         *  2 = Deposit
         *  2 = Payment
         */
        $structure->table = 'fs_escrow_transaction';
        $structure->shortName = 'FS\Escrow:Transaction';
        $structure->contentType = 'fs_escrow_transaction';
        $structure->primaryKey = 'transaction_id';
        $structure->columns = [
            'transaction_id' => ['type' => self::UINT, 'autoIncrement' => true],
            'user_id' => ['type' => self::UINT, 'required' => true],
            'to_user' => ['type' => self::UINT, 'default' => 0],
            'escrow_id' => ['type' => self::UINT, 'default' => 0],
        //    'transaction_amount' => ['type' => self::FLOAT, 'required' => true],
            'transaction_amount' => ['type' => self::STR, 'required' => true],
            'current_amount' => ['type' => self::STR, 'required' => true],
  //          'current_amount' => ['type' => self::FLOAT, 'required' => true],
//
            'transaction_type' => ['type' => self::STR, 'default' => ''],
            'status' => ['type' => self::UINT, 'default' => 0],
            'conversation_id' => ['type' => self::UINT, 'default' => 0],
            'created_at' => ['type' => self::UINT, 'default' => \XF::$time],
        ];

        $structure->relations = [
            'User' => [
                'entity' => 'XF:User',
                'type' => self::TO_ONE,
                'conditions' => 'user_id',
            ],
            'Escrow' => [
                'entity' => 'FS\Escrow:Escrow',
                'type' => self::TO_ONE,
                'conditions' => 'escrow_id',
                'primary' => true
            ],
            
            'Conversation' => [
                'entity' => 'XF:ConversationMaster',
                'type' => self::TO_ONE,
                'conditions' => 'conversation_id',
                'primary' => true
            ],
        ];
        $structure->defaultWith = [];
        $structure->getters = [];
        $structure->behaviors = [];

        return $structure;
    }
    public function getOrignolAmount(){
        $escrowService = \xf::app()->service('FS\Escrow:Escrow\EscrowServ');

       $data= $escrowService->decrypt($this->transaction_amount);
        
       return $data['amount'];
     }

     public function getOrignolBalance(){
        $escrowService = \xf::app()->service('FS\Escrow:Escrow\EscrowServ');

       $data= $escrowService->decrypt($this->current_amount);
        
       return $data['amount'];
     }
}