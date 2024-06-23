<?php

namespace Truonglv\Groups\Service\Group;

use XF;
use function count;
use XF\Entity\User;
use function is_array;
use function array_keys;
use Truonglv\Groups\App;
use function array_merge;
use InvalidArgumentException;
use XF\Service\AbstractService;
use Truonglv\Groups\Entity\Group;
use XF\Mvc\Entity\AbstractCollection;

class Merger extends AbstractService
{
    /**
     * @var Group
     */
    protected $target;

    /**
     * @var array
     */
    protected $sourceGroups;
    /**
     * @var array
     */
    protected $sourceComments;
    /**
     * @var array
     */
    protected $sourceEvents;
    /**
     * @var array
     */
    protected $sourceUsers;

    /**
     * @var string
     */
    protected $alertType = 'none';

    public function __construct(\XF\App $app, Group $target)
    {
        parent::__construct($app);

        $this->target = $target;
    }

    /**
     * @param string $alertType
     * @return void
     */
    public function setAlertType(string $alertType)
    {
        $this->alertType = $alertType;
    }

    /**
     * @param mixed $sourceRaw
     * @return bool
     * @throws \XF\PrintableException
     */
    public function merge($sourceRaw)
    {
        if ($sourceRaw instanceof AbstractCollection) {
            $sourceRaw = $sourceRaw->toArray();
        } elseif ($sourceRaw instanceof Group) {
            $sourceRaw = [$sourceRaw];
        } elseif (!is_array($sourceRaw)) {
            throw new InvalidArgumentException('Groups must be collection, array or entity');
        }

        if (count($sourceRaw) === 0) {
            return false;
        }

        $db = $this->db();
        $db->beginTransaction();

        $sourceGroups = [];
        $sourceUsers = [];

        /** @var Group $group */
        foreach ($sourceRaw as $group) {
            $sourceGroups[$group->group_id] = $group;

            if ($this->alertType === 'admin') {
                $sourceUsers[$group->group_id] = $db->fetchAllColumn('
                    SELECT `user_id`
                    FROM `xf_tl_group_member`
                    WHERE `group_id` = ? AND (`member_role_id` = ? OR `member_role_id` = ?)
                ', [
                    $group->group_id,
                    App::MEMBER_ROLE_ID_ADMIN,
                    App::MEMBER_ROLE_ID_MODERATOR
                ]);
            } elseif ($this->alertType === 'all') {
                $sourceUsers[$group->group_id] = $db->fetchAllColumn('
                    SELECT `user_id`
                    FROM `xf_tl_group_member`
                    WHERE `member_state` = ?
                ', App::MEMBER_STATE_VALID);
            }
        }
        $this->sourceGroups = $sourceGroups;
        $this->sourceUsers = $sourceUsers;

        if (isset($this->sourceGroups[$this->target->group_id])) {
            throw new InvalidArgumentException('Cannot merge a same group!');
        }

        $groupIdsQuoted = $db->quote(array_keys($sourceGroups));

        $postIds = $db->fetchAllColumn("
            SELECT `post_id`
            FROM `xf_tl_group_post`
            WHERE `group_id` IN ($groupIdsQuoted)
        ");
        $eventIds = $db->fetchAllColumn("
            SELECT `event_id`
            FROM `xf_tl_group_event`
            WHERE `group_id` IN ({$groupIdsQuoted})
        ");
        $this->sourceEvents = $eventIds;

        $sourceComments = [];
        if (count($postIds) > 0) {
            $sourceComments = array_merge($sourceComments, $db->fetchAllColumn("
                SELECT `comment_id`
                FROM `xf_tl_group_comment`
                WHERE `content_type` = 'post' AND `content_id` IN ({$db->quote($postIds)})
            "));
        }
        if (count($eventIds) > 0) {
            $sourceComments = array_merge($sourceComments, $db->fetchAllColumn("
                SELECT `comment_id`
                FROM `xf_tl_group_comment`
                WHERE `content_type` = 'event' AND `content_id` IN ({$db->quote($eventIds)})
            "));
        }
        $this->sourceComments = $sourceComments;

        $this->migrateDataToTarget();

        if ($this->alertType === 'admin' || $this->alertType === 'all') {
            $this->sendAlert();
        }

        $this->cleanUpSource();

        foreach ($sourceRaw as $group) {
            $group->delete();
        }

        $this->finalActions();

        $db->commit();

        return true;
    }

    /**
     * @return void
     */
    protected function sendAlert()
    {
        $sourceUsers = $this->sourceUsers;
        if (count($sourceUsers) === 0) {
            return;
        }

        $userIds = [];
        foreach ($sourceUsers as $ids) {
            $userIds = array_merge($userIds, $ids);
        }

        $visitor = XF::visitor();

        /** @var User[] $users */
        $users = $this->em()->findByIds('XF:User', $userIds, ['Profile', 'Option']);
        foreach ($sourceUsers as $groupId => $ids) {
            foreach ($ids as $userId) {
                if (!isset($users[$userId])) {
                    continue;
                }

                $userRef = $users[$userId];
                if ($userRef->user_id === $visitor->user_id) {
                    continue;
                }

                /** @var Group $groupRef */
                $groupRef = $this->sourceGroups[$groupId];

                App::alert(
                    $userRef,
                    $visitor->user_id,
                    $visitor->username,
                    App::CONTENT_TYPE_GROUP,
                    $this->target->group_id,
                    'merge',
                    ['name' => $groupRef->name]
                );
            }
        }
    }

    /**
     * @return void
     */
    protected function migrateDataToTarget()
    {
        $target = $this->target;

        $db = $this->db();

        $groupIds = array_keys($this->sourceGroups);
        $groupIdsQuoted = $db->quote($groupIds);

        $db->update(
            'xf_tl_group_event',
            ['group_id' => $target->group_id],
            "group_id IN ({$groupIdsQuoted})"
        );

        $db->update(
            'xf_tl_group_post',
            ['group_id' => $target->group_id],
            "group_id IN ({$groupIdsQuoted})"
        );

        $db->update(
            'xf_tl_group_view',
            ['group_id' => $target->group_id],
            "group_id IN ({$groupIdsQuoted})",
            [],
            'IGNORE'
        );
        $db->update(
            'xf_tl_group_mg_album',
            ['group_id' => $target->group_id],
            "group_id IN ({$groupIdsQuoted})",
            [],
            'IGNORE'
        );
        $db->update(
            'xf_tl_group_forum',
            ['group_id' => $target->group_id],
            "group_id IN ({$groupIdsQuoted})",
            [],
            'IGNORE'
        );
        $db->update(
            'xf_tl_group_feature',
            ['group_id' => $target->group_id],
            "group_id IN ({$groupIdsQuoted})",
            [],
            'IGNORE'
        );
        $db->update(
            'xf_tl_group_member',
            ['group_id' => $target->group_id],
            "group_id IN ({$groupIdsQuoted})",
            [],
            'IGNORE'
        );
    }

    /**
     * @return void
     */
    protected function cleanUpSource()
    {
        $this->app->search()->delete(App::CONTENT_TYPE_GROUP, array_keys($this->sourceGroups));
    }

    /**
     * @return void
     */
    protected function finalActions()
    {
        $commentIds = $this->sourceComments;
        if (count($commentIds) > 0) {
            $this->app->jobManager()->enqueue('XF:SearchIndex', [
                'content_type' => App::CONTENT_TYPE_COMMENT,
                'content_ids' => $commentIds
            ]);
        }

        $eventIds = $this->sourceEvents;
        if (count($eventIds) > 0) {
            $this->app->jobManager()->enqueue('XF:SearchIndex', [
                'content_type' => App::CONTENT_TYPE_EVENT,
                'content_ids' => $eventIds
            ]);
        }

        $this->app
            ->jobManager()
            ->enqueueLater(
                'GroupMergeRebuild' . $this->target->group_id,
                XF::$time,
                'Truonglv\Groups:GroupItemRebuild',
                [
                    'groupId' => $this->target->group_id
                ]
            );
    }
}
