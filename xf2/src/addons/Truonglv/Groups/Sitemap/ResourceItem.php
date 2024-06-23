<?php

namespace Truonglv\Groups\Sitemap;

use XF;
use XF\Sitemap\Entry;
use XF\Sitemap\AbstractHandler;

class ResourceItem extends AbstractHandler
{
    /**
     * @param mixed $start
     * @return \XF\Mvc\Entity\ArrayCollection
     */
    public function getRecords($start)
    {
        $ids = $this->getIds('xf_tl_group_resource', 'resource_id', $start);

        $resourceFinder = XF::finder('Truonglv\Groups:ResourceItem');

        return $resourceFinder
            ->where('resource_id', $ids)
            ->with(['Group', 'Group.Category'])
            ->order('resource_id')
            ->fetch();
    }

    /**
     * @param mixed $record
     * @return Entry
     */
    public function getEntry($record)
    {
        /** @var \Truonglv\Groups\Entity\ResourceItem $record */
        $url = $this->app->router('public')->buildLink('canonical:group-resources', $record);

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
        if ($record instanceof \Truonglv\Groups\Entity\ResourceItem) {
            return $record->canView();
        }

        return false;
    }
}
