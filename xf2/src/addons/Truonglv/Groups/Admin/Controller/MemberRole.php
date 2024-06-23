<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Admin\Controller;

use XF;
use XF\Entity\Phrase;
use XF\Mvc\FormAction;
use function array_keys;
use Truonglv\Groups\App;
use XF\Mvc\ParameterBag;
use function array_merge;
use function utf8_strlen;
use XF\Repository\UserGroup;
use XF\Admin\Controller\AbstractController;

class MemberRole extends AbstractController
{
    /**
     * @param mixed $action
     * @param ParameterBag $params
     * @throws \XF\Mvc\Reply\Exception
     * @return void
     */
    protected function preDispatchController($action, ParameterBag $params)
    {
        $this->assertAdminPermission(App::PERMISSION_ADMIN_MANAGE_GROUPS);
    }

    public function actionIndex()
    {
        $memberRoles = $this->finder('Truonglv\Groups:MemberRole')
            ->order('display_order')
            ->fetch();

        return $this->view('Truonglv\Groups:MemberRole\List', 'tlg_member_role_list', [
            'memberRoles' => $memberRoles,
            'total' => $memberRoles->count()
        ]);
    }

    public function actionAdd()
    {
        return $this->getMemberRoleForm($this->getNewMemberRole());
    }

    public function actionEdit(ParameterBag $params)
    {
        return $this->getMemberRoleForm($this->assertMemberRoleValid($params->member_role_id));
    }

    public function actionSave(ParameterBag $params)
    {
        $this->assertPostOnly();

        if (!isset($params['member_role_id'])) {
            $memberRoleId = $this->filter('new_member_role_id', 'str');
            if ($memberRoleId === '') {
                return $this->error(XF::phrase('tlg_please_enter_valid_member_role_id'));
            }

            $memberRole = $this->getNewMemberRole();
            $memberRole->member_role_id = $memberRoleId;
        } else {
            $memberRole = $this->assertMemberRoleValid($params->member_role_id);
        }

        $this->memberRoleSaveProcess($memberRole)->run();

        return $this->redirect($this->buildLink('group-member-roles') . $this->buildLinkHash($memberRole->member_role_id));
    }

    public function actionDelete(ParameterBag $params)
    {
        $memberRole = $this->assertMemberRoleValid($params->member_role_id);

        if (!$memberRole->preDelete()) {
            return $this->noPermission($memberRole->getErrors());
        }

        if ($this->isPost()) {
            $memberRole->delete();

            return $this->redirect($this->buildLink('group-member-roles'));
        }

        return $this->view('Truonglv\Groups:MemberRole\Delete', 'tlg_member_role_delete', [
            'memberRole' => $memberRole
        ]);
    }

    /**
     * @param \Truonglv\Groups\Entity\MemberRole $memberRole
     * @return FormAction
     */
    protected function memberRoleSaveProcess(\Truonglv\Groups\Entity\MemberRole $memberRole)
    {
        $inputData = $this->getFormInputData();

        $form = $this->formAction();
        $form->basicEntitySave($memberRole, $inputData['memberRole']);

        $form->validate(function (FormAction $form) use ($inputData) {
            if (!utf8_strlen($inputData['title'])) {
                $form->logError(XF::phrase('please_enter_valid_title'), 'title');
            }
        });

        $form->apply(function () use ($inputData, $memberRole) {
            /** @var Phrase $phrase */
            $phrase = $memberRole->getMasterPhrase(true);
            $phrase->phrase_text = $inputData['title'];
            $phrase->save();

            /** @var Phrase $descPhrase */
            $descPhrase = $memberRole->getMasterPhrase(false);
            $descPhrase->phrase_text = $inputData['description'];
            $descPhrase->save();
        });

        return $form;
    }

    /**
     * @return array
     */
    protected function getFormInputData()
    {
        $inputData = $this->filter([
            'memberRole' => [
                'user_group_ids' => 'array-uint',
                'display_order' => 'uint'
            ],
            'title' => 'str',
            'description' => 'str'
        ]);

        $optionRules = [];
        foreach (App::memberRoleRepo()->getMemberRoleHandlers() as $handler) {
            if ($handler->count() > 0) {
                $optionRules = array_merge($optionRules, $handler->getRoleFilterRules());
            }
        }

        $inputData['memberRole'] += $this->filter([
            'role_permissions' => $optionRules
        ]);

        return $inputData;
    }

    /**
     * @return \Truonglv\Groups\Entity\MemberRole
     */
    protected function getNewMemberRole()
    {
        /** @var \Truonglv\Groups\Entity\MemberRole $memberRole */
        $memberRole = $this->em()->create('Truonglv\Groups:MemberRole');

        return $memberRole;
    }

    /**
     * @param \Truonglv\Groups\Entity\MemberRole $memberRole
     * @return \XF\Mvc\Reply\View
     */
    protected function getMemberRoleForm(\Truonglv\Groups\Entity\MemberRole $memberRole)
    {
        $roles = App::memberRoleRepo()->getMemberRoleHandlers();
        foreach ($roles as $index => $handler) {
            if (!$handler->isEnabled()) {
                unset($roles[$index]);
            }
        }
        /** @var UserGroup $userGroupRepo */
        $userGroupRepo = $this->repository('XF:UserGroup');

        return $this->view('Truonglv\Groups:MemberRole\Form', 'tlg_member_role_add', [
            'memberRole' => $memberRole,
            'roles' => $roles,
            'roleGroups' => array_keys($roles),
            'userGroups' => $userGroupRepo->getUserGroupTitlePairs(),
        ]);
    }

    /**
     * @param int $id
     * @return \Truonglv\Groups\Entity\MemberRole
     * @throws \XF\Mvc\Reply\Exception
     */
    protected function assertMemberRoleValid($id)
    {
        /** @var \Truonglv\Groups\Entity\MemberRole $memberRole */
        $memberRole = $this->assertRecordExists('Truonglv\Groups:MemberRole', $id);

        return $memberRole;
    }
}
