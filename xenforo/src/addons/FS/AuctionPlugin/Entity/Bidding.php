<?php

namespace FS\AuctionPlugin\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

class Bidding extends Entity
{

    public static function getStructure(Structure $structure)
    {
        $structure->table = 'fs_auction_bidding';
        $structure->shortName = 'FS\AuctionPlugin:Bidding';
        $structure->contentType = 'fs_auction_bidding';
        $structure->primaryKey = 'bidding_id';
        $structure->columns = [
            'bidding_id' => ['type' => self::UINT, 'autoIncrement' => true],
            'user_id' => ['type' => self::UINT, 'required' => true],
            'auction_id' => ['type' => self::UINT, 'required' => true],
            'created_at' => ['type' => self::UINT, 'default' => \XF::$time],
            'bidding_amount' => ['type' => self::UINT, 'default' => 0],
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
