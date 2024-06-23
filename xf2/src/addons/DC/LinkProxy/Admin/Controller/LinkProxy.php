<?php

namespace DC\LinkProxy\Admin\Controller;

use XF\Admin\Controller\AbstractController;
use XF\Mvc\ParameterBag;

/**
 * Class Address
 * @package DBTech\eCommerce\Admin\Controller
 */
class LinkProxy extends AbstractController
{
    public function actionIndex(ParameterBag $params)
    {
        $finder = $this->finder('DC\LinkProxy:LinkProxyList');
        $page = $this->filterPage($params->page);
        $perPage = 30;
        $finder->limitByPage($page, $perPage);
        $viewpParams = [
            'lists'=> $finder->fetch(),
            'page'=> $page,
            'perPage'=> $perPage,
            'total'=> $finder->total(),

        ];
        return $this->view('DC\LinkProxy', 'link_proxy_list', $viewpParams);

    }

    public function actionAdd(ParameterBag $params)
    {
        $userGroupRepo =  \xf::app()->Repository('XF:UserGroup');
        $userGroup = $userGroupRepo->getUserGroupTitlePairs();
        $viewpParams = [
        'userGroup'=>$userGroup,
        ];
        return $this->view('DC\LinkProxy', 'link_proxy_add_settings', $viewpParams);
    }

    public function actionEdit(ParameterBag $params)
    {
        if (!$params['list_id']){
            throw $this->exception(
                $this->error(\XF::phrase("link_param_issue"))
            );
        }
        $userGroupRepo =  \xf::app()->Repository('XF:UserGroup');
        $userGroup = $userGroupRepo->getUserGroupTitlePairs();
        $link = $this->finder('DC\LinkProxy:LinkProxyList')->where('list_id', $params['list_id'])->fetchOne();
        $viewpParams = [
        'userGroup'=>$userGroup,
        'list'=>$link,

        ];
        return $this->view('DC\LinkProxy', 'link_proxy_add_settings', $viewpParams);
    }
    public function filterPage($page = 0, $inputName = 'page')
    {
        return max(1, intval($page) ?: $this->filter($inputName, 'uint'));
    }

    public function actionSave(ParameterBag $params)
    {
        $input = $this->filterInput();
        $finder = $this->finder('DC\LinkProxy:LinkProxyList');
        
        $existed_record = $finder->where('user_group_id',  $input['user_group_id'])->fetchOne();
        
        if ($existed_record){
            $record = $existed_record;
        }else{
            $record = $this->em()->create('DC\LinkProxy:LinkProxyList');
        }
        $record->bulkSet([
            'user_group_id'=> $input['user_group_id'],
            'redirect_time'=> $input['redirect_time'],
            'link_redirect_html'=> $input['link_redirect_html'],
        ]);
        $record->save();

        $viewpParams = [
       
        ];
        return $this->redirect(
            $this->buildLink('link-proxy/')
        );
        return $this->view('DC\LinkProxy', 'link_proxy_add_settings', $viewpParams);
    }
    

    protected function filterInput()
    {
        $filters = $this->filter([
            'user_group_id' => 'int',
            'redirect_time' => 'int',
            'link_redirect_html' => 'str',
            'link_id' => 'str'

        ]);
        if ($filters['user_group_id'] == '' || $filters['redirect_time'] == '' || $filters['redirect_time'] < 0){
            throw $this->exception(
                $this->error(\XF::phrase("link_fill_all_fields"))
            );
        }
        return $filters;
    }

    public function actionDelete( $params)
    {
        if (!$params['list_id']){
            throw $this->exception(
                $this->error(\XF::phrase("link_param_issue"))
            );
        }
        
        $replyExists = $this->assertDataExists($params->list_id);
        
     
        /* @var \XF\ControllerPlugin\Delete $plugin */
        $plugin = $this->plugin('XF:Delete');
        return $plugin->actionDelete(
            $replyExists,
            $this->buildLink('link-proxy/delete', $replyExists),
            null,
            $this->buildLink('link-proxy'),
            \XF::phrase('fs_link_sure_delete',['title'=> $replyExists->UserGroup->title]),
        );
    }

    protected function assertDataExists($id, array $extraWith = [], $phraseKey = null)
    {
        return $this->assertRecordExists('DC\LinkProxy:LinkProxyList', $id, $extraWith, $phraseKey);
    }
  
}