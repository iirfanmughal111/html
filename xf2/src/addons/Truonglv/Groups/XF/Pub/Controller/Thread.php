<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\XF\Pub\Controller;

use XF;
use XF\Tree;
use XF\Mvc\Reply\View;
use Truonglv\Groups\App;
use XF\Mvc\ParameterBag;
use XF\Mvc\Reply\Exception;
use Truonglv\Groups\Listener;
use Truonglv\Groups\Data\Badge;
use XF\Mvc\Reply\AbstractReply;
use Truonglv\Groups\Entity\Group;
use Truonglv\Groups\XF\Entity\Post;
use Truonglv\Groups\XF\Entity\User;

class Thread extends XFCP_Thread
{
    public function actionIndex(ParameterBag $params)
    {
        if (!App::isEnabledForums()) {
            $response = parent::actionIndex($params);
            $this->preloadGroupBadges($response);

            return $response;
        }

        try {
            $response = parent::actionIndex($params);
            $this->preloadGroupBadges($response);
        } catch (Exception $e) {
            if ($e->getReply()->getResponseCode() === 403) {
                $thread = $this->em()->find('XF:Thread', $params->thread_id);
                $groupId = App::getGroupIdFromEntity($thread);

                if ($groupId > 0) {
                    /** @var Group $group */
                    $group = $this->em()->find('Truonglv\Groups:Group', $groupId);
                    // the forum belong to group
                    // throw our friendly error
                    throw $this->exception($this->error(XF::phrase('tlg_you_need_become_a_member_of_the_group_x_to_view_the_content', [
                        'title' => $group->name,
                        'url' => $this->app()->router('public')->buildLink('groups', $group)
                    ])));
                }
            }

            throw $e;
        }

        if ($response instanceof View) {
            /** @var \XF\Entity\Thread|null $thread */
            $thread = $response->getParam('thread');
            if ($thread === null) {
                return $response;
            }

            /** @var \Truonglv\Groups\XF\Entity\Forum|null $forum */
            $forum = $thread->Forum;
            $groupId = App::getGroupIdFromEntity($forum);

            if ($groupId > 0) {
                // To prevent XF cache entities. Do fetch it from database source.
                /** @var Group|null $group */
                $group = $this->finder('Truonglv\Groups:Group')
                    ->with('full')
                    ->whereId($groupId)
                    ->fetchOne();
                if ($group === null) {
                    return $response;
                }

                if (!$group->canViewContent()) {
                    throw $this->exception($this->noPermission());
                }

                $response->setTemplateName('tlg_thread_view');
                $response->setParam('group', $group);

                Listener::addContentLanguageResponseHeader($group);
                $this->setSectionContext('tl_groups');

                App::groupRepo()->logView($group);

                $response->setPageParam(App::KEY_PAGE_PARAMS_GROUP, $group);
            }
        }

        return $response;
    }

    protected function preloadGroupBadges(AbstractReply $response): void
    {
        if (!$response instanceof View) {
            return;
        }

        if (App::isEnabledBadge(XF::visitor())) {
            $badgeGroupIds = [];
            /** @var Post $post */
            foreach ($response->getParam('posts') as $post) {
                /** @var User|null $user */
                $user = $post->User;
                if ($user !== null && $user->tlg_badge_group_id > 0) {
                    $badgeGroupIds[] = $user->tlg_badge_group_id;
                }
            }
            /** @var Badge $badgeData */
            $badgeData = $this->data('Truonglv\Groups:Badge');
            $badgeData->setBadgeGroupIds($badgeGroupIds);
        }
    }

    /**
     * @return array
     */
    protected function getThreadViewExtraWith()
    {
        $with = parent::getThreadViewExtraWith();
        if (App::isEnabledForums()) {
            $with[] = 'Forum.GroupForum';
        }

        return $with;
    }

    public function actionMove(ParameterBag $params)
    {
        if (App::isEnabledForums()) {
            App::$isAppendGroupNameIntoNodeTitle = true;
        } else {
            return parent::actionMove($params);
        }

        /** @var \Truonglv\Groups\XF\Entity\Thread $thread */
        $thread = $this->assertViewableThread($params['thread_id']);
        /** @var \Truonglv\Groups\Entity\Forum|null $groupForum */
        $groupForum = $this->em()->find('Truonglv\Groups:Forum', $thread->node_id);
        $visitor = XF::visitor();

        if ($groupForum === null || $visitor->hasNodePermission($thread->node_id, 'manageAnyThread')) {
            return parent::actionMove($params);
        }

        // group owner permissions
        App::$isAppendGroupNameIntoNodeTitle = false;
        $groupNodeIds = $this->finder('Truonglv\Groups:Forum')
            ->where('group_id', $groupForum->group_id)
            ->fetchColumns('node_id');
        $groupNodeIds = array_column($groupNodeIds, 'node_id');

        if ($this->isPost()) {
            // verify target_node_id
            $targetNodeId = $this->filter('target_node_id', 'uint');
            if (!in_array($targetNodeId, $groupNodeIds, true)) {
                return $this->error(XF::phrase('requested_forum_not_found'));
            }
        }

        $response = parent::actionMove($params);
        if ($response instanceof View) {
            /** @var Tree $nodeTree */
            $nodeTree = $response->getParam('nodeTree');
            $nodes = $nodeTree->getAllData();

            foreach ($nodes as $nodeId => $nodeRef) {
                if (!in_array($nodeId, $groupNodeIds, true)) {
                    unset($nodes[$nodeId]);
                }
            }

            $response->setParam('nodeTree', new Tree($nodes, 'parent_node_id'));
            $response->setParam('tlgNodes', $nodes);
        }

        return $response;
    }
}
