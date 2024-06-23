<?php

namespace Truonglv\Groups\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property int $group_id
 * @property string $field_id
 * @property string $field_value
 */
class GroupFieldValue extends Entity
{
    public static function getStructure(Structure $structure)
    {
        $structure->table = 'xf_tl_group_field_value';
        $structure->shortName = 'Truonglv\Groups:GroupFieldValue';
        $structure->primaryKey = ['group_id', 'field_id'];

        $structure->columns = [
            'group_id' => ['type' => self::UINT, 'required' => true],
            'field_id' => ['type' => self::STR, 'maxLength' => 25,
                'match' => 'alphanumeric'
            ],
            'field_value' => ['type' => self::STR, 'default' => '']
        ];

        $structure->getters = [];
        $structure->relations = [];

        return $structure;
    }
}
