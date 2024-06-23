<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Pub\Controller;

use XF;
use Truonglv\Groups\App;
use XF\Mvc\ParameterBag;
use XF\ControllerPlugin\Delete;
use XF\Pub\Controller\AbstractController;
use Truonglv\Groups\Service\Member\Banning;
use Truonglv\Groups\Service\Member\Approver;

class Member extends AbstractController
{
    /**
     * @param mixed $action
     * @param ParameterBag $params
     * @throws \XF\Mvc\Reply\Exception
     * @return void
     */
    protected function preDispatchController($action, ParameterBag $params)
    {
        parent::preDispatchController($action, $params);

        if (!App::hasPermission('view')) {
            throw $this->exception($this->noPermission());
        }
    }

    public function actionFilters()
    {
        $groupId = $this->filter('group_id', 'uint');
        $group = App::assertionPlugin($this)->assertGroupViewable($groupId);

        return App::memberListPlugin($this)->actionFilters($group);
    }

    public function actionApprove(ParameterBag $params)
    {
        $member = App::assertionPlugin($this)->assertMemberViewable($params);
        if (!$member->canBeApproved($error)) {
            return $this->noPermission($error);
        }

        /** @var Approver $approver */
        $approver = $this->service('Truonglv\Groups:Member\Approver', $member);
        $approver->approve();

        return $this->redirect($this->buildLink('groups/members', $member->Group, [
            'member_state' => App::MEMBER_STATE_MODERATED
        ]));
    }

    public function actionNotify(ParameterBag $params)
    {
        $member = App::assertionPlugin($this)->assertMemberViewable($params);

        if (!$member->canUpdateNotify($error)) {
            return $this->noPermission($error);
        }

        if ($this->isPost()) {
            $options = $this->filter([
                'email' => 'bool',
                'alert' => 'bool',
                'alert_post' => 'bool',
                'alert_event' => 'bool',
                'alert_resource' => 'bool',
            ]);

            /** @var \Truonglv\Groups\Service\Member\Updater $updater */
            $updater = $this->service('Truonglv\Groups:Member\Updater', $member);
            $updater->setAlertVia($options['email'], $options['alert']);

            $member = $updater->getMember();

            if (!$updater->validate($errors)) {
                return $this->error($errors);
            }

            $updater->save();

            return $this->redirect($this->buildLink('groups', $member->Group));
        }

        $viewParams = [
            'member' => $member,
            'group' => $member->Group,
            'needWrapper' => $this->filter('_xfWithData', 'bool') === false
        ];

        return $this->view(
            'Truonglv\Groups:Member\Notify',
            'tlg_member_notify_settings',
            $viewParams
        );
    }

    public function actionLeave(ParameterBag $params)
    {
        $member = App::assertionPlugin($this)->assertMemberViewable($params);

        if (!$member->canLeave($errors)) {
            return $this->noPermission($errors);
        }

        if ($this->isPost()) {
            if ($member->isOwner()) {
                $reassignUser = $this->filter('username', 'str');

                /** @var \Truonglv\Groups\Service\Group\Reassigner $reassigner */
                $reassigner = $this->service('Truonglv\Groups:Group\Reassigner', $member->Group);
                $reassigner->setNewOwnerName($reassignUser)
                           ->assign();
            }

            $member->delete();

            return $this->redirect($this->buildLink('groups', $member->Group));
        }

        $viewParams = [
            'member' => $member,
            'group' => $member->Group,
            'needWrapper' => $this->filter('_xfWithData', 'bool') === false
        ];

        return $this->view(
            'Truonglv\Groups:Member\Leave',
            'tlg_member_leave_confirm',
            $viewParams
        );
    }

    public function actionRemove(ParameterBag $params)
    {
        $member = App::assertionPlugin($this)->assertMemberViewable($params);
        if (!$member->canBeRemove($error)) {
            return $this->noPermission($error);
        }

        /** @var Delete $delete */
        $delete = $this->plugin('XF:Delete');

        return $delete->actionDelete(
            $member,
            $this->buildLink('group-members/remove', $member),
            null,
            $this->buildLink('groups/members', $member->Group),
            $member->User !== null ? $member->User->username : $member->username
        );
    }

    public function actionBan(ParameterBag $params)
    {
        $member = App::assertionPlugin($this)->assertMemberViewable($params);
        if (!$member->canBeBanned($error)) {
            return $this->noPermission($error);
        }

        if ($this->isPost()) {
            $expireDate = 0;
            if ($this->filter('expire_type', 'bool') === true) {
                $expireDate = $this->filter('expire_date', 'datetime,end');
            }

            /** @var Banning $banning */
            $banning = $this->service('Truonglv\Groups:Member\Banning', $member);
            $banning->ban($expireDate);

            return $this->redirect($this->buildLink('groups/members', $member->Group));
        }

        return App::assistantPlugin($this)->formTimePeriod(
            XF::phrase('tlg_ban_member'),
            $this->buildLink('group-members/ban', $member),
            $member->ban_end_date
        );
    }

    public function actionLiftBan(ParameterBag $params)
    {
        $member = App::assertionPlugin($this)->assertMemberViewable($params);
        if (!$member->canLiftBan($error)) {
            return $this->noPermission($error);
        }

        $member->ban_end_date = 0;
        $member->member_state = App::MEMBER_STATE_VALID;
        $member->save();

        return $this->redirect($this->buildLink('groups/members', $member->Group));
    }

    public function actionPromote(ParameterBag $params)
    {
        $member = App::assertionPlugin($this)->assertMemberViewable($params);
        if (!$member->canBePromote($errors)) {
            return $this->noPermission($errors);
        }

        $memberRoles = App::memberRoleRepo()->getAllMemberRoles();
        $type = $this->filter('type', 'str');

        if ($type === App::MEMBER_ROLE_ID_ADMIN
            || $type === App::MEMBER_ROLE_ID_MODERATOR
        ) {
            /** @var \Truonglv\Groups\Service\Member\Promoter $promoter */
            $promoter = $this->service('Truonglv\Groups:Member\Promoter', $member);

            if ($type === App::MEMBER_ROLE_ID_ADMIN) {
                if ($member->isAdmin()) {
                    $promoter->removeAdmin();
                } else {
                    $promoter->makeAdmin();
                }
            } else {
                if ($member->isModerator()) {
                    $promoter->removeModerator();
                } else {
                    $promoter->makeModerator();
                }
            }

            $promoter->promote();

            return $this->redirect($this->buildLink('groups/members', $member->Group));
        }

        if ($this->isPost()) {
            $newMemberRoleId = $this->filter('member_role_id', 'str');
            if (!isset($memberRoles[$newMemberRoleId])) {
                return $this->error(XF::phrase('tlg_please_select_a_valid_member_role'));
            }

            /** @var \Truonglv\Groups\Service\Member\Promoter $promoter */
            $promoter = $this->service('Truonglv\Groups:Member\Promoter', $member);

            $promoter->setMemberRoleId($newMemberRoleId);
            $promoter->promote();

            return $this->redirect($this->buildLink('groups/members', $member->Group));
        }

        $viewParams = [
            'member' => $member,
            'memberRoles' => $memberRoles,
            'group' => $member->Group,
            'inlinePromote' => $this->filter('_xfWithData', 'bool'),
        ];

        return $this->view(
            'Truonglv\Groups:Member\Promote',
            'tlg_member_promote',
            $viewParams
        );
    }

    public function actionAccepted(ParameterBag $params)
    {
        $member = App::assertionPlugin($this)->assertMemberViewable($params);
        if (!$member->isInvited()) {
            return $this->noPermission();
        }
        if ($member->Group === null) {
            return $this->noPermission();
        }

        $member->member_state = $member->Group->always_moderate_join
            ? App::MEMBER_STATE_MODERATED
            : App::MEMBER_STATE_VALID;
        $member->alert = App::MEMBER_ALERT_OPT_ALL;
        if ($member->member_state !== App::MEMBER_STATE_VALID) {
            $member->member_role_id = '';
        }

        $member->save();

        return $this->redirect($this->buildLink('groups', $member->Group));
    }

    /**
     * @param array $activities
     * @return bool|\XF\Phrase
     */
    public static function getActivityDetails(array $activities)
    {
        return XF::phrase('tlg_viewing_group');
    }
}
