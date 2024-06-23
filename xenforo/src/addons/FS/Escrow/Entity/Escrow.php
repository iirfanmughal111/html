<?php

namespace FS\Escrow\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

class Escrow extends Entity
{

/*
    Status Variable Abbreviation

    0 = Createed / Waiting for aproval
    1 = Approved / Processing
    2 = Cancelled by mentioned User (to_user)
    3 = Cancelled by Creater/Starter/Owner
    4 = Complete /Amount paid
*/

    public static function getStructure(Structure $structure)
    {
        $structure->table = 'fs_escrow';
        $structure->shortName = 'FS\Escrow:Escrow';
        $structure->contentType = 'fs_escrow';
        $structure->primaryKey = 'escrow_id';
        $structure->columns = [
            'escrow_id' => ['type' => self::UINT, 'autoIncrement' => true],
            'user_id' => ['type' => self::UINT, 'default' => 0],
            'to_user' => ['type' => self::UINT, 'default' => 0],
       //     'escrow_amount' => ['type' => self::UINT, 'default' => 0],
            'escrow_amount' => ['type' => self::STR, 'default' => 0],
            'thread_id' => ['type' => self::UINT, 'default' => 0],
            'transaction_id' => ['type' => self::UINT, 'default' => 0],
            'escrow_status' => ['type' => self::UINT, 'default' => 0],
            'admin_percentage' => ['type' => self::UINT, 'default' => 0],
            'last_update' => ['type' => self::UINT, 'default' => \XF::$time]
        ];

        $structure->relations = [
            'User' => [
                'entity' => 'XF:User',
                'type' => self::TO_ONE,
                'conditions' => [
                    ['user_id', '=', '$to_user'],
                ]
            ],
            'Thread' => [
                'entity' => 'XF:Thread',
                'type' => self::TO_ONE,
                'conditions' => 'thread_id',
                'primary' => true
            ],
            'Transaction' => [
                'entity' => 'FS\Escrow:Transaction',
                'type' => self::TO_ONE,
                'conditions' => 'transaction_id',
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

       $data= $escrowService->decrypt($this->escrow_amount);
       return   number_format((float)$data['amount'], 2, '.', '');
       
       //return $data['amount'];
     }
}