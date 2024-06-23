<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property int $user_id
 * @property int $event_id
 *
 * RELATIONS
 * @property \XF\Entity\User $User
 * @property \Truonglv\Groups\Entity\Event $Event
 */
class EventWatch extends Entity
{
    public static function getStructure(Structure $structure)
    {
        $structure->table = 'xf_tl_group_event_watch';
        $structure->shortName = 'Truonglv\Groups:EventWatch';
        $structure->primaryKey = ['user_id', 'event_id'];

        $structure->columns = [
            'user_id' => ['type' => self::UINT, 'required' => true],
            'event_id' => ['type' => self::UINT, 'required' => true]
        ];

        $structure->relations = [
            'User' => [
                'type' => self::TO_ONE,
                'entity' => 'XF:User',
                'conditions' => 'user_id',
                'primary' => true
            ],
            'Event' => [
                'type' => self::TO_ONE,
                'entity' => 'Truonglv\Groups:Event',
                'conditions' => 'event_id',
                'primary' => true
            ]
        ];

        return $structure;
    }
}
