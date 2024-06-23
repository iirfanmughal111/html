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
 * @property int $feature_date
 * @property int $expire_date
 *
 * RELATIONS
 * @property \Truonglv\Groups\Entity\Group $Group
 */
class Feature extends Entity
{
    public static function getStructure(Structure $structure)
    {
        $structure->table = 'xf_tl_group_feature';
        $structure->primaryKey = 'group_id';
        $structure->shortName = 'Truonglv\Groups:Feature';

        $structure->columns = [
            'group_id' => ['type' => self::UINT, 'required' => true],
            'feature_date' => ['type' => self::UINT, 'default' => XF::$time],
            'expire_date' => ['type' => self::UINT, 'default' => 0]
        ];

        $structure->relations = [
            'Group' => [
                'type' => self::TO_ONE,
                'entity' => 'Truonglv\Groups:Group',
                'conditions' => 'group_id',
                'primary' => true
            ]
        ];

        return $structure;
    }
}
