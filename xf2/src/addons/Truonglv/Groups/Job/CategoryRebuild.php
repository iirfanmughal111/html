<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Job;

use XF;
use XF\Job\AbstractRebuildJob;

class CategoryRebuild extends AbstractRebuildJob
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
            SELECT `category_id`
            FROM `xf_tl_group_category`
            WHERE `category_id` > ?
            ORDER BY `category_id`
        ', $batch), $start);
    }

    /**
     * @return \XF\Phrase
     */
    protected function getStatusType()
    {
        return XF::phrase('tlg_categories');
    }

    /**
     * @param mixed $id
     * @throws \XF\PrintableException
     * @return void
     */
    protected function rebuildById($id)
    {
        /** @var \Truonglv\Groups\Entity\Category|null $category */
        $category = XF::em()->find('Truonglv\Groups:Category', $id);
        if ($category === null) {
            return;
        }

        $category->rebuildCounters();
        $category->save();
    }
}
