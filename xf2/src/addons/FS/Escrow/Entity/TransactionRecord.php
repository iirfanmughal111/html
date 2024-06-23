<?php

namespace FS\Escrow\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

class TransactionRecord extends Entity
{

    public static function getStructure(Structure $structure)
    {
        $structure->table = 'fs_escrow_transactions_record';
        $structure->shortName = 'FS\Escrow:TransactionRecord';
        $structure->contentType = 'fs_escrow_transaction_record';
        $structure->primaryKey = 'trx_id';
        $structure->columns = [
            'trx_id' => ['type' => self::UINT, 'autoIncrement' => true,'forceSet'=>true],
            'user_id' => ['type' => self::UINT, 'required' => true],
            'status' => ['type' => self::UINT, 'default' => 0],
            'Amount' => ['type' => self::FLOAT, 'default' => 0],
            'TxId' => ['type' => self::STR, 'required' => true],
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