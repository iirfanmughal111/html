<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Job;

use XF;
use XF\Job\AbstractRebuildJob;

class EventRebuild extends AbstractRebuildJob
{
    /**
     * @param mixed $start
     * @param mixed $batch
     * @return array
     */
    protected function getNextIds($start, $batch)
    {
        $db = $this->app->db();

        return $db->fetchAllColumn($db->limit('
            SELECT `event_id`
            FROM `xf_tl_group_event`
            WHERE `event_id` > ?
            ORDER BY `event_id`
        ', $batch), $start);
    }

    /**
     * @return \XF\Phrase
     */
    protected function getStatusType()
    {
        return XF::phrase('tlg_events');
    }

    /**
     * @param mixed $id
     * @return void
     */
    protected function rebuildById($id)
    {
        /** @var \Truonglv\Groups\Entity\Event|null $event */
        $event = XF::em()->find('Truonglv\Groups:Event', $id);
        if ($event === null) {
            return;
        }

        if ($event->FirstComment === null) {
            $event->rebuildFirstComment();
        }

        $event->rebuildCounters();
        $event->saveIfChanged();
    }
}
