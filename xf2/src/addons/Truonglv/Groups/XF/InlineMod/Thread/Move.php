<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\XF\InlineMod\Thread;

use XF;
use Truonglv\Groups\App;
use XF\Mvc\Entity\Entity;
use Truonglv\Groups\XF\Entity\Thread;
use XF\Mvc\Entity\AbstractCollection;

/**
 * Class Move
 * @package Truonglv\Groups\XF\InlineMod\Thread
 * @inheritdoc
 */
class Move extends XFCP_Move
{
    /**
     * @param Entity $entity
     * @param array $options
     * @param mixed $error
     * @return bool
     */
    protected function canApplyToEntity(Entity $entity, array $options, & $error = null)
    {
        $result = parent::canApplyToEntity($entity, $options, $error);
        if (!$result) {
            return false;
        }

        $visitor = XF::visitor();
        /** @var Thread $thread */
        $thread = $entity;
        if ($visitor->hasNodePermission($thread->node_id, 'manageAnyThread')) {
            // moderator
            return $result;
        }

        /** @var \Truonglv\Groups\Entity\Forum|null $groupForum */
        $groupForum = XF::em()->find('Truonglv\Groups:Forum', $thread->node_id);
        if ($groupForum === null) {
            return $result;
        }

        $groupNodeIds = XF::finder('Truonglv\Groups:Forum')
            ->where('group_id', $groupForum->group_id)
            ->fetchColumns('node_id');
        $groupNodeIds = array_column($groupNodeIds, 'node_id');
        $targetNodeId = $options['target_node_id'];

        // group admin can move thread. Make sure they only move group forums.
        return in_array($targetNodeId, $groupNodeIds, true);
    }

    /**
     * @param AbstractCollection $entities
     * @param \XF\Mvc\Controller $controller
     * @return \XF\Mvc\Reply\View|null
     */
    public function renderForm(AbstractCollection $entities, \XF\Mvc\Controller $controller)
    {
        if (App::isEnabledForums()) {
            App::$isAppendGroupNameIntoNodeTitle = true;
        }

        return parent::renderForm($entities, $controller);
    }
}
