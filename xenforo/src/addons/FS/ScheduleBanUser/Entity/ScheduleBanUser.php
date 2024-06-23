<?php

namespace FS\ScheduleBanUser\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

class ScheduleBanUser extends Entity
{

    public static function getStructure(Structure $structure)
    {

        $structure->table = 'fs_schedule_ban_user';
        $structure->shortName = 'FS\ScheduleBanUser:ScheduleBanUser';
        $structure->contentType = 'fs_schedule_ban_user';
        $structure->primaryKey = 'ban_id';
        $structure->columns = [
            'ban_id' => ['type' => self::UINT, 'autoIncrement' => true],
            'user_id' => ['type' => self::UINT, 'default' => 0],
            'user_banBy_id' => ['type' => self::UINT, 'default' => 0],
            'ban_date' => ['type' => self::UINT, 'default' => 0],

            'ban_reason' => ['type' => self::STR, 'default' => null],
        ];

        $structure->relations = [
            'User' => [
                'entity' => 'XF:User',
                'type' => self::TO_ONE,
                'conditions' => 'user_id',
            ],
            'BanByUser' => [
                'entity' => 'XF:User',
                'type' => self::TO_ONE,
                'conditions' => [
                    ['user_id', '=', '$user_banBy_id'],
                ]
            ],
        ];
        $structure->defaultWith = [];
        $structure->getters = [];
        $structure->behaviors = [];

        return $structure;
    }

    public function getbanDate()
    {
        $timezone = \xf::options()->fs_scheduled_ban_user_timezone;
        $tz = new \DateTimeZone($timezone);

        $date = new \DateTime('@' . $this->ban_date, $tz);

        return $date->format("H:i");
    }

    public function getbanTime()
    {
        $timezone = \xf::options()->fs_scheduled_ban_user_timezone;
        $tz = new \DateTimeZone($timezone);

        $date = new \DateTime('@' . $this->ban_date, $tz);

        return $date->format("h:i A");
    }
}
