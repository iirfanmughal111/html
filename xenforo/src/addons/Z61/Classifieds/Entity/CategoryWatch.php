<?php

namespace Z61\Classifieds\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property int user_id
 * @property int category_id
 * @property string notify_on
 * @property bool send_alert
 * @property bool send_email
 * @property bool include_children
 *
 * RELATIONS
 * @property \Z61\Classifieds\Entity\Category Category
 * @property \XF\Entity\User User
 */
class CategoryWatch extends Entity
{
    public static function getStructure(Structure $structure)
    {
        $structure->table = 'xf_z61_classifieds_category_watch';
        $structure->shortName = 'Z61\Classifieds:CategoryWatch';
        $structure->primaryKey = ['user_id', 'category_id'];
        $structure->columns = [
            'user_id' => ['type' => self::UINT, 'required' => true],
            'category_id' => ['type' => self::UINT, 'required' => true],
            'notify_on' => ['type' => self::STR, 'default' => '',
                'allowedValues' => ['', 'classifieds_listing']
            ],
            'send_alert' => ['type' => self::BOOL, 'default' => false],
            'send_email' => ['type' => self::BOOL, 'default' => false],
            'include_children' => ['type' => self::BOOL, 'default' => false]
        ];
        $structure->getters = [];
        $structure->relations = [
            'Category' => [
                'entity' => 'Z61\Classifieds:Category',
                'type' => self::TO_ONE,
                'conditions' => 'category_id',
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