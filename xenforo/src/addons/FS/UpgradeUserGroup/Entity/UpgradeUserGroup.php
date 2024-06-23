<?php

namespace FS\UpgradeUserGroup\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

class UpgradeUserGroup extends Entity
{

    public static function getStructure(Structure $structure)
    {
        $structure->table = 'fs_upgrade_userGroup';
        $structure->shortName = 'FS\UpgradeUserGroup:UpgradeUserGroup';
        $structure->contentType = 'fs_upgrade_userGroup';
        $structure->primaryKey = 'usg_id';
        $structure->columns = [
            'usg_id' => ['type' => self::UINT, 'autoIncrement' => true],
            'exist_userGroup' => ['type' => self::UINT, 'default' => null],
            'user_id' => ['type' => self::UINT, 'default' => null],
            'total_messages' => ['type' => self::UINT, 'default' => null],
            'upgrade_userGroup' => ['type' => self::UINT, 'default' => null],
        ];

        $structure->relations = [
            'User' => [
                'entity' => 'XF:User',
                'type' => self::TO_ONE,
                'conditions' => 'user_id',
            ],

            'UserGroup' => [
                'entity' => 'XF:UserGroup',
                'type' => self::TO_ONE,
                'conditions' => [
                    ['user_group_id', '=', '$exist_userGroup'],
                ],
            ],

            'UserGroups' => [
                'entity' => 'XF:UserGroup',
                'type' => self::TO_ONE,
                'conditions' => [
                    ['user_group_id', '=', '$upgrade_userGroup'],
                ],
            ],
        ];
        $structure->defaultWith = [];
        $structure->getters = [];
        $structure->behaviors = [];

        return $structure;
    }
}