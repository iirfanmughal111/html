<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Service\Group;

use XF;
use function trim;
use LogicException;
use XF\Entity\User;
use Truonglv\Groups\App;
use XF\PrintableException;
use InvalidArgumentException;
use XF\Service\AbstractService;
use Truonglv\Groups\Entity\Group;
use Truonglv\Groups\Entity\Member;
use XF\Service\ValidateAndSavableTrait;

class Inviter extends AbstractService
{
    use ValidateAndSavableTrait;

    /**
     * @var Group
     */
    protected $group;
    /**
     * @var User
     */
    protected $toUser;

    /**
     * @var User
     */
    protected $fromUser;
    /**
     * @var bool
     */
    protected $sendNotify = true;
    /**
     * @var bool
     */
    protected $logAction = true;

    public function __construct(\XF\App $app, Group $group)
    {
        parent::__construct($app);

        if (!$group->exists()) {
            throw new LogicException('Group must be exists.');
        }
        $this->group = $group;
        $this->fromUser(XF::visitor());
    }

    /**
     * @param User $user
     * @return $this
     */
    public function fromUser(User $user)
    {
        if (!$user->exists()) {
            throw new LogicException('User must be exists.');
        }

        $this->fromUser = $user;

        return $this;
    }

    /**
     * @param User $user
     * @return $this
     */
    public function toUser(User $user)
    {
        if (!$user->exists()) {
            throw new LogicException('User must be exists.');
        }

        $this->toUser = $user;

        return $this;
    }

    /**
     * @param bool $sendNotify
     * @return void
     */
    public function setSendNotify(bool $sendNotify)
    {
        $this->sendNotify = $sendNotify;
    }

    /**
     * @param bool $logAction
     * @return void
     */
    public function setLogAction(bool $logAction)
    {
        $this->logAction = $logAction;
    }

    /**
     * @param string $nameOrEmail
     * @return Inviter
     * @throws PrintableException
     */
    public function toUsernameOrEmail(string $nameOrEmail)
    {
        if (trim($nameOrEmail) === '') {
            throw new InvalidArgumentException('Arguments could not be empty.');
        }

        /** @var \XF\Repository\User $userRepo */
        $userRepo = $this->repository('XF:User');
        /** @var \XF\Entity\User|null $user */
        $user = $userRepo->getUserByNameOrEmail($nameOrEmail);

        if ($user === null) {
            throw new PrintableException(XF::phrase('requested_member_not_found'));
        }

        return $this->toUser($user);
    }

    /**
     * @return \Truonglv\Groups\Entity\Member
     */
    public function sendInvitation()
    {
        return $this->_save();
    }

    /**
     * @return Member
     * @throws PrintableException
     */
    protected function _save()
    {
        $db = $this->db();
        $db->beginTransaction();

        /** @var \Truonglv\Groups\Entity\Member $member */
        $member = $this->em()->create('Truonglv\Groups:Member');

        $member->user_id = $this->toUser->user_id;
        $member->username = $this->toUser->username;
        $member->member_role_id = App::MEMBER_ROLE_ID_MEMBER;
        $member->group_id = $this->group->group_id;
        $member->member_state = App::MEMBER_STATE_INVITED;

        $this->setupMember($member);
        $invited = false;

        try {
            $member->save(true, false);
            $invited = true;
        } catch (\XF\Db\DuplicateKeyException $e) {
        }

        if ($this->sendNotify && $invited) {
            $this->sendNotifications($member);
        }
        if ($this->logAction && $invited) {
            App::logAction(
                $this->group,
                'member',
                $member->user_id,
                'invite'
            );
        }

        $db->commit();

        return $member;
    }

    /**
     * @param Member $member
     * @return void
     */
    protected function setupMember(Member $member)
    {
    }

    /**
     * @param Member $member
     * @return void
     */
    protected function sendNotifications(\Truonglv\Groups\Entity\Member $member)
    {
        // send an notification to group admins?

        /** @var \XF\Repository\UserAlert $userAlertRepo */
        $userAlertRepo = $this->repository('XF:UserAlert');
        $userAlertRepo->alert(
            $this->toUser,
            $this->fromUser->user_id,
            $this->fromUser->username,
            App::CONTENT_TYPE_GROUP,
            $this->group->group_id,
            'invited'
        );

        // TODO: send an email?
    }

    /**
     * @throws \XF\PrintableException
     * @return array
     */
    protected function _validate()
    {
        $errors = [];
        /** @var \XF\Entity\User|null */
        $toUser = $this->toUser;
        if ($toUser === null) {
            throw new LogicException('User must be set.');
        }

        if ($toUser->is_banned) {
            // cannot invite banned user
            $errors[] = XF::phrase('tlg_user_x_has_been_banned_and_not_allowed_join_any', [
                'user' => $toUser->username
            ]);
        }

        if ($toUser->user_state !== 'valid') {
            $errors[] = XF::phrase('tlg_user_x_invalid_and_not_join_any', [
                'user' => $toUser->username
            ]);
        }

        if ($toUser->user_id === $this->fromUser->user_id) {
            $errors[] = XF::phrase('tlg_you_cannot_invite_yourself_join_this_group');
        }

        $memberFinder = App::memberFinder();
        /** @var Member|null $member */
        $member = $memberFinder->whereKeys($toUser, $this->group)->fetchOne();

        if ($member !== null) {
            $errors[] = XF::phrase('tlg_user_already_in_the_group');
        }

        return $errors;
    }
}
