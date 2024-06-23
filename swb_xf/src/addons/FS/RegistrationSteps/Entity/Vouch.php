<?php

namespace FS\RegistrationSteps\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

class Vouch extends Entity
{

    public static function getStructure(Structure $structure)
    {
        $structure->table = 'fs_register_vouch';
        $structure->shortName = 'FS\RegistrationSteps:Vouch';
        $structure->contentType = 'fs_register_vouch';
        $structure->primaryKey = 'vouch_id';
        $structure->columns = [
            'vouch_id' => ['type' => self::UINT, 'autoIncrement' => true],
            'vouch_from_user_id' => ['type' => self::UINT, 'required' => true],
            'vouch_to_user_id' => ['type' => self::STR, 'required' => true],
            'created_at' => ['type' => self::UINT, 'default' => \XF::$time],
        ];

        $structure->relations = [
            'User' => [
                'entity' => 'XF:User',
                'type' => self::TO_ONE,
                'conditions' =>  [
                    ['user_id', '=', '$vouch_from_user_id']
                ],
            ],
            'UserTo' => [
                'entity' => 'XF:User',
                'type' => self::TO_ONE,
                'conditions' =>  [
                    ['user_id', '=', '$vouch_to_user_id']
                ],
            ],
        ];


        $structure->defaultWith = [];
        $structure->getters = [];
        $structure->behaviors = [];

        return $structure;
    }
}
