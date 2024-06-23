<?php

namespace FS\PostCounter\Entity;

use XF\Mvc\Entity\Structure;

class PostCounter extends \XF\Mvc\Entity\Entity
{
    public static function getStructure(Structure $structure)
    {
        $structure->table = 'fs_post_counter';
        $structure->shortName = 'FS\PostCounter:PostCounter';
        $structure->primaryKey = ['user_id', 'node_id'];
        $structure->columns = [
            'user_id' => ['type' => self::UINT],
            'node_id' => ['type' => self::UINT],
            'post_count' => ['type' => self::UINT, 'default' => 0],
            'thread_count' => ['type' => self::UINT, 'default' => 0]
        ];

        return $structure;
    }
}
