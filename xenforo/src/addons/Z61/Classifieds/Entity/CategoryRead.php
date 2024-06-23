<?php

namespace Z61\Classifieds\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property int|null category_read_id
 * @property int user_id
 * @property int category_id
 * @property int category_read_date
 *
 * RELATIONS
 * @property \XF\Entity\User User
 * @property \Z61\Classifieds\Entity\Category Category
 */
class CategoryRead extends Entity
{
    public static function getStructure(Structure $structure)
    {
        $structure->table = 'xf_z61_classifieds_category_read';
        $structure->shortName = 'Z61\Classifieds:CategoryRead';
        $structure->primaryKey = 'category_read_id';
        $structure->columns = [
            'category_read_id' => ['type' => self::UINT, 'autoIncrement' => true, 'nullable' => true],
            'user_id' => ['type' => self::UINT, 'required' => true],
            'category_id' => ['type' => self::UINT, 'required' => true],
            'category_read_date' => ['type' => self::UINT, 'required' => true]
        ];
        $structure->getters = [];
        $structure->relations = [
            'User' => [
                'entity' => 'XF:User',
                'type' => self::TO_ONE,
                'conditions' => 'user_id',
                'primary' => true
            ],
            'Category' => [
                'entity' => 'Z61\Classifieds:Category',
                'type' => self::TO_ONE,
                'conditions' => 'category_id',
                'primary' => true
            ],
        ];

        return $structure;
    }
}