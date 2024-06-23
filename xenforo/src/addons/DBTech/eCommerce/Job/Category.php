<?php

namespace DBTech\eCommerce\Job;

use XF\Job\AbstractRebuildJob;

/**
 * Class Category
 *
 * @package DBTech\eCommerce\Job
 */
class Category extends AbstractRebuildJob
{
	/**
	 * @param $start
	 * @param $batch
	 *
	 * @return array
	 */
	protected function getNextIds($start, $batch): array
	{
		$db = $this->app->db();

		return $db->fetchAllColumn($db->limit(
			'
				SELECT category_id
				FROM xf_dbtech_ecommerce_category
				WHERE category_id > ?
				ORDER BY category_id
			',
			$batch
		), $start);
	}
	
	/**
	 * @param $id
	 *
	 * @throws \LogicException
	 * @throws \Exception
	 * @throws \XF\PrintableException
	 */
	protected function rebuildById($id)
	{
		/** @var \DBTech\eCommerce\Entity\Category $category */
		$category = $this->app->em()->find('DBTech\eCommerce:Category', $id);
		if ($category)
		{
			$category->rebuildCounters();
			$category->save();
		}
	}
	
	/**
	 * @return \XF\Phrase
	 */
	protected function getStatusType(): \XF\Phrase
	{
		return \XF::phrase('dbtech_ecommerce_ecommerce_categories');
	}
}