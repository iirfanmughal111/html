<?php

namespace FS\Escrow\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

class BithideTransactionRecord extends Entity
{

    public static function getStructure(Structure $structure)
    {
        $structure->table = 'fs_escrow_bithide_transaction';
        $structure->shortName = 'FS\Escrow:BithideTransactionRecord';
        $structure->contentType = 'fs_escrow_bithide_transaction';
        $structure->primaryKey = 'Id';
        $structure->columns = [
            'Id' => ['type' => self::UINT, 'autoIncrement' => true],
            'Type' => ['type' => self::UINT],
            'Date' => ['type' => self::STR],
            'TxId' => ['type' => self::STR],
            'Cryptocurrency' => ['type' => self::STR],
            'MerchantId' => ['type' => self::UINT],
            'MerchantName' => ['type' => self::STR],
            'Initiator' => ['type' => self::STR],
            'InitiatorId' => ['type' => self::STR],
            'Amount' => ['type' => self::FLOAT],
            'AmountUSD' => ['type' => self::FLOAT],
            'Rate' => ['type' => self::FLOAT],
            'Commission' => ['type' => self::FLOAT],
            'CommissionCurrency' => ['type' => self::STR],
            'AddressAdditionalInfo' => ['type' => self::STR],
            'DestinationAddress' => ['type' => self::STR],
            'SenderAddresses' => ['type' => self::JSON_ARRAY],
            'Comment' => ['type' => self::STR],
            'ExternalId' => ['type' => self::STR],
            'Status' => ['type' => self::UINT],
            'FailReason' => ['type' => self::STR],

        ];

        $structure->relations = [
         
        ];
        $structure->defaultWith = [];
        $structure->getters = [];
        $structure->behaviors = [];

        return $structure;
    }
}