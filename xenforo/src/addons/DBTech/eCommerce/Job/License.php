<?php

namespace DBTech\eCommerce\Job;

use XF\Job\AbstractRebuildJob;

/**
 * Class License
 *
 * @package DBTech\eCommerce\Job
 */
class License extends AbstractRebuildJob
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
				SELECT license_id
				FROM xf_dbtech_ecommerce_license
				WHERE license_id > ?
				ORDER BY license_id
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
		/** @var \DBTech\eCommerce\Entity\License $license */
		$license = $this->app->em()->find('DBTech\eCommerce:License', $id);
		if (!$license)
		{
			return;
		}
		
		if ($license->rebuildCounters())
		{
			$license->save();
		}
		
		$license->rebuildLicenseFieldValuesCache();
	}

	/**
	 * @return \XF\Phrase
	 */
	protected function getStatusType(): \XF\Phrase
	{
		return \XF::phrase('dbtech_ecommerce_ecommerce_licenses');
	}
}