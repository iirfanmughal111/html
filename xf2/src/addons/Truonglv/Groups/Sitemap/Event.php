<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Sitemap;

use XF\Sitemap\Entry;
use Truonglv\Groups\App;
use XF\Sitemap\AbstractHandler;

class Event extends AbstractHandler
{
    /**
     * @param mixed $start
     * @return \XF\Mvc\Entity\ArrayCollection
     */
    public function getRecords($start)
    {
        $ids = $this->getIds('xf_tl_group_event', 'event_id', $start);

        $eventFinder = App::eventFinder();
        $events = $eventFinder
            ->where('event_id', $ids)
            ->with(['Group', 'Group.Category'])
            ->order('event_id')
            ->fetch();

        return $events;
    }

    /**
     * @param mixed $record
     * @return Entry
     */
    public function getEntry($record)
    {
        /** @var \Truonglv\Groups\Entity\Event $record */
        $url = $this->app->router('public')->buildLink('canonical:group-events', $record);

        return Entry::create($url, [
            'lastmod' => $record->last_comment_date
        ]);
    }

    /**
     * @param mixed $record
     * @return bool
     */
    public function isIncluded($record)
    {
        /** @var \Truonglv\Groups\Entity\Event $record */
        return $record->canView();
    }
}
