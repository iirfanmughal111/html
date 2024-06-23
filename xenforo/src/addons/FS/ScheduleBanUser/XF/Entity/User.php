<?php

namespace FS\ScheduleBanUser\XF\Entity;

use XF\Mvc\Entity\Structure;


class User extends XFCP_User
{

    public static function getStructure(Structure $structure)
    {
        $structure = parent::getStructure($structure);

        $structure->relations = [
            'Admin' => [
                'entity' => 'XF:Admin',
                'type' => self::TO_ONE,
                'conditions' => 'user_id',
                'primary' => true
            ],
            'Auth' => [
                'entity' => 'XF:UserAuth',
                'type' => self::TO_ONE,
                'conditions' => 'user_id',
                'primary' => true
            ],
            'ConnectedAccounts' => [
                'entity' => 'XF:UserConnectedAccount',
                'type' => self::TO_MANY,
                'conditions' => 'user_id',
                'key' => 'provider'
            ],
            'Option' => [
                'entity' => 'XF:UserOption',
                'type' => self::TO_ONE,
                'conditions' => 'user_id',
                'primary' => true
            ],
            'PermissionCombination' => [
                'entity' => 'XF:PermissionCombination',
                'type' => self::TO_ONE,
                'conditions' => 'permission_combination_id',
                'proxy' => true,
                'primary' => true
            ],
            'Profile' => [
                'entity' => 'XF:UserProfile',
                'type' => self::TO_ONE,
                'conditions' => 'user_id',
                'primary' => true
            ],
            'Privacy' => [
                'entity' => 'XF:UserPrivacy',
                'type' => self::TO_ONE,
                'conditions' => 'user_id',
                'primary' => true
            ],
            'Ban' => [
                'entity' => 'XF:UserBan',
                'type' => self::TO_ONE,
                'conditions' => 'user_id',
                'primary' => true
            ],
            'Reject' => [
                'entity' => 'XF:UserReject',
                'type' => self::TO_ONE,
                'conditions' => 'user_id',
                'primary' => true
            ],
            'Activity' => [
                'entity' => 'XF:SessionActivity',
                'type' => self::TO_ONE,
                'conditions' => [
                    ['user_id', '=', '$user_id'],
                    ['unique_key', '=', '$user_id', '']
                ],
                'primary' => true
            ],
            'ApprovalQueue' => [
                'entity' => 'XF:ApprovalQueue',
                'type' => self::TO_ONE,
                'conditions' => [
                    ['content_type', '=', 'user'],
                    ['content_id', '=', '$user_id']
                ],
                'primary' => true
            ],
            'Following' => [
                'entity' => 'XF:UserFollow',
                'type' => self::TO_MANY,
                'conditions' => 'user_id',
                'key' => 'follow_user_id'
            ],
            'PendingUsernameChange' => [
                'entity' => 'XF:UsernameChange',
                'type' => self::TO_ONE,
                'conditions' => [
                    ['user_id', '=', '$user_id'],
                    ['change_state', '=', 'moderated']
                ],
                'order' => ['change_date', 'DESC']
            ],
            'PreRegAction' => [
                'entity' => 'XF:PreRegAction',
                'type' => self::TO_ONE,
                'conditions' => 'user_id',
                'primary' => true
            ],
            'ScheduleBan' => [
                'entity' => 'FS\ScheduleBanUser:ScheduleBanUser',
                'type' => self::TO_ONE,
                'conditions' => [
                    ['user_id', '=', '$user_id'],
                ],
            ],

            'ScheduleBanBy' => [
                'entity' => 'FS\ScheduleBanUser:ScheduleBanUser',
                'type' => self::TO_ONE,
                'conditions' => [
                    ['user_banBy_id', '=', '$user_id'],
                ],
            ],
        ];

        return $structure;
    }

    public function canViewFullProfile(&$error = null)
    {
        $response = parent::canViewFullProfile($error);
        $visitor = \XF::visitor();
        if (!$visitor->hasPermission('general', 'viewProfile')) {
            return false;
        }

        if ($this->is_banned && $visitor->hasPermission('general', 'fs_viewBannedProfile')) {
            return true;
        }

        return $response;
    }

    public function getBanDetails()
    {
        $finder = \XF::finder('FS\ScheduleBanUser:ScheduleBanUser')->where('user_id',  $this->user_id)->fetchOne();

        if ($finder) {
            return $finder;
        }

        return null;
    }
}
