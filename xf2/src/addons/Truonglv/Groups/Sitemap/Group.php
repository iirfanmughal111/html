<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Sitemap;

use XF\Sitemap\Entry;
use Truonglv\Groups\App;
use XF\Sitemap\AbstractHandler;

class Group extends AbstractHandler
{
    /**
     * @param mixed $start
     * @return \XF\Mvc\Entity\ArrayCollection
     */
    public function getRecords($start)
    {
        $ids = $this->getIds('xf_tl_group', 'group_id', $start);

        $groupFinder = App::groupFinder();
        $groups = $groupFinder
            ->where('group_id', $ids)
            ->with(['Category'])
            ->order('group_id')
            ->fetch();

        return $groups;
    }

    /**
     * @param mixed $record
     * @return Entry
     */
    public function getEntry($record)
    {
        /** @var \Truonglv\Groups\Entity\Group $record */
        $url = $this->app->router('public')->buildLink('canonical:groups', $record);

        return Entry::create($url, [
            'lastmod' => $record->last_activity
        ]);
    }

    /**
     * @param mixed $record
     * @return bool
     */
    public function isIncluded($record)
    {
        /** @var \Truonglv\Groups\Entity\Group $record */
        return $record->canView();
    }
}
