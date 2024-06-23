<?php

namespace Truonglv\Groups\Entity;

use function time;
use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property int $resource_id
 * @property int $user_id
 * @property int $download_date
 * @property int $total
 *
 * RELATIONS
 * @property \XF\Entity\User $User
 */
class ResourceDownloadLog extends Entity
{
    public static function getStructure(Structure $structure)
    {
        $structure->table = 'xf_tl_group_resource_download_log';
        $structure->primaryKey = ['resource_id', 'user_id'];
        $structure->shortName = 'Truonglv\Groups:ResourceDownloadLog';

        $structure->columns = [
            'resource_id' => ['type' => self::UINT, 'required' => true],
            'user_id' => ['type' => self::UINT, 'required' => true],
            'download_date' => ['type' => self::UINT, 'default' => time()],
            'total' => ['type' => self::UINT, 'default' => 1]
        ];

        $structure->relations = [
            'User' => [
                'type' => self::TO_ONE,
                'entity' => 'XF:User',
                'conditions' => 'user_id',
                'primary' => true,
            ]
        ];

        return $structure;
    }
}
