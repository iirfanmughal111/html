<?php

namespace XenAddons\Showcase\Job;

use XF\Job\AbstractRebuildJob;

class Category extends AbstractRebuildJob
{
	protected function getNextIds($start, $batch)
	{
		$db = $this->app->db();

		return $db->fetchAllColumn($db->limit(
			"
				SELECT category_id
				FROM xf_xa_sc_category
				WHERE category_id > ?
				ORDER BY category_id
			", $batch
		), $start);
	}

	protected function rebuildById($id)
	{
		/** @var \XenAddons\Showcase\Entity\Category $category */
		$category = $this->app->em()->find('XenAddons\Showcase:Category', $id);
		if ($category)
		{
			$category->rebuildCounters();
			$category->save();
		}
	}

	protected function getStatusType()
	{
		return \XF::phrase('xa_sc_item_categories');
	}
}