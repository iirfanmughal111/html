<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Repository;

use XF;
use function count;
use LogicException;
use XF\Entity\User;
use function intval;
use function strval;
use XF\Entity\Forum;
use function shuffle;
use function in_array;
use function array_fill;
use function array_keys;
use Truonglv\Groups\App;
use function is_callable;
use function array_splice;
use function array_unique;
use function array_replace;
use XF\Mvc\Entity\Repository;

class Group extends Repository
{
    /**
     * @return array
     */
    public function getAllowedPrivacy()
    {
        return [App::PRIVACY_PUBLIC, App::PRIVACY_CLOSED, App::PRIVACY_SECRET];
    }

    /**
     * @return array
     */
    public function getVisiblePrivacy()
    {
        return [App::PRIVACY_PUBLIC, App::PRIVACY_CLOSED];
    }

    /**
     * @return array
     */
    public function getAvailableGroupSorts()
    {
        return [
            'name' => 'name',
            'member_count' => 'member_count',
            'event_count' => 'event_count',
            'view_count' => 'view_count',
            'discussion_count' => 'discussion_count',
            'last_activity' => 'last_activity',
            'created_date' => 'created_date'
        ];
    }

    /**
     * @param array|null $viewableCategoryIds
     * @param array $limits
     * @return \Truonglv\Groups\Finder\Group
     */
    public function findGroupsForOverviewList(array $viewableCategoryIds = null, array $limits = [])
    {
        $limits = array_replace([
            'visibility' => true,
            'allowOwnPending' => true,
            'privacyChecks' => false,
        ], $limits);

        $groupFinder = App::groupFinder();

        if ($viewableCategoryIds !== null && count($viewableCategoryIds) > 0) {
            $groupFinder->where('category_id', $viewableCategoryIds);
        }

        $groupFinder
            ->with('full')
            ->useDefaultOrder();

        $visitor = XF::visitor();
        if ($visitor->user_id > 0) {
            $groupFinder->with('Views|' . $visitor->user_id);
        }

        if ($limits['visibility'] === true) {
            $groupFinder->applyGlobalVisibilityChecks($limits['allowOwnPending']);
        }
        if ($limits['privacyChecks'] === true) {
            $groupFinder->applyGlobalPrivacyChecks();
        }

        return $groupFinder;
    }

    /**
     * @param mixed $nodes
     * @return void
     */
    public function loadGroupsForNodes($nodes)
    {
        if (!App::isEnabledForums()) {
            return;
        }

        static $loadedNodeIds = [];

        $nodeIds = [];
        /** @var \XF\Entity\Node $node */
        foreach ($nodes as $node) {
            if ($node->node_type_id === App::NODE_TYPE_ID
                && !in_array($node->node_id, $loadedNodeIds, true)
            ) {
                $nodeIds[] = $node->node_id;
            }
        }

        $groupForums = $this->em->getEmptyCollection();
        if (count($nodeIds) > 0) {
            $groupForums = $this->finder('Truonglv\Groups:Forum')
                ->with('Group.fullView')
                ->where('node_id', $nodeIds)
                ->fetch();
        }

        /** @var \Truonglv\Groups\XF\Entity\Node $node */
        foreach ($nodes as $node) {
            /** @var mixed $callable */
            $callable = [$node, 'setTlgGroupEntity'];
            if ($node->node_type_id !== App::NODE_TYPE_ID
                || in_array($node->node_id, $loadedNodeIds, true)
                || !is_callable($callable)
            ) {
                continue;
            }

            $loadedNodeIds[] = $node->node_id;

            /** @var Forum $nodeData */
            $nodeData = $node->getDataRelationOrDefault();
            if (isset($groupForums[$node->node_id])
                && $groupForums[$node->node_id]->Group
            ) {
                $node->setTlgGroupEntity($groupForums[$node->node_id]->Group);
                $nodeData->hydrateRelation('GroupForum', $groupForums[$node->node_id]);
            } else {
                $node->setTlgGroupEntity(null);
                $nodeData->hydrateRelation('GroupForum', null);
            }
        }
    }

    /**
     * @param \XF\Mvc\Entity\AbstractCollection $groups
     * @param int $limit
     * @param bool $forceUsage
     * @return void
     */
    public function addMembersIntoGroups($groups, $limit = 0, $forceUsage = false)
    {
        $limitMembers = $limit > 0 ? $limit : App::getOption('maxMembersInCard');
        if ($limitMembers < 1 && $forceUsage === false) {
            return;
        }

        $memberIdsMap = [];
        $visitor = XF::visitor();

        /** @var \Truonglv\Groups\Entity\Group $group */
        foreach ($groups as $group) {
            $members = $group->member_cache;
            $memberIds = [];

            foreach ($members as $member) {
                if (!in_array($member['member_state'], App::memberRepo()->getValidMemberStates(), true)) {
                    continue;
                }

                if ($visitor->Profile !== null && $visitor->Profile->isFollowing($member['user_id'])) {
                    $memberIds = array_fill(count($memberIds), 10, $member['member_id']);
                } else {
                    $memberIds[] = $member['member_id'];
                }
            }

            shuffle($memberIds);
            $memberIds = array_unique($memberIds);

            if (count($memberIds) > $limitMembers) {
                $memberIds = array_splice($memberIds, 0, $limitMembers);
            }

            foreach ($memberIds as $memberId) {
                $memberIdsMap[$memberId] = $group->group_id;
            }
        }

        if (count($memberIdsMap) === 0) {
            $members = $this->em->getEmptyCollection();
        } else {
            $members = App::memberFinder()
                ->with('User')
                ->whereIds(array_keys($memberIdsMap))
                ->order('joined_date', 'desc')
                ->fetch()
                ->groupBy('group_id');
        }

        foreach ($groups as $group) {
            if (isset($members[$group->group_id])) {
                $memberList = [];
                foreach ($members[$group->group_id] as $member) {
                    $memberList[$member->user_id] = $member;
                }
                $memberCollection = $this->em->getBasicCollection($memberList);
            } else {
                $memberCollection = $this->em->getEmptyCollection();
            }
            $visitorMember = $group->Member;
            if ($visitorMember !== null) {
                $memberCollection[$visitorMember->user_id] = $visitorMember;
            }

            $group->setCardMembers($memberCollection);
        }
    }

    /**
     * @return void
     */
    public function pruneExpiredFeatureGroups()
    {
        $features = XF::finder('Truonglv\Groups:Feature')
            ->with('Group')
            ->where('expire_date', 'BETWEEN', [1, XF::$time])
            ->fetch();

        foreach ($features as $feature) {
            $feature->delete();
        }
    }

    /**
     * @throws \XF\Db\Exception
     * @return void
     */
    public function batchUpdateGroupViews()
    {
        $db = $this->db();

        $db->query('
			UPDATE xf_tl_group AS g
			INNER JOIN xf_tl_group_view_log AS vl ON (g.group_id = vl.group_id)
			SET g.view_count = g.view_count + vl.total
		');

        $db->emptyTable('xf_tl_group_view_log');
    }

    public function getGroupNodeIds(): array
    {
        return $this->db()->fetchAllColumn('
            SELECT DISTINCT(`node_id`)
            FROM `xf_tl_group_forum`
        ');
    }

    /**
     * @param int $groupId
     * @param int $activityDate
     * @return void
     */
    public function logGroupActivity($groupId, $activityDate = 0)
    {
        $this->db()
            ->insert('xf_tl_group_activity', [
                'group_id' => $groupId,
                'activity_date' => $activityDate > 0 ? $activityDate : XF::$time
            ], true);
    }

    /**
     * @return void
     */
    public function cleanUpInactiveGroups()
    {
        $days = (int) App::getOption('cleanUpInactiveXDays');
        if ($days <= 0) {
            return;
        }

        $groups = $this->finder('Truonglv\Groups:Group')
            ->where('group_state', 'visible')
            ->where('last_activity', '<=', XF::$time - $days * 86400)
            ->fetch();

        /** @var \Truonglv\Groups\Entity\Group $group */
        foreach ($groups as $group) {
            $group->group_state = 'deleted';
            $group->save();

            $managers = App::memberRepo()->getManagers($group);
            /** @var \Truonglv\Groups\Entity\Member $manager */
            foreach ($managers as $manager) {
                /** @var User|null $user */
                $user = $manager->User;
                if ($user === null) {
                    continue;
                }

                App::alert(
                    $user,
                    0,
                    'Guest',
                    'user',
                    $user->user_id,
                    'tlg_group_inactive',
                    [
                        'groupId' => $group->group_id,
                        'groupName' => $group->name,
                        'days' => $days
                    ]
                );
            }
        }
    }

    /**
     * @param \Truonglv\Groups\Entity\Group|int $group
     * @throws \XF\Db\Exception
     * @return void
     */
    public function logView($group)
    {
        /** @var \Truonglv\Groups\Entity\Group|null $groupEntity */
        $groupEntity = null;
        if ($group instanceof \Truonglv\Groups\Entity\Group) {
            $groupId = $group->group_id;
            $groupEntity = $group;
        } else {
            $groupId = intval(strval($group));
        }

        if ($groupId < 1) {
            throw new LogicException('Unspecified group to log.');
        }

        $db = $this->db();
        $db->query('
            INSERT INTO `xf_tl_group_view_log`
                (`group_id`, `total`)
            VALUES
                (?, 1)
            ON DUPLICATE KEY UPDATE
                `total` = `total` + 1
        ', [$groupId]);

        // also update last user view group
        $visitor = XF::visitor();
        if ($visitor->user_id > 0 && $groupEntity !== null && $groupEntity->Member !== null) {
            $db->query('
                INSERT INTO `xf_tl_group_view`
                    (`group_id`, `user_id`, `view_date`)
                VALUES
                    (?, ?, ?)
                ON DUPLICATE KEY UPDATE
                    `view_date` = VALUES(`view_date`)
            ', [$groupEntity->group_id, $visitor->user_id, XF::$time]);
        }
    }

    /**
     * @param User $user
     * @param int $adjust
     * @return void
     */
    public function adjustUserOwnGroupsCount(User $user, $adjust)
    {
        $db = $this->db();

        $db->query('
            UPDATE `xf_user`
            SET `tlg_total_own_groups` = GREATEST(0, `tlg_total_own_groups` + ?)
            WHERE `user_id` = ?
        ', [$adjust, $user->user_id]);
    }
}
