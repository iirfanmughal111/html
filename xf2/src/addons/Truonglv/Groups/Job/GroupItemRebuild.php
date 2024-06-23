<?php

namespace Truonglv\Groups\Job;

use XF;
use Exception;
use function round;
use XF\Job\JobResult;
use function is_array;
use XF\Job\AbstractJob;
use function var_export;
use Truonglv\Groups\App;
use function array_merge;
use function json_decode;
use function array_replace;
use Truonglv\Groups\Entity\Group;
use Truonglv\Groups\Entity\UserCache;

class GroupItemRebuild extends AbstractJob
{
    /**
     * @var array
     */
    protected $defaultData = [
        'groupId' => 0,

        'rebuildCounters' => false,
        'rebuildCache' => false,
        'rebuildUserCache' => false,
    ];

    /**
     * @param int $maxRunTime
     * @return JobResult
     * @throws \XF\Db\Exception
     */
    public function run($maxRunTime)
    {
        /** @var Group|null $group */
        $group = $this->app->em()->find('Truonglv\Groups:Group', $this->data['groupId']);
        if ($group === null) {
            return $this->complete();
        }

        $start = microtime(true);
        static::rebuild($group, $this->data);
        $timeLapsed = microtime(true) - $start;

        if ($maxRunTime > 0 && $timeLapsed > $maxRunTime) {
            // process too long.
            XF::logException(
                new Exception(
                    'Group rebuild time exceeded. $timing='
                    . round($timeLapsed, 2)
                    . ' $options=' . var_export($this->data, true)
                ),
                false,
                '[tl] Groups: '
            );
        }

        return $this->complete();
    }

    /**
     * @return \XF\Phrase
     */
    public function getStatusMessage()
    {
        return XF::phrase('tlg_groups');
    }

    /**
     * @return bool
     */
    public function canCancel()
    {
        return false;
    }

    /**
     * @return bool
     */
    public function canTriggerByChoice()
    {
        return false;
    }

    /**
     * @param Group $group
     * @param array $options
     * @return void
     * @throws \XF\Db\Exception
     */
    public static function rebuild(Group $group, array $options = [])
    {
        $options = array_replace([
            'rebuildCounters' => false,
            'rebuildCache' => false,
            'rebuildUserCache' => false,
        ], $options);

        $rebuildCache = (bool) $options['rebuildCache'];
        if ($rebuildCache) {
            // saving recent members
            $group->rebuildMemberCache();

            App::fieldRepo()->rebuildGroupFieldValuesCache($group->group_id);
        }

        $rebuildCounters = (bool) $options['rebuildCounters'];
        if ($rebuildCounters) {
            $group->rebuildCounters();
        }

        $rebuildUserCache = (bool) $options['rebuildUserCache'];
        if ($rebuildUserCache) {
            RebuildUserCache::rebuildForGuest();

            $db = XF::db();

            $finder = XF::finder('Truonglv\Groups:Member');
            $finder->with('UserCache');
            $finder->where('group_id', $group->group_id);
            $finder->order('joined_date');

            $stmt = $db->query($finder->getQuery([
                'fetchOnly' => array_merge(RebuildUserCache::$fetchColumns, ['UserCache.cache_data'])
            ]));
            while ($row = $stmt->fetch()) {
                $cache = json_decode($row['cache_data'], true);
                if (!is_array($cache)) {
                    $cache = [];
                }
                unset($row['cache_data']);

                $cache[$group->group_id] = UserCache::getUserCacheFromData(array_merge($row, $group->toArray(false)));
                RebuildUserCache::executeQueryUpdate($row['user_id'], $cache);
            }
        }

        $group->saveIfChanged();
    }
}
