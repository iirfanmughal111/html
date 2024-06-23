<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Job;

use XF;
use function array_unshift;
use XF\Job\AbstractRebuildJob;
use Truonglv\Groups\Option\GroupNodeCache;

class GroupRebuild extends AbstractRebuildJob
{
    /**
     * @var array
     */
    protected $defaultData = [
        'counter' => true,
    ];

    /**
     * @param mixed $start
     * @param mixed $batch
     * @return array
     */
    protected function getNextIds($start, $batch)
    {
        $db = $this->app->db();

        $groupIds = $db->fetchAllColumn($db->limit('
            SELECT `group_id`
            FROM `xf_tl_group`
            WHERE `group_id` > ?
            ORDER BY `group_id`
        ', $batch), $start);

        if ($start == 0) {
            array_unshift($groupIds, $start);
        }

        return $groupIds;
    }

    /**
     * @return \XF\Phrase
     */
    protected function getStatusType()
    {
        return XF::phrase('tlg_groups');
    }

    /**
     * @param mixed $id
     * @return void
     * @throws \XF\Db\Exception
     * @throws \XF\PrintableException
     */
    protected function rebuildById($id)
    {
        if ($id == 0) {
            // rebuild misc
            GroupNodeCache::rebuildCache();

            return;
        }

        /** @var \Truonglv\Groups\Entity\Group|null $group */
        $group = $this->app->em()->find('Truonglv\Groups:Group', $id);
        if ($group === null) {
            return;
        }

        GroupItemRebuild::rebuild($group, [
            'rebuildCounters' => $this->data['counter'],
            'rebuildCache' => true,
        ]);
    }
}
