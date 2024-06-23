<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Admin\Controller;

use XF;
use function implode;
use Truonglv\Groups\App;
use XF\Mvc\ParameterBag;
use XF\Admin\Controller\AbstractController;

class Group extends AbstractController
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

    public function actionRebuild()
    {
        return $this->view(
            'Truonglv\Groups:Group\Rebuild',
            'tlg_tools_rebuild'
        );
    }

    public function actionImport()
    {
        return $this->view(
            'Truonglv\Groups:Group\Import',
            'tlg_import_data'
        );
    }

    public function actionList()
    {
        $page = $this->filterPage();
        $perPage = 50;

        if (!$this->request()->exists('criteria')) {
            $finder = $this->finder('Truonglv\Groups:Group');

            $privacy = $this->filter('privacy', 'str');
            $state = $this->filter('group_state', 'str');

            $pageNavParams = [];
            if ($privacy !== '') {
                $pageNavParams['privacy'] = $privacy;
                $finder->where('privacy', $privacy);
            }

            if ($state !== '') {
                $pageNavParams['group_state'] = $state;
                $finder->where('group_state', $state);
            }

            $total = $finder->total();
            $groups = $finder->limitByPage($page, $perPage)->fetch();

            return $this->view('Truonglv\Groups:Group\List', 'tlg_group_list', [
                'linkPrefix' => $this->getLinkPrefix(),
                'groups' => $groups,
                'total' => $total,
                'page' => $page,
                'perPage' => $perPage,
                'controller' => $this,
                'pageNavParams' => $pageNavParams,
            ]);
        }

        $order = $this->filter('order', 'str');
        $direction = $this->filter('direction', 'str');

        $criteria = $this->filter('criteria', 'array');

        $showingAll = $this->filter('all', 'bool') === true;
        if ($showingAll) {
            $page = 1;
            $perPage = 5000;
        }

        $searcher = $this->searcher('Truonglv\Groups:Group', $criteria);
        $searcher->setOrder($order, $direction);

        $finder = $searcher->getFinder();
        $finder->limitByPage($page, $perPage);

        $total = $finder->total();
        $groups = $finder->fetch();

        $viewParams = [
            'groups' => $groups,

            'total' => $total,
            'page' => $page,
            'perPage' => $perPage,

            'showingAll' => $showingAll,
            'showAll' => (!$showingAll && $total <= 5000),

            'criteria' => $searcher->getFilteredCriteria(),
            'order' => $order,
            'direction' => $direction,
            'linkPrefix' => $this->getLinkPrefix(),
        ];

        return $this->view('Truonglv\Groups:Group\Listing', 'tlg_group_list_criteria', $viewParams);
    }

    public function actionBatchUpdate()
    {
        $searcher = $this->searcher('Truonglv\Groups:Group');

        $viewParams = [
            'criteria' => $searcher->getFormCriteria(),
            'success' => $this->filter('success', 'bool'),
            'linkPrefix' => $this->getLinkPrefix(),
        ] + $searcher->getFormData();

        return $this->view('Truonglv\Groups:Group\BatchUpdate', 'tlg_group_batch_update', $viewParams);
    }

    public function actionBatchUpdateConfirm()
    {
        $this->assertPostOnly();

        $criteria = $this->filter('criteria', 'array');
        $searcher = $this->searcher('Truonglv\Groups:Group', $criteria);

        $groupIds = $this->filter('group_ids', 'array-uint');

        $total = count($groupIds) > 0 ? count($groupIds) : $searcher->getFinder()->total();
        if ($total <= 0) {
            throw $this->exception($this->error(XF::phraseDeferred('no_items_matched_your_filter')));
        }

        $viewParams = [
            'total' => $total,
            'groupIds' => $groupIds,
            'criteria' => $searcher->getFilteredCriteria(),

            'categories' => App::categoryRepo()->getCategoryOptionsData(false),
            'linkPrefix' => $this->getLinkPrefix(),
        ];

        return $this->view(
            'Truonglv\Groups:Group\BatchUpdateConfirm',
            'tlg_group_batch_update_confirm',
            $viewParams
        );
    }

    public function actionBatchUpdateAction()
    {
        $this->assertPostOnly();

        if ($this->request->exists('group_ids')) {
            $groupIds = $this->filter('group_ids', 'json-array');
            $total = count($groupIds);
            $jobCriteria = null;
        } else {
            $criteria = $this->filter('criteria', 'json-array');
            $searcher = $this->searcher('Truonglv\Groups:Group', $criteria);
            $total = $searcher->getFinder()->total();
            $jobCriteria = $searcher->getFilteredCriteria();

            $groupIds = null;
        }

        if ($total <= 0) {
            throw $this->exception($this->error(XF::phraseDeferred('no_items_matched_your_filter')));
        }

        $actions = $this->filter('actions', 'array');
        $actions = $this->app()->inputFilterer()->filterArray($actions, [
            'delete' => 'bool',
            'category_id' => 'uint',
            'privacy' => 'str',
            'approve' => 'bool',
            'unapprove' => 'bool',
            'soft_delete' => 'bool'
        ]);

        if ($this->request->exists('confirm_delete') && $actions['delete'] !== true) {
            return $this->error(XF::phrase('you_must_confirm_deletion_to_proceed'));
        }

        $this->app->jobManager()->enqueueUnique('tlgGroupAction', 'Truonglv\Groups:GroupAction', [
            'total' => $total,
            'actions' => $actions,
            'groupIds' => $groupIds,
            'criteria' => $jobCriteria
        ]);

        return $this->redirect($this->buildLink('tlg-groups/batch-update', null, ['success' => true]));
    }

    public function actionDelete(ParameterBag $params)
    {
        $group = $this->assertGroupExists($params['group_id']);

        /** @var XF\ControllerPlugin\Delete $deletePlugin */
        $deletePlugin = $this->plugin('XF:Delete');

        return $deletePlugin->actionDelete(
            $group,
            $this->buildLink($this->getLinkPrefix() . '/delete', $group),
            $group->getContentUrl(true),
            $this->buildLink($this->getLinkPrefix() . '/list'),
            $group->name
        );
    }

    public function getEntityExplain(\Truonglv\Groups\Entity\Group $group): ?string
    {
        $parts = [];
        $parts[] = $group->User !== null ? $group->User->username : $group->owner_username;
        $parts[] = $group->Category->title;
        $parts[] = $group->group_state;

        return implode(', ', $parts);
    }

    /**
     * @param mixed $id
     * @return \Truonglv\Groups\Entity\Group
     * @throws XF\Mvc\Reply\Exception
     */
    protected function assertGroupExists($id): \Truonglv\Groups\Entity\Group
    {
        /** @var \Truonglv\Groups\Entity\Group $group */
        $group = $this->assertRecordExists('Truonglv\Groups:Group', $id, null, 'tlg_requested_group_not_found');

        return $group;
    }

    protected function getLinkPrefix(): string
    {
        return 'tlg-groups';
    }
}
