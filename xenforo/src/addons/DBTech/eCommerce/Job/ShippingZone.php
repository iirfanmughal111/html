<?php

namespace DBTech\eCommerce\Job;

use XF\Job\AbstractRebuildJob;

/**
 * Class ShippingZone
 *
 * @package DBTech\eCommerce\Job
 */
class ShippingZone extends AbstractRebuildJob
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
				SELECT shipping_zone_id
				FROM xf_dbtech_ecommerce_shipping_zone
				WHERE shipping_zone_id > ?
				ORDER BY shipping_zone_id
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
		/** @var \DBTech\eCommerce\Entity\ShippingZone $shippingZone */
		$shippingZone = $this->app->em()->find('DBTech\eCommerce:ShippingZone', $id);
		if ($shippingZone)
		{
			if ($shippingZone->rebuildCounters())
			{
				$shippingZone->save();
			}
			
			$shippingZone->rebuildCountryCache();
			$shippingZone->rebuildShippingMethodCache();
			$shippingZone->rebuildShippingCombination();
		}
	}
	
	/**
	 * @return \XF\Phrase
	 */
	protected function getStatusType(): \XF\Phrase
	{
		return \XF::phrase('dbtech_ecommerce_ecommerce_shipping_zones');
	}
}