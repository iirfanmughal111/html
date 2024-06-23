<?php

namespace FS\UpgradeUserGroup\Admin\Controller;

use XF\Admin\Controller\AbstractController;
use XF\Mvc\ParameterBag;

class DowngradeUserGroup extends AbstractController
{

    public function actionIndex(ParameterBag $params)
    {
        

        // $date =date();
        // var_dump( date_format($date,"Y-m-d"));exit;
        
       // $date = date('y-m-d',$finder['last_activity']);
    //    $date  =date(\XF::$time);
    //    $finder = \XF::finder('XF:User')->where('user_id', 4)->fetchOne();
    //    $tempDays = $date - $finder['last_activity'];
    //    $days = round($a/86400);
    //     var_dump($days);exit;  
       
    //     $a = date_create($date);
    //    $time =  date_sub($a,date_interval_create_from_date_string("10 days"));
    //    $b = array();
    //     $b[] = date('y-m-d',$finder['last_activity']);
    //     $b[] = $time;
        
        
        
        
        $finder = $this->finder('FS\UpgradeUserGroup:DowngradeUserGroup')->order('usg_id', 'DESC');


        $page = $params->page;
        $perPage = 2;

        $finder->limitByPage($page, $perPage);

        $viewParams = [
            'downgradeUserGroup' => $finder->fetch(),

            'page' => $page,
            'perPage' => $perPage,
            'total' => $finder->total(),

            'totalReturn' => count($finder->fetch()),
        ];
      
    //  var_dump($viewParams['downgradeUserGroup']);exit;
    
        
        return $this->view('FS\UpgradeUserGroup:DowngradeUserGroup\Index', 'fs_downgrade_usergroup_index', $viewParams);
    }

    public function actionAdd()
    {
        $emptyData = $this->em()->create('FS\UpgradeUserGroup:DowngradeUserGroup');
        return $this->actionAddEdit($emptyData);
    }

    public function actionEdit(ParameterBag $params)
    {
        /** @var \FS\UpgradeUserGroup\Entity\DowngradeUserGroup $data */
        $data = $this->assertDataExists($params->usg_id);

        return $this->actionAddEdit($data);
    }

    public function actionAddEdit(\FS\UpgradeUserGroup\Entity\DowngradeUserGroup $data)
    {

        $viewParams = [
            'upgradeUserGroup' => $data,
            'userGroups' => $this->em()->getRepository('XF:UserGroup')->getUserGroupTitlePairs(),
        ];

        return $this->view('FS\UpgradeUserGroup:DowngradeUserGroup\Add', 'fs_downgrade_usergroup_add_edit', $viewParams);
    }

    public function actionSave(ParameterBag $params)
    {
        if ($params->usg_id) {
            $usergroupEditAdd = $this->assertDataExists($params->usg_id);
        } else {
            $usergroupEditAdd = $this->em()->create('FS\UpgradeUserGroup:DowngradeUserGroup');
        }

        $this->usergroupSaveProcess($usergroupEditAdd);

        return $this->redirect($this->buildLink('downgradeGroup'));
    }

    protected function usergroupSaveProcess(\FS\UpgradeUserGroup\Entity\DowngradeUserGroup $userGroupData)
    {
        $input = $this->filterUsergroupInputs();

        $user_id = $this->isExistedUser($input['username']);

        $userGroupData->user_id = $user_id;
        $userGroupData->exist_userGroup = $input['sl_ug_id'];
        $userGroupData->last_login = $input['last_login'];
        $userGroupData->downgrade_userGroup = $input['up_ug_id'];
        $userGroupData->save();
    }

    protected function filterUsergroupInputs()
    {
        $input = $this->filter([
            'sl_ug_id' => 'int',
            'username' => 'str',
            'last_login' => 'int',
            'up_ug_id' => 'int',
        ]);

        if ($input['sl_ug_id'] != 0 && $input['last_login'] != 0 && $input['up_ug_id'] != 0 && $input['username'] != '') {
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
            $this->buildLink('downgradeGroup/delete', $replyExists),
            null,
            $this->buildLink('downgradeGroup'),
            "{$replyExists->User->username}"
        );
    }

    /**
     * @param string $id
     * @param array|string|null $with
     * @param null|string $phraseKey
     *
     * @return \FS\UpgradeUserGroup\Entity\DowngradeUserGroup
     */
    protected function assertDataExists($id, array $extraWith = [], $phraseKey = null)
    {
        return $this->assertRecordExists('FS\UpgradeUserGroup:DowngradeUserGroup', $id, $extraWith, $phraseKey);
    }
}