<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\XF\Entity;

use XF;
use Truonglv\Groups\App;
use XF\Mvc\Entity\Structure;
use Truonglv\Groups\Entity\Group;

/**
 * @inheritDoc
 * @property int $tlg_total_own_groups
 * @property int $tlg_badge_group_id
 */
class User extends XFCP_User
{
    /**
     * @param mixed $error
     * @return bool
     */
    public function canAddGroup(& $error = null)
    {
        /** @var mixed $max */
        $max = (int) App::hasPermission('maxCreateGroups');
        if ($max === -1) {
            return true;
        }

        return $this->get('tlg_total_own_groups') < $max;
    }

    /**
     * @param mixed $error
     * @return bool
     */
    public function canTLGViewGroups(& $error = null)
    {
        if (!App::hasPermission('view')) {
            return false;
        }

        $visitor = XF::visitor();
        if ($this->user_id === $visitor->user_id) {
            return true;
        }

        return $this->isPrivacyCheckMet('tlg_allow_view_groups', $visitor);
    }

    /**
     * @param \XF\Api\Result\EntityResult $result
     * @param int $verbosity
     * @param array $options
     * @return void
     */
    protected function setupApiResultData(
        \XF\Api\Result\EntityResult $result,
        $verbosity = \XF\Entity\User::VERBOSITY_NORMAL,
        array $options = []
    ) {
        parent::setupApiResultData($result, $verbosity, $options);

        $result->sg_can_add_group = $this->canAddGroup();
    }

    /**
     * @return bool
     */
    public function canCreateAlbum()
    {
        if (App::$createAlbumInGroup instanceof Group
            && App::$createAlbumInGroup->canAddAlbums()
        ) {
            return true;
        }

        /** @var mixed $parentFn */
        $parentFn = 'parent::canCreateAlbum';

        return call_user_func($parentFn);
    }

    /**
     * @return void
     */
    public function rebuildWarningPoints()
    {
        parent::rebuildWarningPoints();

        $this->fastUpdate(
            'tlg_total_own_groups',
            $this->finder('Truonglv\Groups:Group')
                ->where('owner_user_id', $this->user_id)
                ->total()
        );
    }

    public static function getStructure(Structure $structure)
    {
        $structure = parent::getStructure($structure);

        $structure->columns += [
            'tlg_total_own_groups' => ['type' => self::UINT, 'default' => 0, 'forced' => true],
            'tlg_badge_group_id' => ['type' => self::UINT, 'default' => 0]
        ];

        $structure->relations += [
            'TLGUserCache' => [
                'type' => self::TO_ONE,
                'entity' => 'Truonglv\Groups:UserCache',
                'conditions' => 'user_id',
                'primary' => true
            ],
            'TLGDisplayGroup' => [
                'type' => self::TO_ONE,
                'entity' => 'Truonglv\Groups:Group',
                'conditions' => [
                    ['group_id', '=', '$tlg_badge_group_id']
                ],
                'primary' => true,
            ]
        ];

        return $structure;
    }

    protected function _postSave()
    {
        parent::_postSave();

        if ($this->isInsert()) {
            try {
                $this->db()->insert('xf_tl_group_user_cache', [
                    'user_id' => $this->user_id,
                    'cache_data' => json_encode([]),
                ]);
            } catch (\XF\Db\DuplicateKeyException $e) {
            }
        }
    }
}
