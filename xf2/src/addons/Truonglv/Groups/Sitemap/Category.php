<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Sitemap;

use XF\Sitemap\Entry;
use Truonglv\Groups\App;
use XF\Sitemap\AbstractHandler;

class Category extends AbstractHandler
{
    /**
     * @param mixed $start
     * @return \XF\Mvc\Entity\ArrayCollection
     */
    public function getRecords($start)
    {
        $ids = $this->getIds('xf_tl_group_category', 'category_id', $start);

        $categoryFinder = App::categoryFinder();
        $categories = $categoryFinder
            ->where('category_id', $ids)
            ->order('category_id')
            ->fetch();

        return $categories;
    }

    /**
     * @param mixed $record
     * @return Entry
     */
    public function getEntry($record)
    {
        /** @var \Truonglv\Groups\Entity\Category $record */
        $url = $this->app->router('public')->buildLink('canonical:group-categories', $record);

        return Entry::create($url);
    }

    /**
     * @param mixed $record
     * @return bool
     */
    public function isIncluded($record)
    {
        /** @var \Truonglv\Groups\Entity\Category $record */
        return $record->canView();
    }
}
