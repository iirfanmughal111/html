<?php

namespace DBTech\eCommerce\Job;

use XF\Job\AbstractRebuildJob;

/**
 * Class Commission
 *
 * @package DBTech\eCommerce\Job
 */
class Commission extends AbstractRebuildJob
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
				SELECT commission_id
				FROM xf_dbtech_ecommerce_commission
				WHERE commission_id > ?
				ORDER BY commission_id
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
		/** @var \DBTech\eCommerce\Entity\Commission $commission */
		$commission = $this->app->em()->find('DBTech\eCommerce:Commission', $id);
		if ($commission)
		{
			$commission->rebuildCounters();
			$commission->save();
		}
	}

	/**
	 * @return \XF\Phrase
	 */
	protected function getStatusType(): \XF\Phrase
	{
		return \XF::phrase('dbtech_ecommerce_ecommerce_commissions');
	}
}