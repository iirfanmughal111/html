<?php

namespace DBTech\eCommerce\Import\Data;

use XF\Import\Data\AbstractEmulatedData;

/**
 * Class ProductCost
 *
 * @package DBTech\eCommerce\Import\Data
 */
class ProductCost extends AbstractEmulatedData
{
	/**
	 * @return string
	 */
	public function getImportType(): string
	{
		return 'cost';
	}
	
	/**
	 * @return string
	 */
	protected function getEntityShortName(): string
	{
		return 'DBTech\eCommerce:ProductCost';
	}
	
	
	/**
	 * @param $oldId
	 * @param $newId
	 */
	protected function postSave($oldId, $newId)
	{
		/** @var \DBTech\eCommerce\Repository\ProductCost $productCostRepo */
		$productCostRepo = \XF::repository('DBTech\eCommerce:ProductCost');
		$productCostRepo->rebuildContentAssociationCache([$this->get('product_id')]);
	}
}