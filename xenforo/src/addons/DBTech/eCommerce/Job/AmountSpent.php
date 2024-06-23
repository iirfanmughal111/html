<?php

namespace DBTech\eCommerce\Job;

use XF\Job\AbstractRebuildJob;

/**
 * Class AmountSpent
 *
 * @package DBTech\eCommerce\Job
 */
class AmountSpent extends AbstractRebuildJob
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
		/** @var \DBTech\eCommerce\Repository\Purchase $repo */
		$repo = $this->app->repository('DBTech\eCommerce:Purchase');
		$amount = $repo->getAmountSpentForUser($id);

		$this->app->db()->update(
			'xf_user',
			['dbtech_ecommerce_amount_spent' => $amount > 0.00 ? $amount : 0.00],
			'user_id = ?',
			$id
		);
	}

	/**
	 * @return \XF\Phrase
	 */
	protected function getStatusType(): \XF\Phrase
	{
		return \XF::phrase('dbtech_ecommerce_ecommerce_amount_spent');
	}
}