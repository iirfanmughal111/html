<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Entity;

use XF;
use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property int $group_id
 * @property int $user_id
 * @property int $view_date
 *
 * RELATIONS
 * @property \Truonglv\Groups\Entity\Group $Group
 * @property \Truonglv\Groups\Entity\Member $Member
 */
class GroupView extends Entity
{
    public static function getStructure(Structure $structure)
    {
        $structure->table = 'xf_tl_group_view';
        $structure->primaryKey = ['group_id', 'user_id'];
        $structure->shortName = 'Truonglv\Groups:GroupView';
        $structure->columns = [
            'group_id' => ['type' => self::UINT, 'required' => true],
            'user_id' => ['type' => self::UINT, 'required' => true],
            'view_date' => ['type' => self::UINT, 'default' => XF::$time]
        ];

        $structure->relations = [
            'Group' => [
                'type' => self::TO_ONE,
                'entity' => 'Truonglv\Groups:Group',
                'conditions' => 'group_id',
                'primary' => true
            ],
            'Member' => [
                'type' => self::TO_ONE,
                'entity' => 'Truonglv\Groups:Member',
                'conditions' => [
                    ['group_id', '=', '$group_id'],
                    ['user_id', '=', '$user_id']
                ],
                'primary' => false
            ]
        ];

        return $structure;
    }
}
