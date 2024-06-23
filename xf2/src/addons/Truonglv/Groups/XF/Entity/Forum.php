<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */
 
namespace Truonglv\Groups\XF\Entity;

use XF;
use Truonglv\Groups\App;
use XF\Mvc\Entity\Structure;
use Truonglv\Groups\Entity\UserCache;
use Truonglv\Groups\Option\GroupNodeCache;

/**
 * Class Forum
 * @package Truonglv\Groups\XF\Entity
 *
 * @inheritdoc
 * @property Node $Node
 * @property \Truonglv\Groups\Entity\Forum|null $GroupForum
 */
class Forum extends XFCP_Forum
{
    public static function getStructure(Structure $structure)
    {
        $structure = parent::getStructure($structure);

        if (App::isEnabledForums()) {
            $structure->relations['GroupForum'] = [
                'type' => self::TO_ONE,
                'entity' => 'Truonglv\Groups:Forum',
                'conditions' => 'node_id',
                'primary' => true
            ];

            $structure->behaviors['Truonglv\Groups:Activity'] = [
                'checkForUpdates' => [
                    'last_post_id',
                    'last_thread_id'
                ],
                'groupIdField' => function (\XF\Entity\Forum $entity) {
                    /** @var \Truonglv\Groups\Entity\Forum|null $groupForum  */
                    $groupForum = $entity->getRelation('GroupForum');

                    return $groupForum !== null ? $groupForum->group_id : 0;
                }
            ];
        }

        return $structure;
    }

    /**
     * @return array
     */
    public static function getListedWith()
    {
        $with = parent::getListedWith();

        if (App::isEnabledForums()) {
            $with[] = 'GroupForum.Group.fullView';
        }

        return $with;
    }

    /**
     * @param mixed $error
     * @return bool
     */
    public function canView(& $error = null)
    {
        $allowed = parent::canView($error);
        if (!$allowed) {
            return false;
        }

        if (App::isEnabledForums()) {
            $groupId = GroupNodeCache::getGroupId($this->node_id);

            if ($groupId <= 0) {
                return true;
            }

            /** @var UserCache|null $userCache */
            $userCache = XF::visitor()->getRelation('TLGUserCache');
            if ($userCache === null || !$userCache->canViewGroupContent($groupId, $error)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return bool
     */
    public function canViewDeletedThreads()
    {
        if (App::hasThreadPostPermission('viewDeleted', $this)) {
            return true;
        }

        return parent::canViewDeletedThreads();
    }

    /**
     * @return bool
     */
    public function canViewModeratedThreads()
    {
        if (App::hasThreadPostPermission('viewModerated', $this)) {
            return true;
        }

        return parent::canViewModeratedThreads();
    }

    /**
     * @return \Truonglv\Groups\Entity\Forum
     */
    public function getNewGroupForum()
    {
        /** @var \Truonglv\Groups\Entity\Forum $forum */
        $forum = $this->em()->create('Truonglv\Groups:Forum');
        $forum->node_id = $this->_getDeferredValue(function () {
            return $this->node_id;
        }, 'save');

        $this->addCascadedSave($forum);

        return $forum;
    }

    /**
     * @param \XF\Entity\Thread $thread
     * @return void
     */
    public function threadAdded(\XF\Entity\Thread $thread)
    {
        parent::threadAdded($thread);

        $group = App::getGroupEntityFromEntity($this);
        if ($group !== null && $this->isChanged('discussion_count')) {
            $group->onThreadCreated($thread);
            $group->saveIfChanged();
        }
    }

    /**
     * @param \XF\Entity\Thread $thread
     * @return void
     */
    public function threadRemoved(\XF\Entity\Thread $thread)
    {
        parent::threadRemoved($thread);

        $group = App::getGroupEntityFromEntity($this);
        if ($group !== null && $this->isChanged('discussion_count')) {
            $group->onThreadRemoved($thread);
            $group->saveIfChanged();
        }
    }

    protected function _postDelete()
    {
        parent::_postDelete();

        $db = $this->db();
        $groupIds = $db->fetchAllColumn('
            SELECT `group_id`
            FROM `xf_tl_group_forum`
            WHERE `node_id` = ?
        ', $this->node_id);
        foreach ($groupIds as $groupId) {
            $db->query('
                UPDATE `xf_tl_group`
                SET `node_count` = GREATEST(`node_count` - 1, 0)
                WHERE `group_id` = ?
            ', $groupId);
        }
        $db->delete('xf_tl_group_forum', 'node_id = ?', $this->node_id);
        GroupNodeCache::rebuildCache();

        if ($this->discussion_count > 0
            && App::isEnabledForums()
        ) {
            $groupForum = $this->getRelation('GroupForum');
            if ($groupForum === null) {
                return;
            }

            $db->query('
                UPDATE `xf_tl_group`
                SET `discussion_count` = GREATEST(`discussion_count` - ?, 0)
                WHERE `group_id` = ?
            ', [$this->discussion_count, $groupForum->group_id]);
        }
    }
}
