<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Pub\Controller;

use XF;
use XF\Entity\Node;
use XF\Mvc\FormAction;
use XF\Mvc\Reply\View;
use Truonglv\Groups\App;
use XF\Mvc\ParameterBag;
use XF\Mvc\Reply\AbstractReply;
use XF\Pub\Controller\AbstractController;

class Forum extends AbstractController
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

        if (!App::hasPermission('view') || !App::isEnabledForums()) {
            throw $this->exception($this->noPermission());
        }
    }

    /**
     * @param mixed $action
     * @param ParameterBag $params
     * @param AbstractReply $reply
     * @return void
     */
    protected function postDispatchController($action, ParameterBag $params, AbstractReply & $reply)
    {
        parent::postDispatchController($action, $params, $reply);

        if (!$reply instanceof View) {
            return;
        }

        /** @var \Truonglv\Groups\Entity\Group|null $group */
        $group = $reply->getParam('group');
        if ($group !== null) {
            $reply->setContainerKey('tlg-group-' . $group->group_id);
        }
    }

    public function actionAdd()
    {
        $group = App::assertionPlugin($this)
            ->assertGroupViewable($this->filter('group_id', 'uint'), ['Feature']);

        if (!$group->canAddForum($errors)) {
            return $this->noPermission($errors);
        }

        /** @var \Truonglv\Groups\XF\Entity\Node $node */
        $node = $this->em()->create('XF:Node');
        $node->node_type_id = App::NODE_TYPE_ID;

        if ($this->isPost()) {
            $this->nodeSaveProcess($group, $node)->run();

            return $this->redirect($this->buildLink('forums', $node->getDataRelationOrDefault()));
        }

        /** @var \Truonglv\Groups\XF\Entity\Forum $forum */
        $forum = $node->getDataRelationOrDefault(false);

        return $this->getForumForm(
            'Truonglv\Group:Forum\Add',
            $group,
            $node,
            $forum
        );
    }

    public function actionEdit(ParameterBag $params)
    {
        $forum = App::assertionPlugin($this)->assertForumViewable($params->node_id);
        $group = App::getGroupEntityFromEntity($forum);

        if ($group === null || $forum->Node === null) {
            return $this->noPermission();
        }

        if (!$group->canEditForum($error)) {
            return $this->noPermission($error);
        }

        if ($this->isPost()) {
            $this->nodeSaveProcess($group, $forum->Node)->run();

            return $this->redirect($this->buildLink('forums', $forum));
        }

        return $this->getForumForm(
            'Truonglv\Group:Forum\Edit',
            $group,
            $forum->Node,
            $forum
        );
    }

    public function actionDelete(ParameterBag $params)
    {
        $forum = App::assertionPlugin($this)->assertForumViewable($params->node_id);
        $group = App::getGroupEntityFromEntity($forum);

        $error = null;
        if ($group === null || !$group->canDeleteForum($error)) {
            return $this->noPermission($error);
        }

        if ($this->isPost()) {
            $group->deleteForum($forum);

            return $this->redirect($this->buildLink('groups', $group));
        }

        return $this->view(
            'Truonglv\Groups:Forum\Delete',
            'delete_confirm',
            [
                'confirmUrl' => $this->buildLink('group-forums/delete', $forum),
                'contentUrl' => $this->buildLink('forums', $forum),
                'contentTitle' => $forum->title,
                'content' => $forum
            ]
        );
    }

    /**
     * @param string $viewClass
     * @param \Truonglv\Groups\Entity\Group $group
     * @param Node $node
     * @param \XF\Entity\Forum $forum
     * @return \XF\Mvc\Reply\View
     */
    protected function getForumForm(
        $viewClass,
        \Truonglv\Groups\Entity\Group $group,
        Node $node,
        \XF\Entity\Forum $forum
    ) {
        $viewParams = [
            'group' => $group,
            'node' => $node,
            'forum' => $forum,
            'nodeTree' => App::nodeTreeData()->forumTree($group),
            'withData' => $this->filter('_xfWithData', 'bool'),
            'formAction' => $forum->exists()
                ? $this->buildLink('group-forums/edit', $forum)
                : $this->buildLink('group-forums/add', null, ['group_id' => $group->group_id])
        ];

        return $this->view($viewClass, 'tlg_forum_add', $viewParams);
    }

    /**
     * @param \Truonglv\Groups\Entity\Group $group
     * @param Node $node
     * @return FormAction
     */
    protected function nodeSaveProcess(\Truonglv\Groups\Entity\Group $group, Node $node)
    {
        $node->setOption(App::OPTION_MANUAL_REBUILD_PERMISSION, function () {
            $visitor = XF::visitor();
            /** @var \XF\Entity\PermissionCombination|null $combination */
            $combination = $this->em()->find('XF:PermissionCombination', $visitor->permission_combination_id);
            if ($combination !== null) {
                $permissionBuilder = $this->app()->permissionBuilder();
                $permissionBuilder->rebuildCombination($combination);
            }
        });

        $form = $this->formAction();

        $input = $this->filter([
            'node' => [
                'title' => 'str',
                'description' => 'str',
                'parent_node_id' => 'uint'
            ]
        ]);

        if ($node->isInsert()) {
            $finder = $this->finder('XF:Forum');
            $finder->with('Node', true);
            $finder->with('GroupForum', true);
            $finder->where('GroupForum.group_id', '>', 0);
            $finder->order('Node.display_order', 'desc');

            /** @var \Truonglv\Groups\XF\Entity\Forum|null $lastNode */
            $lastNode = $finder->fetchOne();
            if ($node->display_order < App::$nodeStartOrder) {
                $input['node']['display_order'] =
                    ($lastNode != null && $lastNode->Node !== null)
                        ? ($lastNode->Node->display_order + 50)
                        : App::$nodeStartOrder;
            }
        }

        /** @var \Truonglv\Groups\XF\Entity\Forum $data */
        $data = $node->getDataRelationOrDefault();

        if ($node->isInsert()) {
            $groupForum = $data->getNewGroupForum();
            $groupForum->group_id = $group->group_id;

            $node->addCascadedSave($data);
        }

        $archiveNodeId = $this->options()->tl_groups_archiveNodeId;
        if ($input['node']['parent_node_id'] <= 0) {
            $input['node']['parent_node_id'] = $archiveNodeId;
        }

        $form->validate(function (FormAction $form) use ($input, $archiveNodeId, $group) {
            // Bug: https://nobita.me/threads/how-to-disable-html-in-node-description.1770/
            $description = $input['node']['description'];
            $stripped = strip_tags($description);
            if ($stripped !== $description) {
                $form->logError(XF::phrase('tlg_description_contains_invalid_characters'), 'description');
            }

            if ($input['node']['parent_node_id'] > 0
                && $input['node']['parent_node_id'] != $archiveNodeId
            ) {
                $nodeTree = App::nodeTreeData()->forumTree($group);

                if (!$nodeTree->getData($input['node']['parent_node_id'])) {
                    $form->logError(XF::phrase('please_select_valid_parent'), 'parent_node_id');
                }
            }
        });

        $form->basicEntitySave($node, $input['node']);
        $this->saveTypeData($form, $node, $data, $group);

        $form->complete(function () use ($data, $group) {
            $this->app()->jobManager()->enqueueLater(
                'tlg_autoWatchForum' . $data->node_id,
                XF::$time,
                'Truonglv\Groups:AutoWatchForum',
                [
                   'forumId' => $data->node_id,
                   'groupId' => $group->group_id,
               ]
            );
        });

        return $form;
    }

    /**
     * @param FormAction $form
     * @param Node $node
     * @param \XF\Entity\Forum $data
     * @param \Truonglv\Groups\Entity\Group $group
     * @return void
     */
    protected function saveTypeData(
        FormAction $form,
        \XF\Entity\Node $node,
        \XF\Entity\Forum $data,
        \Truonglv\Groups\Entity\Group $group
    ) {
        $input = $this->filter([
            'min_tags' => 'uint',
            'allowed_watch_notifications' => 'str',
            'default_sort_order' => 'str',
            'default_sort_direction' => 'str',
            'list_date_limit_days' => 'uint',
        ]);

        if ($data->node_id <= 0) {
            // insert mode
            $input += [
                'require_prefix' => false,
                'default_prefix_id' => 0,
                'allow_posting' => true,
                'moderate_threads' => false,
                'moderate_replies' => false,
                'count_messages' => true,
                'find_new' => false,
            ];
        }

        $data->bulkSet($input);
    }
}
