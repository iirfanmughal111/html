<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Entity;

use XF;
use function in_array;
use Truonglv\Groups\App;
use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;
use InvalidArgumentException;
use XF\Service\User\UserGroupChange;

/**
 * COLUMNS
 * @property int|null $member_id
 * @property int $user_id
 * @property string $username
 * @property int $group_id
 * @property string $member_state
 * @property string $member_role_id
 * @property int $joined_date
 * @property string $alert
 * @property int $ban_end_date
 *
 * GETTERS
 * @property \XF\Phrase $member_state_title
 * @property null|MemberRole $MemberRole
 * @property bool $is_staff
 *
 * RELATIONS
 * @property \XF\Entity\User $User
 * @property \Truonglv\Groups\Entity\Group $Group
 * @property \Truonglv\Groups\Entity\GroupView $GroupView
 * @property \Truonglv\Groups\Entity\UserCache $UserCache
 */
class Member extends Entity
{
    /**
     * @param mixed $error
     * @return bool
     */
    public function canView(& $error = null)
    {
        /** @var Group|null $group */
        $group = $this->Group;

        return $group !== null;
    }

    /**
     * @param mixed $error
     * @return bool
     */
    public function canUpdateNotify(& $error = null)
    {
        if ($this->user_id !== XF::visitor()->user_id) {
            return false;
        }

        return $this->isValidMember();
    }

    /**
     * @param mixed $error
     * @return bool
     */
    public function canLeave(& $error = null)
    {
        if (XF::visitor()->user_id <= 0) {
            return false;
        }

        // always allow member to leave the group
        return true;
    }

    /**
     * @param mixed $error
     * @return bool
     */
    public function canBeRemove(& $error = null)
    {
        $visitor = XF::visitor();
        if ($visitor->user_id <= 0 || $visitor->user_id == $this->user_id) {
            return false;
        }

        if (App::hasPermission('manageMembers')) {
            return true;
        }

        $member = $this->Group !== null ? $this->Group->Member : null;
        if ($member === null) {
            return false;
        }

        return $member->hasRole(App::MEMBER_ROLE_PERM_KEY_MEMBER, 'remove');
    }

    /**
     * @param mixed $error
     * @return bool
     */
    public function canBePromote(& $error = null)
    {
        $visitor = XF::visitor();
        if ($visitor->user_id <= 0 || $visitor->user_id == $this->user_id) {
            return false;
        }

        if (!$this->isValidMember()) {
            // could not give a promote record on invalid member
            return false;
        }

        if (App::hasPermission('manageMembers')) {
            return true;
        }

        $member = $this->Group !== null ? $this->Group->Member : null;
        if ($member === null) {
            return false;
        }

        return $member->hasRole(App::MEMBER_ROLE_PERM_KEY_MEMBER, 'promote');
    }

    /**
     * @param mixed $error
     * @return bool
     */
    public function canBeBanned(& $error = null)
    {
        $visitor = XF::visitor();
        if ($visitor->user_id <= 0
            || $visitor->user_id == $this->user_id
        ) {
            return false;
        }

        if (App::hasPermission('manageMembers')) {
            return true;
        }

        if ($this->is_staff) {
            return false;
        }

        if (!$this->isValidMember()) {
            // could not give a ban record on invalid member
            return false;
        }

        $member = $this->Group !== null ? $this->Group->Member : null;
        if ($member === null) {
            return false;
        }

        return $member->hasRole(App::MEMBER_ROLE_PERM_KEY_MEMBER, 'ban');
    }

    /**
     * @param mixed $error
     * @return bool
     */
    public function canBeApproved(& $error = null)
    {
        if (XF::visitor()->user_id <= 0) {
            return false;
        }

        if (!$this->isModeratedMember()) {
            return false;
        }

        if (App::hasPermission('manageMembers')) {
            return true;
        }

        $member = $this->Group !== null ? $this->Group->Member : null;
        if ($member === null) {
            return false;
        }

        return $member->hasRole(App::MEMBER_ROLE_PERM_KEY_MEMBER, 'approve');
    }

    /**
     * @param mixed $error
     * @return bool
     */
    public function canLiftBan(& $error = null)
    {
        $visitor = XF::visitor();
        if ($visitor->user_id <= 0
            || $visitor->user_id == $this->user_id
        ) {
            return false;
        }

        if ($this->member_state !== App::MEMBER_STATE_BANNED) {
            return false;
        }

        if (App::hasPermission('manageMembers')) {
            return true;
        }

        $member = $this->Group !== null ? $this->Group->Member : null;
        if ($member === null) {
            return false;
        }

        return $member->hasRole(App::MEMBER_ROLE_PERM_KEY_MEMBER, 'ban');
    }

    /**
     * @return bool
     */
    public function canInlineMod()
    {
        return ($this->canBeRemove() || $this->canBeApproved() || $this->canBeBanned() || $this->canBePromote());
    }

    /**
     * @return bool
     */
    public function isOwner()
    {
        if (XF::visitor()->user_id <= 0) {
            return false;
        }

        /** @var Group|null $group */
        $group = $this->Group;
        if ($group === null) {
            return false;
        }

        return $this->user_id === $group->owner_user_id;
    }

    /**
     * @return bool
     */
    public function isBanned()
    {
        if ($this->member_state !== App::MEMBER_STATE_BANNED) {
            return false;
        }

        if ($this->ban_end_date > 0 && $this->ban_end_date <= XF::$time) {
            return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    public function isIgnored()
    {
        return XF::visitor()->isIgnoring($this->user_id);
    }

    /**
     * @return bool
     */
    public function isValidMember()
    {
        return in_array($this->member_state, App::memberRepo()->getValidMemberStates(), true);
    }

    /**
     * @return bool
     */
    public function isInvited()
    {
        return $this->member_state === App::MEMBER_STATE_INVITED;
    }

    /**
     * @return bool
     */
    public function isModeratedMember()
    {
        return $this->member_state === App::MEMBER_STATE_MODERATED;
    }

    /**
     * @param string $group
     * @param string $role
     * @return bool
     */
    public function hasRole($group, $role)
    {
        $memberRole = $this->MemberRole;
        if ($memberRole === null) {
            return false;
        }

        if ($this->isBanned()) {
            return false;
        }

        return $memberRole->hasRole($group, $role);
    }

    /**
     * @param string $type
     * @return bool
     */
    public function isReceiveAlertType($type)
    {
        if ($this->alert === App::MEMBER_ALERT_OPT_ALL) {
            return true;
        }

        return $this->alert === $type;
    }

    /**
     * @return null|MemberRole
     */
    public function getMemberRole()
    {
        if ($this->member_role_id === '') {
            return null;
        }

        $memberRoles = App::memberRoleRepo()->getAllMemberRoles();
        if (!isset($memberRoles[$this->member_role_id])) {
            return null;
        }

        /** @var MemberRole $memberRole */
        $memberRole = $memberRoles[$this->member_role_id];

        return $memberRole;
    }

    /**
     * @return \XF\Phrase
     */
    public function getMemberStateTitle()
    {
        $phraseId = 'tlg_member_state_' . $this->member_state;

        // @phpstan-ignore-next-line
        return XF::phraseDeferred($phraseId);
    }

    /**
     * @return bool
     */
    public function isAdmin()
    {
        /** @var MemberRole $adminRole */
        $adminRole = App::memberRoleRepo()->getAllMemberRoles()[App::MEMBER_ROLE_ID_ADMIN];

        return $this->member_role_id === App::MEMBER_ROLE_ID_ADMIN && $adminRole->is_staff;
    }

    /**
     * @return bool
     */
    public function isModerator()
    {
        /** @var MemberRole $moderatorRole */
        $moderatorRole = App::memberRoleRepo()->getAllMemberRoles()[App::MEMBER_ROLE_ID_MODERATOR];

        return $this->member_role_id === App::MEMBER_ROLE_ID_MODERATOR && $moderatorRole->is_staff;
    }

    /**
     * @return bool
     */
    public function isStaff()
    {
        if ($this->MemberRole === null) {
            return false;
        }

        return ($this->isAdmin() || $this->isModerator() || $this->isOwner() || $this->MemberRole->is_staff);
    }

    /**
     * @return array
     */
    public function getDataForCache()
    {
        return [
            'member_id' => $this->member_id,
            'user_id' => $this->user_id,
            'username' => $this->username,
            'member_state' => $this->member_state,
            'member_role_id' => $this->member_role_id
        ];
    }

    /**
     * @param \XF\Api\Result\EntityResult $result
     * @param int $verbosity
     * @param array $options
     * @return void
     */
    protected function setupApiResultData(
        \XF\Api\Result\EntityResult $result,
        $verbosity = self::VERBOSITY_NORMAL,
        array $options = []
    ) {
        $result->includeRelation('User');

        $result->is_ignored = $this->isIgnored();

        $result->can_leave = $this->canLeave();
        $result->can_be_banned = $this->canBeBanned();
        $result->can_be_remove = $this->canBeRemove();
    }

    public static function getStructure(Structure $structure)
    {
        $structure->table = 'xf_tl_group_member';

        $structure->primaryKey = 'member_id';
        $structure->shortName = 'Truonglv\Groups:Member';
        $structure->contentType = App::CONTENT_TYPE_MEMBER;

        $structure->columns = [
            'member_id' => ['type' => self::UINT, 'nullable' => true, 'autoIncrement' => true, 'api' => false],
            'user_id' => ['type' => self::UINT, 'required' => true, 'api' => true],
            'username' => ['type' => self::STR, 'maxLength' => 50, 'required' => true, 'api' => true],
            'group_id' => ['type' => self::UINT, 'required' => true, 'api' => true],
            'member_state' => [
                'type' => self::STR,
                'allowedValues' => App::memberRepo()->getAllowedMemberStates(),
                'required' => true,
                'api' => true
            ],
            'member_role_id' => ['type' => self::STR, 'maxLength' => 50, 'default' => '', 'api' => true],
            'joined_date' => ['type' => self::UINT, 'default' => time(), 'api' => true],
            'alert' => [
                'type' => self::STR,
                'allowedValues' => App::getAllowedAlertOptions(),
                'default' => App::MEMBER_ALERT_OPT_ALL,
                'api' => true
            ],

            'ban_end_date' => ['type' => self::UINT, 'default' => 0, 'api' => true]
        ];

        $structure->getters = [
            'member_state_title' => true,
            'MemberRole' => true,
            'is_staff' => [
                'cache' => true,
                'getter' => 'isStaff'
            ]
        ];

        $structure->behaviors = [];

        $structure->relations = [
            'User' => [
                'type' => self::TO_ONE,
                'entity' => 'XF:User',
                'conditions' => 'user_id',
                'primary' => true
            ],
            'Group' => [
                'type' => self::TO_ONE,
                'entity' => 'Truonglv\Groups:Group',
                'conditions' => 'group_id',
                'primary' => true
            ],
            'GroupView' => [
                'type' => self::TO_ONE,
                'entity' => 'Truonglv\Groups:GroupView',
                'conditions' => [
                    ['group_id', '=', '$group_id'],
                    ['user_id', '=', '$user_id']
                ],
                'primary' => true
            ],
            'UserCache' => [
                'type' => self::TO_ONE,
                'entity' => 'Truonglv\Groups:UserCache',
                'conditions' => 'user_id',
                'primary' => true,
            ]
        ];

        $structure->withAliases = [
            'full' => [
                'User',
                'User.Profile'
            ],
            'api' => [
                'User',
                'User.api',
                'User.Profile'
            ]
        ];

        $structure->defaultWith = ['Group', 'GroupView'];

        return $structure;
    }

    protected function _preSave()
    {
        if ($this->isChanged('member_state')) {
            if ($this->member_role_id === '' && $this->member_state === App::MEMBER_STATE_VALID) {
                throw new InvalidArgumentException('A valid member must have a role!');
            }
        }

        if ($this->isChanged('member_role_id')) {
            unset($this->_getterCache['is_staff']);
        }
    }

    protected function _postSave()
    {
        $this->updateGroupRecord();

        if ($this->isChanged(['member_state', 'member_role_id'])) {
            /** @var UserCache $userCache */
            $userCache = $this->getRelationOrDefault('UserCache', false);
            $userCache->onMemberStateChanged($this);
        }

        if ($this->isChanged('member_role_id')) {
            $this->applyUserGroupChange();
        }
    }

    protected function applyUserGroupChange(): void
    {
        /** @var UserGroupChange $userGroupChange */
        $userGroupChange = $this->app()->service('XF:User\UserGroupChange');

        /** @var MemberRole|null $memberRole */
        $memberRole = $this->MemberRole;
        if ($memberRole !== null && count($memberRole->user_group_ids) > 0) {
            $userGroupChange->addUserGroupChange(
                $this->user_id,
                'tlg_member_' . $this->group_id,
                $memberRole->user_group_ids
            );
        }

        /** @var MemberRole|null $oldMemberRole */
        $oldMemberRole = $this->em()->find('Truonglv\Groups:MemberRole', $this->getExistingValue('member_role_id'));
        if ($oldMemberRole !== null && count($oldMemberRole->user_group_ids) > 0) {
            $userGroupChange->removeUserGroupChange($this->user_id, 'tlg_member_' . $this->group_id);
        }
    }

    /**
     * @param bool $hardDelete
     * @return void
     */
    protected function updateGroupRecord($hardDelete = false)
    {
        /** @var Group|null $group */
        $group = $this->Group;
        if ($group === null || !$group->exists()) {
            return;
        }

        if ($hardDelete) {
            if ($this->member_state === App::MEMBER_STATE_VALID) {
                $group->member_count--;
            } elseif ($this->member_state === App::MEMBER_STATE_MODERATED) {
                $group->member_moderated_count--;
            }
            $group->rebuildMemberCache();
            $group->saveIfChanged();

            return;
        }

        $validState = $this->isStateChanged('member_state', App::MEMBER_STATE_VALID);
        $invitedState = $this->isStateChanged('member_state', App::MEMBER_STATE_INVITED);
        $moderatedState = $this->isStateChanged('member_state', App::MEMBER_STATE_MODERATED);

        if ($validState === 'enter' || $invitedState === 'enter') {
            if ($invitedState === 'leave') {
                // it's already counted
            } else {
                $group->onMemberJoined($this);
            }
        } elseif ($validState === 'leave'
            || $invitedState === 'leave'
        ) {
            $group->onMemberLeaved($this);
        }

        if ($moderatedState === 'enter') {
            $group->member_moderated_count++;
        } elseif ($moderatedState === 'leave') {
            $group->member_moderated_count--;
        }

        $group->rebuildMemberCache();
        $group->saveIfChanged();
    }

    protected function _postDelete()
    {
        $this->updateGroupRecord(true);

        /** @var \XF\Repository\UserAlert $userAlertRepo */
        $userAlertRepo = $this->repository('XF:UserAlert');
        $userAlertRepo->fastDeleteAlertsForContent(App::CONTENT_TYPE_MEMBER, $this->member_id);

        /** @var UserCache|null $userCache */
        $userCache = $this->UserCache;
        /** @var Group|null $group */
        $group = $this->Group;
        if ($userCache !== null && $group !== null) {
            $userCache->onLeaveGroup($group);
        }

        if ($this->MemberRole !== null && count($this->MemberRole->user_group_ids) > 0) {
            /** @var UserGroupChange $userGroupChange */
            $userGroupChange = $this->app()->service('XF:User\UserGroupChange');
            $userGroupChange->removeUserGroupChange($this->user_id, 'tlg_member_' . $this->group_id);
        }

        $db = $this->db();
        $db->update(
            'xf_user',
            [
                'tlg_badge_group_id' => 0,
            ],
            'user_id = ? AND tlg_badge_group_id = ?',
            [$this->user_id, $this->group_id]
        );
    }
}
