<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Entity;

use XF\Mvc\Entity\Structure;
use XF\Entity\AbstractFieldMap;

/**
 * COLUMNS
 * @property int $category_id
 * @property string $field_id
 *
 * RELATIONS
 * @property \Truonglv\Groups\Entity\Field $Field
 * @property \Truonglv\Groups\Entity\Category $Category
 */
class CategoryField extends AbstractFieldMap
{
    /**
     * @return string
     */
    public static function getContainerKey()
    {
        return 'category_id';
    }

    public static function getStructure(Structure $structure)
    {
        self::setupDefaultStructure(
            $structure,
            'xf_tl_group_category_field',
            'Truonglv\Groups:CategoryField',
            'Truonglv\Groups:Field'
        );

        $structure->relations['Category'] = [
            'type' => self::TO_ONE,
            'entity' => 'Truonglv\Groups:Category',
            'conditions' => 'category_id',
            'primary' => true
        ];

        return $structure;
    }
}
