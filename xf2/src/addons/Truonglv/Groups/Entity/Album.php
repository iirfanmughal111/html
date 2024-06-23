<?php
/**
 * @license
 * Copyright 2019 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property int $group_id
 * @property int $album_id
 *
 * RELATIONS
 * @property \Truonglv\Groups\Entity\Group $Group
 * @property \XFMG\Entity\Album $Album
 */
class Album extends Entity
{
    public static function getStructure(Structure $structure)
    {
        $structure->table = 'xf_tl_group_mg_album';
        $structure->primaryKey = 'album_id';
        $structure->shortName = 'Truonglv\Groups:Album';

        $structure->columns = [
            'group_id' => ['type' => self::UINT, 'required' => true],
            'album_id' => ['type' => self::UINT, 'required' => true]
        ];

        $structure->relations = [
            'Group' => [
                'type' => self::TO_ONE,
                'entity' => 'Truonglv\Groups:Group',
                'conditions' => 'group_id',
                'primary' => true,
                'with' => ['Category']
            ],
            'Album' => [
                'type' => self::TO_ONE,
                'entity' => 'XFMG:Album',
                'conditions' => 'album_id',
                'primary' => true
            ]
        ];

        return $structure;
    }
}
