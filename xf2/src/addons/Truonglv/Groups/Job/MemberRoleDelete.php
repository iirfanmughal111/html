<?php

namespace Truonglv\Groups\Job;

use XF\Timer;
use XF\Job\JobResult;
use XF\Job\AbstractJob;
use Truonglv\Groups\App;
use Truonglv\Groups\Entity\Member;

class MemberRoleDelete extends AbstractJob
{
    /**
     * @var array
     */
    protected $defaultData = [
        'memberRoleId' => ''
    ];

    /**
     * @param mixed $maxRunTime
     * @return JobResult
     */
    public function run($maxRunTime)
    {
        $finder = $this->app->finder('Truonglv\Groups:Member');
        $finder->where('member_role_id', $this->data['memberRoleId']);

        if ($finder->total() === 0) {
            return $this->complete();
        }

        $members = $finder->limit(50)->fetch();
        $timer = $maxRunTime > 0 ? new Timer($maxRunTime) : null;

        /** @var Member $member */
        foreach ($members as $member) {
            $member->member_role_id = App::MEMBER_ROLE_ID_MEMBER;
            $member->saveIfChanged();

            if ($timer !== null && $timer->limitExceeded()) {
                break;
            }
        }

        return $finder->total() > 0 ? $this->resume() : $this->complete();
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
