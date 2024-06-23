<?php

namespace Z61\Classifieds\Entity;

use XF\Entity\AbstractFieldMap;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property int category_id
 * @property string field_id
 *
 * RELATIONS
 * @property \Z61\Classifieds\Entity\ListingField Field
 * @property \Z61\Classifieds\Entity\Category Category
 */
class CategoryField extends AbstractFieldMap
{
    public static function getContainerKey()
    {
        return 'category_id';
    }

    public static function getStructure(Structure $structure)
    {
        self::setupDefaultStructure($structure, 'xf_z61_classifieds_category_field', 'Z61\Classifieds:CategoryField', 'Z61\Classifieds:ListingField');

        $structure->relations['Category'] = [
            'entity' => 'Z61\Classifieds:Category',
            'type' => self::TO_ONE,
            'conditions' => 'category_id',
            'primary' => true
        ];

        return $structure;
    }
}