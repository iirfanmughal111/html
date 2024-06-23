<?php

namespace Truonglv\Groups\Entity;

use XF;
use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property int|null $log_id
 * @property int $group_id
 * @property string $content_type
 * @property int $content_id
 * @property string $action
 * @property array $extra_data
 * @property int $user_id
 * @property int $log_date
 *
 * RELATIONS
 * @property \Truonglv\Groups\Entity\Group $Group
 * @property \XF\Entity\User $User
 */
class Log extends Entity
{
    public static function getStructure(Structure $structure)
    {
        $structure->table = 'xf_tl_group_action_log';
        $structure->primaryKey = 'log_id';
        $structure->shortName = 'Truonglv\Groups:Log';

        $structure->columns = [
            'log_id' => ['type' => self::UINT, 'nullable' => true, 'autoIncrement' => true],
            'group_id' => ['type' => self::UINT, 'required' => true],
            'content_type' => ['type' => self::STR, 'required' => true, 'maxLength' => 25],
            'content_id' => ['type' => self::UINT, 'required' => true],
            'action' => ['type' => self::STR, 'required' => true],
            'extra_data' => ['type' => self::JSON_ARRAY, 'default' => []],
            'user_id' => ['type' => self::UINT, 'required' => true],
            'log_date' => ['type' => self::UINT, 'default' => XF::$time]
        ];

        $structure->relations = [
            'Group' => [
                'type' => self::TO_ONE,
                'entity' => 'Truonglv\Groups:Group',
                'conditions' => 'group_id',
                'primary' => true
            ],
            'User' => [
                'type' => self::TO_ONE,
                'entity' => 'XF:User',
                'conditions' => 'user_id',
                'primary' => true
            ]
        ];

        return $structure;
    }
}
