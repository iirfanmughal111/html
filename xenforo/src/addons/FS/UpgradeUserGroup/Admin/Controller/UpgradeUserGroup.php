<?php

namespace FS\UpgradeUserGroup\Admin\Controller;

use XF\Admin\Controller\AbstractController;
use XF\Mvc\ParameterBag;

class UpgradeUserGroup extends AbstractController
{

    public function actionIndex(ParameterBag $params)
    {
        $finder = $this->finder('FS\UpgradeUserGroup:UpgradeUserGroup')->order('usg_id', 'DESC');


        $page = $params->page;
        $perPage = 2;

        $finder->limitByPage($page, $perPage);

        $viewParams = [
            'upgradeUserGroup' => $finder->fetch(),

            'page' => $page,
            'perPage' => $perPage,
            'total' => $finder->total(),

            'totalReturn' => count($finder->fetch()),
        ];

        return $this->view('FS\UpgradeUserGroup:UpgradeUserGroup\Index', 'fs_upgrade_usergroup_index', $viewParams);
    }

    public function actionAdd()
    {
        $emptyData = $this->em()->create('FS\UpgradeUserGroup:UpgradeUserGroup');
        return $this->actionAddEdit($emptyData);
    }

    public function actionEdit(ParameterBag $params)
    {
        /** @var \FS\UpgradeUserGroup\Entity\UpgradeUserGroup $data */
        $data = $this->assertDataExists($params->usg_id);

        return $this->actionAddEdit($data);
    }

    public function actionAddEdit(\FS\UpgradeUserGroup\Entity\UpgradeUserGroup $data)
    {

        $viewParams = [
            'upgradeUserGroup' => $data,
            'userGroups' => $this->em()->getRepository('XF:UserGroup')->getUserGroupTitlePairs(),
        ];

        return $this->view('FS\UpgradeUserGroup:UpgradeUserGroup\Add', 'fs_upgrade_usergroup_add_edit', $viewParams);
    }

    public function actionSave(ParameterBag $params)
    {
        if ($params->usg_id) {
            $usergroupEditAdd = $this->assertDataExists($params->usg_id);
        } else {
            $usergroupEditAdd = $this->em()->create('FS\UpgradeUserGroup:UpgradeUserGroup');
        }

        $this->usergroupSaveProcess($usergroupEditAdd);

        return $this->redirect($this->buildLink('upgradeGroup'));
    }

    protected function usergroupSaveProcess(\FS\UpgradeUserGroup\Entity\UpgradeUserGroup $userGroupData)
    {
        $input = $this->filterUsergroupInputs();

        $user_id = $this->isExistedUser($input['username']);

        $userGroupData->user_id = $user_id;
        $userGroupData->exist_userGroup = $input['sl_ug_id'];
        $userGroupData->total_messages = $input['total_message'];
        $userGroupData->upgrade_userGroup = $input['up_ug_id'];
        $userGroupData->save();
    }

    protected function filterUsergroupInputs()
    {
        $input = $this->filter([
            'sl_ug_id' => 'int',
            'username' => 'str',
            'total_message' => 'int',
            'up_ug_id' => 'int',
        ]);

        if ($input['sl_ug_id'] != 0 && $input['total_message'] != 0 && $input['up_ug_id'] != 0 && $input['username'] != '') {
            if ($input['sl_ug_id'] != $input['up_ug_id']) {
                return $input;
            } else {
                throw $this->exception(
                    $this->notFound(\XF::phrase("fs_select_different_usergroups"))
                );
            }
        }

        throw $this->exception(
            $this->notFound(\XF::phrase("fs_filled_reqired_fields"))
        );
    }

    protected function isExistedUser($value)
    {
        $user = $this->em()->findOne('XF:User', ['username' => $value]);

        if (!$user) {
            throw $this->exception($this->error(\XF::phraseDeferred('requested_user_x_not_found', ['name' => $value])));
        }

        return $user['user_id'];
    }

    public function actionDelete(ParameterBag $params)
    {
        $replyExists = $this->assertDataExists($params->usg_id, ['User']);

        /** @var \XF\ControllerPlugin\Delete $plugin */
        $plugin = $this->plugin('XF:Delete');
        return $plugin->actionDelete(
            $replyExists,
            $this->buildLink('upgradeGroup/delete', $replyExists),
            null,
            $this->buildLink('upgradeGroup'),
            "{$replyExists->User->username}"
        );
    }

    /**
     * @param string $id
     * @param array|string|null $with
     * @param null|string $phraseKey
     *
     * @return \FS\UpgradeUserGroup\Entity\UpgradeUserGroup
     */
    protected function assertDataExists($id, array $extraWith = [], $phraseKey = null)
    {
        return $this->assertRecordExists('FS\UpgradeUserGroup:UpgradeUserGroup', $id, $extraWith, $phraseKey);
    }
}