<?php

namespace Z61\Classifieds\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property int user_id
 * @property int listing_id
 * @property bool email_subscribe
 *
 * RELATIONS
 * @property \Z61\Classifieds\Entity\Listing Listing
 * @property \XF\Entity\User User
 */
class ListingWatch extends Entity
{
    public static function getStructure(Structure $structure)
    {
        $structure->table = 'xf_z61_classifieds_listing_watch';
        $structure->shortName = 'Z61\Classifieds:ListingWatch';
        $structure->primaryKey = ['user_id', 'listing_id'];
        $structure->columns = [
            'user_id' => ['type' => self::UINT, 'required' => true],
            'listing_id' => ['type' => self::UINT, 'required' => true],
            'email_subscribe' => ['type' => self::BOOL, 'default' => false]
        ];
        $structure->getters = [];
        $structure->relations = [
            'Listing' => [
                'entity' => 'Z61\Classifieds:Listing',
                'type' => self::TO_ONE,
                'conditions' => 'listing_id',
                'primary' => true
            ],
            'User' => [
                'entity' => 'XF:User',
                'type' => self::TO_ONE,
                'conditions' => 'user_id',
                'primary' => true
            ],
        ];

        return $structure;
    }
}