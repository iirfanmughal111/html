<?php

namespace Truonglv\Groups\Service\Member;

use XF;
use XF\Entity\User;
use Truonglv\Groups\App;
use XF\Repository\ForumWatch;
use XF\Service\AbstractService;
use Truonglv\Groups\Entity\Forum;
use Truonglv\Groups\Entity\Member;

class Approver extends AbstractService
{
    /**
     * @var Member
     */
    protected $member;
    /**
     * @var bool
     */
    protected $autoAlerts;
    /**
     * @var bool
     */
    protected $logAction;
    /**
     * @var bool
     */
    protected $automaticallyWatch;

    public function __construct(\XF\App $app, Member $member)
    {
        parent::__construct($app);

        $this->member = $member;

        $this->setAutoAlerts(true);
        $this->setLogAction(true);
        $this->setAutomaticallyWatch(true);
    }

    /**
     * @param bool $automaticallyWatch
     * @return void
     */
    public function setAutomaticallyWatch($automaticallyWatch)
    {
        $this->automaticallyWatch = $automaticallyWatch;
    }

    /**
     * @param bool $autoAlerts
     * @return void
     */
    public function setAutoAlerts($autoAlerts)
    {
        $this->autoAlerts = $autoAlerts;
    }

    /**
     * @param bool $logAction
     * @return void
     */
    public function setLogAction($logAction)
    {
        $this->logAction = $logAction;
    }

    /**
     * @throws \XF\PrintableException
     * @return void
     */
    public function approve()
    {
        $db = $this->db();
        $db->beginTransaction();

        $member = $this->member;

        $member->member_state = App::MEMBER_STATE_VALID;
        $member->member_role_id = App::MEMBER_ROLE_ID_MEMBER;

        $member->save(true, false);

        if ($this->autoAlerts) {
            $this->sendNotifications();
        }

        if ($this->automaticallyWatch) {
            $this->automaticallyWatchForums();
        }

        if ($this->logAction && $member->Group !== null) {
            App::logAction(
                $member->Group,
                'member',
                $member->user_id,
                'approved'
            );
        }

        $this->removeRequestNotifications();
        $db->commit();
    }

    /**
     * @return void
     */
    protected function automaticallyWatchForums()
    {
        if (!App::isEnabledForums()) {
            return;
        }

        $member = $this->member;
        /** @var User $user */
        $user = $member->User;

        $records = $this->finder('Truonglv\Groups:Forum')
            ->with(['Forum', 'Forum.Watch|' . $member->user_id])
            ->where('group_id', $member->group_id)
            ->fetch();
        if ($records->count() === 0) {
            return;
        }

        /** @var ForumWatch $forumWatch */
        $forumWatch = $this->app->repository('XF:ForumWatch');

        /** @var Forum $record */
        foreach ($records as $record) {
            if ($record->Forum === null || isset($record->Forum->Watch[$member->user_id])) {
                continue;
            }

            $forumWatch->setWatchState(
                $record->Forum,
                $user,
                'message',
                true
            );
        }
    }

    /**
     * @return void
     */
    public function sendNotifications()
    {
        $member = $this->member;
        $visitor = XF::visitor();

        if ($member->User !== null) {
            App::alert(
                $member->User,
                $visitor->user_id,
                $visitor->username,
                $member->getEntityContentType(),
                $member->member_id,
                'state_approved'
            );
        }
    }

    /**
     * @return void
     */
    protected function removeRequestNotifications()
    {
        $member = $this->member;

        $this->alertRepo()->fastDeleteAlertsFromUser(
            $member->user_id,
            $member->getEntityContentType(),
            $member->member_id,
            'request_join'
        );
    }

    /**
     * @return \XF\Repository\UserAlert
     */
    protected function alertRepo()
    {
        /** @var \XF\Repository\UserAlert $userAlertRepo */
        $userAlertRepo = $this->repository('XF:UserAlert');

        return $userAlertRepo;
    }
}
