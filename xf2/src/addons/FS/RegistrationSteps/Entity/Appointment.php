<?php

namespace FS\RegistrationSteps\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

class Appointment extends Entity
{

    public static function getStructure(Structure $structure)
    {
        $structure->table = 'fs_register_appointments';
        $structure->shortName = 'FS\RegistrationSteps:Appointment';
        $structure->contentType = 'fs_register_appointments';
        $structure->primaryKey = 'appt_id';
        $structure->columns = [
            'appt_id' => ['type' => self::UINT, 'autoIncrement' => true],
            'time' => ['type' => self::UINT, 'default' => NUll],
            'date' => ['type' => self::STR, 'default' => NUll],
            'from_user_id' => ['type' => self::STR, 'default' => NUll],
            'to_user_id' => ['type' => self::STR, 'default' => NUll],
            'contact' => ['type' => self::STR, 'default' => NUll],
            'duration' => ['type' => self::STR, 'default' => NUll],
            'appt_type' => ['type' => self::STR, 'default' => NUll],
            'city' => ['type' => self::STR, 'default' => NUll],
            'rates' => ['type' => self::STR, 'default' => NUll],
            'promotion' => ['type' => self::STR, 'default' => NUll],

            'created_at' => ['type' => self::UINT, 'default' => \XF::$time],
        ];

        $structure->relations = [
            'User' => [
                'entity' => 'XF:User',
                'type' => self::TO_ONE,
                'conditions' =>  [
                    ['user_id', '=', '$from_user_id']
                ],
            ],
            'UserTo' => [
                'entity' => 'XF:User',
                'type' => self::TO_ONE,
                'conditions' =>  [
                    ['user_id', '=', '$to_user_id']
                ],
            ],
        ];


        $structure->defaultWith = [];
        $structure->getters = [];
        $structure->behaviors = [];

        return $structure;
    }
}