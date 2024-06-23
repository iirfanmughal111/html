<?php

namespace Z61\Classifieds\Job;

use XF\Job\AbstractRebuildJob;

class Category extends AbstractRebuildJob
{
    protected function getNextIds($start, $batch)
    {
        $db = $this->app->db();

        return $db->fetchAllColumn($db->limit(
            "
				SELECT category_id
				FROM xf_z61_classifieds_category
				WHERE category_id > ?
				ORDER BY category_id
			", $batch
        ), $start);
    }

    protected function rebuildById($id)
    {
        /** @var \Z61\Classifieds\Entity\Category $category */
        $category = $this->app->em()->find('Z61\Classifieds:Category', $id);
        if ($category)
        {
            $category->rebuildCounters();
            $category->save();
        }
    }

    protected function getStatusType()
    {
        return \XF::phrase('z61_classifieds_categories');
    }
}