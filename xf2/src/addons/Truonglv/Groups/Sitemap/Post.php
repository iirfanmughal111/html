<?php
/**
 * @license
 * Copyright 2019 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Sitemap;

use XF\Sitemap\Entry;
use Truonglv\Groups\App;
use XF\Sitemap\AbstractHandler;

class Post extends AbstractHandler
{
    /**
     * @param mixed $start
     * @return \XF\Mvc\Entity\ArrayCollection
     */
    public function getRecords($start)
    {
        $ids = $this->getIds('xf_tl_group_post', 'post_id', $start, 1000);

        $posts = App::postFinder()
            ->where('post_id', $ids)
            ->with(['Group', 'Group.Category'])
            ->order('post_id')
            ->fetch();

        return $posts;
    }

    /**
     * @param mixed $record
     * @return Entry
     */
    public function getEntry($record)
    {
        /** @var \Truonglv\Groups\Entity\Post $record */
        $url = $this->app->router('public')->buildLink('canonical:group-posts', $record);

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
        if ($record instanceof \Truonglv\Groups\Entity\Post) {
            return $record->canView();
        }

        return false;
    }
}
