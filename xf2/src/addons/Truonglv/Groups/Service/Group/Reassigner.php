<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Service\Group;

use XF;
use LogicException;
use XF\Entity\User;
use Truonglv\Groups\App;
use XF\PrintableException;
use XF\Service\AbstractService;
use Truonglv\Groups\Entity\Group;
use Truonglv\Groups\Entity\Member;
use XF\Service\ValidateAndSavableTrait;

class Reassigner extends AbstractService
{
    use ValidateAndSavableTrait;

    /**
     * @var Group
     */
    protected $group;
    /**
     * @var User|null
     */
    protected $newOwner;

    /**
     * @var \Truonglv\Groups\Entity\Member
     */
    protected $newMember;

    public function __construct(\XF\App $app, Group $group)
    {
        parent::__construct($app);

        if (!$group->exists()) {
            throw new LogicException('Group must be exists.');
        }
        $this->group = $group;
    }

    /**
     * @param string $nameOrEmail
     * @return Reassigner
     * @throws PrintableException
     */
    public function setNewOwnerName(string $nameOrEmail)
    {
        /** @var \XF\Repository\User $userRepo */
        $userRepo = $this->repository('XF:User');
        /** @var \XF\Entity\User|null $user */
        $user = $userRepo->getUserByNameOrEmail($nameOrEmail);

        if ($user === null) {
            throw new PrintableException(XF::phrase('requested_member_not_found'));
        }

        return $this->setNewOwner($user);
    }

    /**
     * @param User $user
     * @return $this
     */
    public function setNewOwner(User $user)
    {
        if (!$user->exists()) {
            throw new LogicException('User must be exists.');
        }

        $this->newOwner = $user;

        return $this;
    }

    /**
     * @return array
     */
    protected function _validate()
    {
        $errors = [];

        if ($this->newOwner === null) {
            throw new LogicException('Must be set newOwner.');
        }

        if ($this->newOwner->is_banned) {
            $errors[] = XF::phrase('tlg_user_x_has_been_banned_and_not_allowed_join_any', [
                'user' => $this->newOwner->username
            ]);
        }

        if ($this->newOwner->user_state !== 'valid') {
            $errors[] = XF::phrase('tlg_user_x_invalid_and_not_join_any', [
                'user' => $this->newOwner->username
            ]);
        }

        return $errors;
    }

    /**
     * @return Group
     * @throws PrintableException
     */
    protected function _save()
    {
        $db = $this->db();
        $db->beginTransaction();

        /** @var User $newOwner */
        $newOwner = $this->newOwner;
        $group = $this->group;

        /** @var Member|null $member */
        $member = App::memberFinder()
            ->where('user_id', $newOwner->user_id)
            ->where('group_id', $group->group_id)
            ->fetchOne();

        if ($member === null) {
            /** @var Member $member */
            $member = $this->em()->create('Truonglv\Groups:Member');

            $member->group_id = $group->group_id;
            $member->alert = App::MEMBER_ALERT_OPT_ALL;
            $member->user_id = $newOwner->user_id;
        }

        // to sync latest username
        $member->username = $newOwner->username;

        $member->member_role_id = App::MEMBER_ROLE_ID_ADMIN;
        $member->member_state = App::MEMBER_STATE_VALID;

        $group->owner_user_id = $newOwner->user_id;
        $group->owner_username = $newOwner->username;
        $member->addCascadedSave($group);

        $member->save(true, false);

        $this->newMember = $member;
        $this->sendNotifications();

        $db->commit();

        return $group;
    }

    /**
     * @return Group
     */
    public function assign()
    {
        return $this->_save();
    }

    /**
     * @return void
     */
    protected function sendNotifications()
    {
        $member = $this->newMember;
        $visitor = XF::visitor();

        /** @var \XF\Repository\UserAlert $userAlertRepo */
        $userAlertRepo = $this->repository('XF:UserAlert');
        if ($member->User !== null) {
            $userAlertRepo->alert(
                $member->User,
                $visitor->user_id,
                $visitor->username,
                App::CONTENT_TYPE_GROUP,
                $member->group_id,
                'assigned_owner'
            );
        }
    }
}
