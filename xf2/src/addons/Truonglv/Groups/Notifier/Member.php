<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Notifier;

use function count;
use XF\Entity\User;
use Truonglv\Groups\App;
use Truonglv\Groups\Entity\Group;
use XF\Notifier\AbstractNotifier;

class Member extends AbstractNotifier
{
    /**
     * @var Group
     */
    protected $group;
    /**
     * @var int
     */
    protected $contentUserId;
    /**
     * @var array
     */
    protected $memberRoleIds = [];
    /**
     * @var string
     */
    protected $alertAction = '';

    /**
     * @var \Truonglv\Groups\Entity\Member|null
     */
    protected $alertSender;

    /**
     * Member constructor.
     * @param \XF\App $app
     * @param Group $group
     * @param int $contentUserId
     */
    public function __construct(\XF\App $app, Group $group, $contentUserId = 0)
    {
        parent::__construct($app);

        $this->group = $group;
        $this->contentUserId = $contentUserId;
    }

    /**
     * @param array|string $memberRoleIds
     * @return $this
     */
    public function setMemberRoleIds($memberRoleIds)
    {
        $this->memberRoleIds = (array) $memberRoleIds;

        return $this;
    }

    /**
     * @param string $alertAction
     * @return $this
     */
    public function setAlertAction(string $alertAction)
    {
        $this->alertAction = $alertAction;

        return $this;
    }

    /**
     * @param \Truonglv\Groups\Entity\Member $member
     * @return $this
     */
    public function setAlertSender(\Truonglv\Groups\Entity\Member $member)
    {
        $this->alertSender = $member;

        return $this;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function canNotify(User $user)
    {
        if ($this->contentUserId == $user->user_id) {
            return false;
        }

        if ($this->alertSender !== null && $this->alertSender->user_id == $user->user_id) {
            return false;
        }

        return parent::canNotify($user);
    }

    /**
     * @param User $user
     * @return bool
     */
    public function sendAlert(User $user)
    {
        if ($this->alertAction !== ''
            && $this->alertSender !== null
        ) {
            return App::alert(
                $user,
                $this->alertSender->user_id,
                $this->alertSender->username,
                App::CONTENT_TYPE_MEMBER,
                $this->alertSender->member_id,
                $this->alertAction
            );
        }

        return false;
    }

    /**
     * @return array
     */
    public function getDefaultNotifyData()
    {
        $group = $this->group;

        $finder = App::memberFinder()
            ->with('User')
            ->inGroup($group)
            ->validOnly()
            ->alertable()
            ->where('User.user_state', '=', 'valid')
            ->where('User.is_banned', '=', 0);

        if (count($this->memberRoleIds) > 0) {
            $finder->where('member_role_id', $this->memberRoleIds);
        }

        $notifyData = [];
        /** @var \Truonglv\Groups\Entity\Member $member */
        foreach ($finder->fetch() as $member) {
            $notifyData[$member->user_id] = [
                'alert' => $member->isReceiveAlertType(App::MEMBER_ALERT_OPT_ALERT_ONLY),
                'email' => $member->isReceiveAlertType(App::MEMBER_ALERT_OPT_EMAIL_ONLY)
            ];
        }

        return $notifyData;
    }
}
