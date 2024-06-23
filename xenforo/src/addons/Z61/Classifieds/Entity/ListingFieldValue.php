<?php

namespace Z61\Classifieds\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property int listing_id
 * @property string field_id
 * @property string field_value
 */
class ListingFieldValue extends  Entity
{
    public static function getStructure(Structure $structure)
    {
        $structure->table = 'xf_z61_classifieds_listing_field_value';
        $structure->shortName = 'Z61\Classifieds:ListingFieldValue';
        $structure->primaryKey = ['listing_id', 'field_id'];
        $structure->columns = [
            'listing_id' => ['type' => self::UINT, 'required' => true],
            'field_id' => ['type' => self::STR, 'maxLength' => 25,
                'match' => 'alphanumeric'
            ],
            'field_value' => ['type' => self::STR, 'default' => '']
        ];
        $structure->getters = [];
        $structure->relations = [];

        return $structure;
    }
}