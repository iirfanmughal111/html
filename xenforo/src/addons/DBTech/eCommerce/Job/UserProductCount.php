<?php

namespace DBTech\eCommerce\Job;

use XF\Job\AbstractRebuildJob;

/**
 * Class UserProductCount
 *
 * @package DBTech\eCommerce\Job
 */
class UserProductCount extends AbstractRebuildJob
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
				SELECT user_id
				FROM xf_user
				WHERE user_id > ?
				ORDER BY user_id
			',
			$batch
		), $start);
	}
	
	/**
	 * @param $id
	 */
	protected function rebuildById($id)
	{
		/** @var \DBTech\eCommerce\Repository\Product $repo */
		$repo = $this->app->repository('DBTech\eCommerce:Product');
		$count = $repo->getUserProductCount($id);

		$this->app->db()->update('xf_user', ['dbtech_ecommerce_product_count' => $count], 'user_id = ?', $id);
	}
	
	/**
	 * @return \XF\Phrase
	 */
	protected function getStatusType(): \XF\Phrase
	{
		return \XF::phrase('dbtech_ecommerce_ecommerce_product_counts');
	}
}