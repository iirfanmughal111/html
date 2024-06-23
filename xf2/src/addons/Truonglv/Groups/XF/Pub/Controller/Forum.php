<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\XF\Pub\Controller;

use XF;
use XF\Mvc\Reply\View;
use XF\Mvc\Reply\Error;
use Truonglv\Groups\App;
use XF\Mvc\ParameterBag;
use XF\Mvc\Reply\Exception;
use Truonglv\Groups\Listener;
use Truonglv\Groups\Entity\Group;

class Forum extends XFCP_Forum
{
    public function actionList(ParameterBag $params)
    {
        if (App::isEnabledForums()) {
            App::$isAppendGroupNameIntoNodeTitle = trim(App::getOption('nodeTitleFormat')) !== '';
        }

        return parent::actionList($params);
    }

    public function actionForum(ParameterBag $params)
    {
        if (!App::isEnabledForums()) {
            return parent::actionForum($params);
        }

        try {
            $response = parent::actionForum($params);
        } catch (Exception $e) {
            if ($e->getReply()->getResponseCode() === 403) {
                if ($params->node_id > 0) {
                    $forum = $this->em()->find('XF:Forum', $params->node_id);
                } else {
                    $finder = $this->finder('XF:Forum');
                    $finder->where(['Node.node_name' => $params->node_name, 'Node.node_type_id' => 'Forum']);

                    $forum = $finder->fetchOne();
                }

                $groupId = App::getGroupIdFromEntity($forum);
                if ($forum !== null
                    && $groupId > 0
                ) {
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

            // unknown exception type
            throw $e;
        }

        if ($response instanceof View) {
            /** @var \Truonglv\Groups\XF\Entity\Forum|null $forum */
            $forum = $response->getParam('forum');
            if ($forum === null) {
                return $response;
            }

            $groupId = App::getGroupIdFromEntity($forum);
            if ($groupId > 0) {
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

                $response->setTemplateName('tlg_forum_view');
                $response->setParam('group', $group);

                Listener::addContentLanguageResponseHeader($group);
                $this->setSectionContext('tl_groups');

                App::groupRepo()->logView($group);

                $response->setPageParam(App::KEY_PAGE_PARAMS_GROUP, $group);
            }
        }

        return $response;
    }

    public function actionPostThread(ParameterBag $params)
    {
        $response = parent::actionPostThread($params);
        if ($response instanceof View
            && $response->getTemplateName() === 'forum_post_thread'
        ) {
            $forum = $response->getParam('forum');
            $groupId = App::getGroupIdFromEntity($forum);
            if ($groupId <= 0) {
                return $response;
            }

            /** @var Group|null $group */
            $group = $this->finder('Truonglv\Groups:Group')
                ->with('full')
                ->whereId($groupId)
                ->fetchOne();
            if ($group === null) {
                return $response;
            }

            $response->setTemplateName('tlg_forum_post_thread');
            $response->setParam('group', $group);
        }

        return $response;
    }

    /**
     * @return array
     */
    protected function getForumViewExtraWith()
    {
        $with = parent::getForumViewExtraWith();

        if (App::isEnabledForums()) {
            $with[] = 'GroupForum';
        }

        return $with;
    }
}
