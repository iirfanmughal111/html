<?php

namespace Truonglv\Groups\Entity;

use Truonglv\Groups\App;
use function array_merge;
use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;
use InvalidArgumentException;
use function array_key_exists;

/**
 * COLUMNS
 * @property int $user_id
 * @property array $cache_data
 */
class UserCache extends Entity
{
    const KEY_MEMBER_STATE = '_m_s';
    const KEY_GROUP_PRIVACY = '_g_p';
    const KEY_GROUP_STATE = '_g_s';
    const KEY_MEMBER_ROLE_ID = '_m_r_i';

    const GUEST_USER_ID = 0;

    /**
     * @param int $groupId
     * @param mixed $error
     * @return bool
     */
    public function canViewGroupContent($groupId, & $error = null)
    {
        $member = $this->cache_data[$groupId] ?? null;
        if ($member === null) {
            if ($this->user_id === self::GUEST_USER_ID) {
                return false;
            }

            // user did not join group.
            /** @var UserCache|null $guestUser */
            $guestUser = $this->em()->find('Truonglv\Groups:UserCache', self::GUEST_USER_ID);
            if ($guestUser === null) {
                // prevent error when user caches not rebuilt
                // need to rebuild group user caches to fix this issue.
                return false;
            }

            return $guestUser->canViewGroupContent($groupId, $error);
        }

        $ourKeys = [
            self::KEY_MEMBER_STATE,
            self::KEY_GROUP_PRIVACY,
            self::KEY_GROUP_STATE,
        ];
        foreach ($ourKeys as $ourKey) {
            if (!array_key_exists($ourKey, $member)) {
                return false;
            }
        }

        if (!isset($member[self::KEY_GROUP_STATE]) || $member[self::KEY_GROUP_STATE] !== App::STATE_VISIBLE) {
            // group is not visible.
            return false;
        }

        if (App::hasPermission('bypassViewPrivacy')) {
            return true;
        }

        if ($member[self::KEY_GROUP_PRIVACY] !== App::PRIVACY_PUBLIC) {
            // it's required a member of group can view inside content.
            return $member[self::KEY_MEMBER_STATE] === App::MEMBER_STATE_VALID;
        }

        // public group... viewing without any restrict.
        return true;
    }

    /**
     * @param Group $group
     * @throws \XF\PrintableException
     * @return void
     */
    public function onLeaveGroup(Group $group)
    {
        $cached = $this->cache_data;
        if (isset($cached[$group->group_id])) {
            unset($cached[$group->group_id]);

            $this->cache_data = $cached;
            $this->save();
        }
    }

    /**
     * @param Member $member
     * @throws \XF\PrintableException
     * @return void
     */
    public function onMemberStateChanged(Member $member)
    {
        if ($member->Group === null) {
            throw new InvalidArgumentException('Member not belong to any group!');
        }

        $cached = $this->cache_data;
        $cached[$member->group_id] = static::getUserCacheFromData(array_merge(
            $member->toArray(false),
            $member->Group->toArray(false)
        ));

        $this->cache_data = $cached;
        $this->save();
    }

    /**
     * @param mixed $data
     * @return array
     */
    public static function getUserCacheFromData($data)
    {
        return [
            self::KEY_MEMBER_STATE => $data['member_state'] ?? '',
            self::KEY_GROUP_PRIVACY => $data['privacy'],
            self::KEY_GROUP_STATE => $data['group_state'],
            self::KEY_MEMBER_ROLE_ID => $data['member_role_id'] ?? '',
        ];
    }

    public static function getStructure(Structure $structure)
    {
        $structure->table = 'xf_tl_group_user_cache';
        $structure->primaryKey = 'user_id';
        $structure->shortName = 'Truonglv\Groups:UserCache';

        $structure->columns = [
            'user_id' => ['type' => self::UINT, 'required' => true],
            'cache_data' => ['type' => self::JSON_ARRAY, 'default' => []],
        ];

        return $structure;
    }
}
