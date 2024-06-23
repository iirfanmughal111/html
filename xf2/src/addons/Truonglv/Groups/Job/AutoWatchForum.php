<?php

namespace Truonglv\Groups\Job;

use XF\Timer;
use XF\Job\AbstractJob;
use Truonglv\Groups\App;
use XF\Repository\ForumWatch;
use Truonglv\Groups\Entity\Forum;
use Truonglv\Groups\Entity\Group;
use Truonglv\Groups\Entity\Member;

class AutoWatchForum extends AbstractJob
{
    /**
     * @var array
     */
    protected $defaultData = [
        'groupId' => 0,
        'forumId' => 0,
        'memberId' => 0,
    ];

    /**
     * @inheritDoc
     */
    public function run($maxRunTime)
    {
        /** @var Group|null $group */
        $group = $this->app->em()->find('Truonglv\Groups:Group', $this->data['groupId']);
        if ($group === null) {
            return $this->complete();
        }

        /** @var Forum|null $groupForum */
        $groupForum = $this->app->finder('Truonglv\Groups:Forum')
            ->where('group_id', $group->group_id)
            ->where('node_id', $this->data['forumId'])
            ->fetchOne();
        if ($groupForum === null || $groupForum->Forum === null) {
            return $this->complete();
        }

        $timer = $maxRunTime > 0 ? new Timer($maxRunTime) : null;
        $members = $this->app->finder('Truonglv\Groups:Member')
            ->where('group_id', $group->group_id)
            ->where('member_id', '>', $this->data['memberId'])
            ->limit(50)
            ->order('member_id')
            ->fetch();
        if ($members->count() === 0) {
            return $this->complete();
        }

        /** @var ForumWatch $forumWatchRepo */
        $forumWatchRepo = $this->app->repository('XF:ForumWatch');

        /** @var Member $member */
        foreach ($members as $member) {
            $this->data['memberId'] = $member->member_id;
            if ($member->User === null) {
                continue;
            }

            /** @var \XF\Entity\ForumWatch|null $isWatched */
            $isWatched = $groupForum->Forum->Watch[$member->user_id];
            if ($isWatched !== null) {
                continue;
            }

            $forumWatchRepo->setWatchState(
                $groupForum->Forum,
                $member->User,
                'thread',
                true,
                $member->isReceiveAlertType(App::MEMBER_ALERT_OPT_EMAIL_ONLY)
            );

            if ($timer !== null && $timer->limitExceeded()) {
                break;
            }
        }

        return $this->resume();
    }

    /**
     * @return string
     */
    public function getStatusMessage()
    {
        return '';
    }

    /**
     * @return bool
     */
    public function canCancel()
    {
        return false;
    }

    /**
     * @return bool
     */
    public function canTriggerByChoice()
    {
        return false;
    }
}
