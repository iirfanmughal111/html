<?php

namespace Z61\Classifieds\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property int|null listing_read_id
 * @property int user_id
 * @property int listing_id
 * @property int listing_read_date
 *
 * RELATIONS
 * @property \XF\Entity\User User
 * @property \Z61\Classifieds\Entity\Listing Listing
 */
class ListingRead extends Entity
{
    public static function getStructure(Structure $structure)
    {
        $structure->table = 'xf_z61_classifieds_listing_read';
        $structure->shortName = 'Z61\Classifieds:ListingRead';
        $structure->primaryKey = 'listing_read_id';
        $structure->columns = [
            'listing_read_id' => ['type' => self::UINT, 'autoIncrement' => true, 'nullable' => true],
            'user_id' => ['type' => self::UINT, 'required' => true],
            'listing_id' => ['type' => self::UINT, 'required' => true],
            'listing_read_date' => ['type' => self::UINT, 'required' => true]
        ];
        $structure->getters = [];
        $structure->relations = [
            'User' => [
                'entity' => 'XF:User',
                'type' => self::TO_ONE,
                'conditions' => 'user_id',
                'primary' => true
            ],
            'Listing' => [
                'entity' => 'Z61\Classifieds:Listing',
                'type' => self::TO_ONE,
                'conditions' => 'listing_id',
                'primary' => true
            ],
        ];

        return $structure;
    }
}