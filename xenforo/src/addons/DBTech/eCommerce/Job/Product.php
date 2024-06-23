<?php

namespace DBTech\eCommerce\Job;

use XF\Job\AbstractRebuildJob;

/**
 * Class Product
 *
 * @package DBTech\eCommerce\Job
 */
class Product extends AbstractRebuildJob
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
				SELECT product_id
				FROM xf_dbtech_ecommerce_product
				WHERE product_id > ?
				ORDER BY product_id
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
		/** @var \DBTech\eCommerce\Entity\Product $product */
		$product = $this->app->em()->find('DBTech\eCommerce:Product', $id);
		if ($product)
		{
			$product->ensureCostExists();
			
			if ($product->rebuildCounters())
			{
				$product->save();
			}
			
			$product->rebuildProductFieldValuesCache();
			$product->rebuildShippingZoneCache();
		}
	}
	
	/**
	 * @return \XF\Phrase
	 */
	protected function getStatusType(): \XF\Phrase
	{
		return \XF::phrase('dbtech_ecommerce_ecommerce_products');
	}
}