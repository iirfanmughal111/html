<?php

namespace DBTech\eCommerce\Job;

use XF\Job\AbstractRebuildJob;

/**
 * Class Address
 *
 * @package DBTech\eCommerce\Job
 */
class Address extends AbstractRebuildJob
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
				SELECT address_id
				FROM xf_dbtech_ecommerce_address
				WHERE address_id > ?
				ORDER BY address_id
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
		/** @var \DBTech\eCommerce\Entity\Address $address */
		$address = $this->app->em()->find('DBTech\eCommerce:Address', $id);
		if ($address)
		{
			if ($address->rebuildCounters())
			{
				$address->save();
			}
		}
	}
	
	/**
	 * @return \XF\Phrase
	 */
	protected function getStatusType(): \XF\Phrase
	{
		return \XF::phrase('dbtech_ecommerce_ecommerce_addresses');
	}
}