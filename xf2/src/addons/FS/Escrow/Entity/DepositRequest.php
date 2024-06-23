<?php

namespace FS\Escrow\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

class DepositRequest extends Entity
{

    public static function getStructure(Structure $structure)
    {
        $structure->table = 'fs_escrow_request_deposit';
        $structure->shortName = 'FS\Escrow:DepositRequest';
        $structure->contentType = 'fs_escrow_deposit_request';
        $structure->primaryKey = 'req_id';
        $structure->columns = [
            'req_id' => ['type' => self::UINT, 'autoIncrement' => true],
            'user_id' => ['type' => self::UINT, 'required' => true],
        //    'amount' => ['type' => self::FLOAT, 'required' => true],
            'amount' => ['type' => self::STR, 'required' => true],

            'external_id' => ['type' => self::STR, 'default' => ''],
            'transaction_id' => ['type' => self::UINT, 'required' => true],
            'created_at' => ['type' => self::UINT, 'default' => \XF::$time],
        ];

        $structure->relations = [
            'User' => [
                'entity' => 'XF:User',
                'type' => self::TO_ONE,
                'conditions' => 'user_id',
            ],
        ];
        $structure->defaultWith = [];
        $structure->getters = [];
        $structure->behaviors = [];

        return $structure;
    }
}