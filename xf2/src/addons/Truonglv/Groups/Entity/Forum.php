<?php
/**
 * @license
 * Copyright 2019 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;
use Truonglv\Groups\Option\GroupNodeCache;

/**
 * COLUMNS
 * @property int $group_id
 * @property int $node_id
 *
 * RELATIONS
 * @property \Truonglv\Groups\Entity\Group $Group
 * @property \XF\Entity\Forum $Forum
 */
class Forum extends Entity
{
    public static function getStructure(Structure $structure)
    {
        $structure->table = 'xf_tl_group_forum';
        $structure->primaryKey = 'node_id';
        $structure->shortName = 'Truonglv\Groups:Forum';

        $structure->columns = [
            'group_id' => ['type' => self::UINT, 'required' => true],
            'node_id' => ['type' => self::UINT, 'required' => true]
        ];

        $structure->relations = [
            'Group' => [
                'type' => self::TO_ONE,
                'entity' => 'Truonglv\Groups:Group',
                'conditions' => 'group_id',
                'primary' => true
            ],
            'Forum' => [
                'type' => self::TO_ONE,
                'entity' => 'XF:Forum',
                'conditions' => 'node_id',
                'primary' => true
            ]
        ];

        $structure->behaviors = [
            'Truonglv\Groups:Countable' => [
                'relationKey' => 'group_id',
                'relationName' => 'Group',
                'countField' => 'node_count'
            ]
        ];

        return $structure;
    }

    protected function _postSave()
    {
        if ($this->isInsert()) {
            GroupNodeCache::onGroupForumSaved($this);
        }
    }

    protected function _postDelete()
    {
        GroupNodeCache::onGroupForumDeleted($this->node_id);
    }
}
