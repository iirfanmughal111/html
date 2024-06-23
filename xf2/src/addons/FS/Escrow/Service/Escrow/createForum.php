<?php

namespace FS\Escrow\Service\Escrow;

use XF\Mvc\FormAction;

class createForum extends \XF\Service\AbstractService
{
    public $nodeId;

    public function createNode()
    {
        $node = \xf::app()->em()->create('XF:Node');
        $node->node_type_id = "Forum";
        $this->createForumNode($node)->run();
        return $node;
    }

    protected function createForumNode(\XF\Entity\Node $node)
    {
        $form = \xf::app()->formAction();

        $input = [
            'node' => [
                'title' => 'Escrow',
                'node_name' => '',
                'description' => '',
                'parent_node_id' => '',
                'display_order' => '',
                'display_in_list' => false,
                'style_id' => '',
                'navigation_id' => ''
            ]
        ];

        $data = $node->getDataRelationOrDefault(false);
        $node->addCascadedSave($data);

        $form->basicEntitySave($node, $input['node']);
        $this->saveTypeData($form, $node, $data);

        return $form;
    }

    protected function getForumTypeHandlerForAddEdit(\XF\Entity\Node $node)
    {
        /** @var \XF\Entity\Forum $forum */
        $forum = $node->getDataRelationOrDefault(false);

        if (!$node->exists()) {

            return $this->app->forumType("discussion", false);
        } else {
            return $forum->TypeHandler;
        }
    }

    protected function saveTypeData(FormAction $form, \XF\Entity\Node $node, \XF\Entity\AbstractNode $data)
    {
        $forumType = $this->getForumTypeHandlerForAddEdit($node);
        if (!$forumType) {
            $form->logError(\XF::phrase('forum_type_handler_not_found'), 'forum_type_id');
            return;
        }

        $forumInput = [
            'allow_posting' => 'true',
            'moderate_threads' => 'false',
            'moderate_replies' => 'false',
            'count_messages' => 'true',
            'find_new' => 'true',
            'allowed_watch_notifications' => 'all',
            'default_sort_order' => 'last_post_date',
            'default_sort_direction' => 'desc',
            'list_date_limit_days' => 0,
            'default_prefix_id' => 0,
            'require_prefix' => false,
            'min_tags' => 0,
            'allow_index' => 'allow'
        ];

        $forumInput['index_criteria'] = $this->filterIndexCriteria();

        /** @var \XF\Entity\Forum $data */
        $data->bulkSet($forumInput);
        $data->forum_type_id = $forumType->getTypeId();

        $typeConfig = $forumType->setupTypeConfigSave($form, $node, $data, \xf::app()->request);
        if ($typeConfig instanceof \XF\Mvc\Entity\ArrayValidator) {
            if ($typeConfig->hasErrors()) {
                $form->logErrors($typeConfig->getErrors());
            }
            $typeConfig = $typeConfig->getValuesForced();
        }

        $data->type_config = $typeConfig;
    }

    protected function filterIndexCriteria()
    {
        $criteria = [];

        $input =

            [
                'max_days_post' => [
                    'enabled' => false,
                    'value' => 0
                ],
                'max_days_last_post' => [
                    'enabled' => false,
                    'value' => 0
                ],
                'min_replies' => [
                    'enabled' => false,
                    'value' => 0
                ],
                'min_reaction_score' => [
                    'enabled' => false,
                    'value' => 0
                ]
            ];

        foreach ($input as $rule => $criterion) {
            if (!$criterion['enabled']) {
                continue;
            }

            $criteria[$rule] = $criterion['value'];
        }

        return $criteria;
    }

    public function updateOptionsforum($nodeId)
    {

        $optionIndex = \xf::app()->finder('XF:Option')->where('option_id', 'fs_escrow_applicable_forum')->fetchOne();

        if ($optionIndex) {

            $optionIndex->option_value = $nodeId;
            $optionIndex->save();
        }
    }

    public function permissionRebuild()
    {

        // $userGroup = \xf::app()->finder('XF:UserGroup')->whereId(2)->fetchOne();


        // $permissions = [
        //     'general' => [

        //         'viewNode' => 'content_allow',
        //     ],
        //     'forum' => [

        //         'postThread' => 'content_allow',
        //         'postReply' => 'content_allow',
        //     ]
        // ];



        // $permissionUpdater = \xf::app()->service('XF:UpdatePermissions');
        // $permissionUpdater->setContent("node", $node->node_id)->setUserGroup($userGroup);
        // $permissionUpdater->updatePermissions($permissions);

        $userGroups = $this->getUserGroupRepo()->findUserGroupsForList()->fetch();

        if (count($userGroups)) {
            //
            $permissionUpdater = \xf::app()->service('XF:UpdatePermissions');
            foreach ($userGroups as $group) {

                $permissionUpdater->setUserGroup($group)->setGlobal();
                if (\xf::app()->container()->isCached('permission.builder')) {
                    \xf::app()->permissionBuilder()->refreshData();
                }

                $permissionUpdater->triggerCacheRebuild();
            }
        }
    }

    public function getUserGroupRepo()
    {
        return \xf::app()->repository('XF:UserGroup');
    }
}
