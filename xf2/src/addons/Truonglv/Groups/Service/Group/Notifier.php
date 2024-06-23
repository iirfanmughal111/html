<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Service\Group;

use XF;
use Exception;
use LogicException;
use Truonglv\Groups\App;
use XF\Service\AbstractNotifier;
use Truonglv\Groups\Entity\Group;
use Truonglv\Groups\Entity\Member;

class Notifier extends AbstractNotifier
{
    /**
     * @var Group
     */
    protected $group;
    /**
     * @var string
     */
    protected $actionType;
    /**
     * @var Member|null
     */
    protected $member;

    public function __construct(\XF\App $app, Group $group, string $actionType)
    {
        parent::__construct($app);

        switch ($actionType) {
            case 'request_join':
                break;
            default:
                throw new LogicException('Unknown action type (' . $actionType . ')');
        }

        $this->group = $group;
        $this->actionType = $actionType;
    }

    /**
     * @param Member $member
     * @return $this
     */
    public function setActionMember(Member $member)
    {
        $this->member = $member;

        return $this;
    }

    /**
     * @param array $users
     * @return void
     */
    protected function loadExtraUserData(array $users)
    {
    }

    /**
     * @return array
     */
    protected function loadNotifiers()
    {
        $notifiers = [];

        if ($this->actionType === 'request_join') {
            if ($this->member === null) {
                throw new LogicException('Action member must be set for action `request_join`');
            }

            /** @var \Truonglv\Groups\Notifier\Member $memberNotifier */
            $memberNotifier = $this->app->notifier('Truonglv\Groups:Member', $this->group);
            $memberNotifier->setAlertAction($this->actionType);

            $memberNotifier->setAlertSender($this->member);
            $memberNotifier->setMemberRoleIds(App::memberRoleRepo()->getMemberRoleIdsWithPermission(
                App::MEMBER_ROLE_PERM_KEY_MEMBER,
                'approve'
            ));

            $notifiers['member'] = $memberNotifier;
        }

        return $notifiers;
    }

    /**
     * @param \XF\Entity\User $user
     * @return bool
     * @throws Exception
     */
    protected function canUserViewContent(\XF\Entity\User $user)
    {
        return XF::asVisitor($user, function () {
            return $this->group->canView();
        });
    }

    /**
     * @return array
     */
    protected function getExtraJobData()
    {
        return [
            'groupId' => $this->group->group_id,
            'actionType' => $this->actionType,
            'actionMemberId' => $this->member !== null ? $this->member->member_id : 0
        ];
    }

    /**
     * @param array $extraData
     * @return Notifier|null
     */
    public static function createForJob(array $extraData)
    {
        /** @var Group|null $group */
        $group = XF::em()->find('Truonglv\Groups:Group', $extraData['groupId'], ['Category']);
        if ($group === null) {
            return null;
        }

        /** @var static $service */
        $service = XF::service('Truonglv\Groups:Group\Notifier', $group, $extraData['actionType']);
        if ($extraData['actionType'] === 'request_join') {
            if (!isset($extraData['actionMemberId'])) {
                return null;
            }

            /** @var Member|null $member */
            $member = XF::em()->find('Truonglv\Groups:Member', $extraData['actionMemberId']);
            if ($member === null) {
                return null;
            }

            $service->setActionMember($member);
        }

        return $service;
    }
}
