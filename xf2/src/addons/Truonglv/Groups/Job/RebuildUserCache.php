<?php

namespace Truonglv\Groups\Job;

use XF;
use function json_encode;
use function array_unshift;
use XF\Job\AbstractRebuildJob;
use Truonglv\Groups\Entity\UserCache;

class RebuildUserCache extends AbstractRebuildJob
{
    /**
     * @var array
     */
    public static $fetchColumns = [
        'user_id',
        'member_state',
        'member_role_id',
        'Group.group_id',
        'Group.privacy',
        'Group.group_state',
    ];

    /**
     * @throws \XF\Db\Exception
     * @return void
     */
    public static function rebuildForGuest()
    {
        $db = XF::db();
        $results = $db->fetchAll('
            SELECT group_id, privacy, group_state
            FROM xf_tl_group
            ORDER BY group_id
        ');

        $data = [];
        foreach ($results as $result) {
            $data[$result['group_id']] = UserCache::getUserCacheFromData($result);
        }

        static::executeQueryUpdate(UserCache::GUEST_USER_ID, $data);
    }

    /**
     * @param int $userId
     * @param array $cache
     * @throws \XF\Db\Exception
     * @return void
     */
    public static function executeQueryUpdate($userId, array $cache)
    {
        $db = XF::db();

        $db->query('
            INSERT IGNORE INTO `xf_tl_group_user_cache`
                (`user_id`, `cache_data`)
            VALUES
                (?, ?)
            ON DUPLICATE KEY UPDATE
                `cache_data` = VALUES(`cache_data`)
        ', [
            $userId,
            json_encode($cache)
        ]);
    }

    /**
     * @param mixed $start
     * @param mixed $batch
     * @return array
     */
    protected function getNextIds($start, $batch)
    {
        $userIds = $this->app->db()->fetchAllColumn($this->app->db()->limit('
            SELECT `user_id`
            FROM `xf_user`
            WHERE `user_id` > ?
            ORDER BY `user_id`
        ', $batch), $start);

        if ($start == UserCache::GUEST_USER_ID) {
            array_unshift($userIds, $start);
        }

        return $userIds;
    }

    /**
     * @param mixed $id
     * @throws \XF\Db\Exception
     * @return void
     */
    protected function rebuildById($id)
    {
        if ($id == UserCache::GUEST_USER_ID) {
            // specific rebuild for GUEST user
            static::rebuildForGuest();

            return;
        }

        $data = [];
        $db = $this->app->db();

        $finder = $this->app->finder('Truonglv\Groups:Member');
        $finder->with('Group', true);
        $finder->where('user_id', $id);
        $finder->order('joined_date');

        $stmt = $db->query($finder->getQuery([
            'fetchOnly' => static::$fetchColumns
        ]));
        while ($row = $stmt->fetch()) {
            $data[$row['group_id']] = UserCache::getUserCacheFromData($row);
        }

        static::executeQueryUpdate($id, $data);
    }

    /**
     * @return \XF\Phrase
     */
    protected function getStatusType()
    {
        return XF::phrase('users');
    }
}
