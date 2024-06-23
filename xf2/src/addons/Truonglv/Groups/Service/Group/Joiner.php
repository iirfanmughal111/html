<?php

namespace Truonglv\Groups\Service\Group;

use XF;
use Throwable;
use XF\Entity\User;
use Truonglv\Groups\App;
use XF\Repository\ForumWatch;
use XF\Service\AbstractService;
use Truonglv\Groups\Entity\Forum;
use Truonglv\Groups\Entity\Group;
use Truonglv\Groups\Entity\Member;
use XF\Service\ValidateAndSavableTrait;

class Joiner extends AbstractService
{
    use ValidateAndSavableTrait;

    /**
     * @var Group
     */
    protected $group;
    /**
     * @var User
     */
    protected $user;
    /**
     * @var Member
     */
    protected $member;
    /**
     * @var bool
     */
    protected $checkJoinLimit = true;
    /**
     * @var bool
     */
    protected $autoWatchForums = true;

    public function __construct(\XF\App $app, Group $group, User $user)
    {
        parent::__construct($app);

        $this->group = $group;
        $this->user = $user;

        $this->autoWatchForums = App::getOption('autoWatchForums') > 0;

        $this->setupDefaults();
    }

    public function setMemberState(string $state): self
    {
        $this->member->member_state = $state;

        return $this;
    }

    public function setAlertState(string $state): self
    {
        $this->member->alert = $state;

        return $this;
    }

    public function setMemberRoleId(string $memberRoleId): self
    {
        $this->member->member_role_id = $memberRoleId;

        return $this;
    }

    public function setCheckJoinLimit(bool $flag): self
    {
        $this->checkJoinLimit = $flag;

        return $this;
    }

    public function setAutoWatchForums(bool $autoWatchForums): self
    {
        $this->autoWatchForums = $autoWatchForums;

        return $this;
    }

    protected function setupDefaults(): void
    {
        /** @var Member $member */
        $member = $this->app->em()->create('Truonglv\Groups:Member');

        $member->group_id = $this->group->group_id;
        $member->user_id = $this->user->user_id;
        $member->username = $this->user->username;

        if ($this->group->always_moderate_join) {
            $member->member_role_id = '';
            $member->member_state = App::MEMBER_STATE_MODERATED;
        } else {
            $member->member_state = App::MEMBER_STATE_VALID;
            $member->member_role_id = App::MEMBER_ROLE_ID_MEMBER;
        }

        $member->alert = App::MEMBER_ALERT_OPT_ALL;

        $member->hydrateRelation('Group', $this->group);
        $member->hydrateRelation('User', $this->user);

        $this->member = $member;
    }

    protected function finalizeSetup(): void
    {
        $this->member->joined_date = time();
    }

    /**
     * @return array
     */
    protected function _validate()
    {
        $this->finalizeSetup();

        $errors = $this->member->getErrors();

        /** @var Member|null $dupe */
        $dupe = $this->finder('Truonglv\Groups:Member')
            ->where('group_id', $this->group->group_id)
            ->where('user_id', $this->user->user_id)
            ->fetchOne();
        $isVisitor = XF::visitor()->user_id === $this->user->user_id;
        if ($dupe !== null) {
            $errors[] = $isVisitor
                ? XF::phrase('tlg_you_already_in_this_group')
                : XF::phrase('tlg_user_x_already_in_this_group', [
                    'name' => $this->user->username,
                ]);
        }

        $limit = (int) $this->user->hasPermission(App::PERMISSION_GROUP, 'maxJoinGroups');
        if ($this->checkJoinLimit && $limit >= 0) {
            $total = $this->finder('Truonglv\Groups:Group')
                ->exists('Members|' . $this->user->user_id)
                ->where('owner_user_id', '<>', $this->user->user_id)
                // exclude to count deleted groups
                ->where('group_state', '<>', App::STATE_DELETED)
                ->total();
            if ($total >= $limit) {
                $errors[] = $isVisitor
                    ? XF::phrase('tlg_you_reached_maximum_groups_which_allowed_to_join')
                    : XF::phrase('tlg_user_x_reached_maximum_groups_which_allowed_to_join', [
                        'name' => $this->user->username,
                    ]);
            }
        }

        return $errors;
    }

    /**
     * @return Member
     */
    protected function _save()
    {
        $db = $this->db();
        $member = $this->member;

        $newValues = $member->getNewValues();

        $db->beginTransaction();
        /** @var Member|null $dupe */
        $dupe = null;
        $newMember = false;

        try {
            $member->save(true, false);
            $newMember = true;
        } catch (Throwable $e) {
            /** @var Member|null $dupe */
            $dupe = $this->finder('Truonglv\Groups:Member')
                ->where('group_id', $this->group->group_id)
                ->where('user_id', $this->user->user_id)
                ->fetchOne();
            if ($dupe === null) {
                $db->rollback();

                throw $e;
            }
        }

        if ($dupe !== null) {
            unset($newValues['group_id'], $newValues['user_id']);
            $dupe->fastUpdate($newValues);

            $member = $dupe;
            $this->member = $dupe;
        }

        if ($newMember && $this->autoWatchForums) {
            $this->autoWatchGroupForumsInternal();
        }

        $db->commit();

        return $member;
    }

    protected function autoWatchGroupForumsInternal(): void
    {
        $forums = $this->finder('Truonglv\Groups:Forum')
            ->with('Forum')
            ->where('group_id', $this->group->group_id)
            ->fetch();
        /** @var ForumWatch $forumWatchRepo */
        $forumWatchRepo = $this->repository('XF:ForumWatch');
        /** @var Forum $forum */
        foreach ($forums as $forum) {
            if ($forum->Forum === null) {
                continue;
            }

            /** @var \XF\Entity\ForumWatch|null $isWatched */
            $isWatched = $forum->Forum->Watch[$this->user->user_id];
            if ($isWatched !== null) {
                continue;
            }

            $forumWatchRepo->setWatchState(
                $forum->Forum,
                $this->user,
                'thread',
                true,
                $this->member->isReceiveAlertType(App::MEMBER_ALERT_OPT_EMAIL_ONLY)
            );
        }
    }

    public function sendNotifications(): void
    {
        if (!$this->member->isModeratedMember()) {
            return;
        }

        /** @var \Truonglv\Groups\Service\Group\Notifier $notifier */
        $notifier = $this->service('Truonglv\Groups:Group\Notifier', $this->group, 'request_join');
        $notifier->setActionMember($this->member);
        $notifier->notifyAndEnqueue(3);
    }
}
