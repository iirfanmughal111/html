<?php

namespace Z61\Classifieds\Entity;

use XF\Entity\AbstractPrefixMap;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property int category_id
 * @property int prefix_id
 *
 * RELATIONS
 * @property \Z61\Classifieds\Entity\ListingPrefix Prefix
 * @property \Z61\Classifieds\Entity\Category Category
 */
class CategoryPrefix extends AbstractPrefixMap
{
    public static function getContainerKey()
    {
        return 'category_id';
    }

    public static function getStructure(Structure $structure)
    {
        self::setupDefaultStructure($structure, 'xf_z61_classifieds_category_prefix', 'Z61\Classifieds:CategoryPrefix', 'Z61\Classifieds:ListingPrefix');

        $structure->relations['Category'] = [
            'entity' => 'Z61\Classifieds:Category',
            'type' => self::TO_ONE,
            'conditions' => 'category_id',
            'primary' => true
        ];

        return $structure;
    }
}