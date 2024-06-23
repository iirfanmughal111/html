<?php

namespace DBTech\eCommerce\Repository;

use XF\Mvc\Entity\Repository;

/**
 * Class ProductDistributor
 *
 * @package DBTech\eCommerce\Repository
 */
class ProductDistributor extends Repository
{
	/**
	 * @param int $distributorId
	 * @param array $distributorInfo
	 *
	 * @throws \InvalidArgumentException
	 */
	public function updateContentAssociations(int $distributorId, array $distributorInfo)
	{
		$db = $this->db();
		$db->beginTransaction();

		$db->delete('xf_dbtech_ecommerce_product_distributor_value', 'user_id = ?', $distributorId);

		$map = [];

		foreach ($distributorInfo AS $info)
		{
			$map[] = [
				'user_id' => $distributorId,
				'product_id' => $info['product_id'],
				'available_licenses' => $info['available_licenses']
			];
		}

		if ($map)
		{
			$db->insertBulk('xf_dbtech_ecommerce_product_distributor_value', $map);
		}

		$this->rebuildContentAssociationCache([$distributorId]);

		$db->commit();
	}
	
	/**
	 * @param array $distributorIds
	 */
	public function rebuildContentAssociationCache(array $distributorIds)
	{
		if (!$distributorIds)
		{
			return;
		}

		$newCache = [];

		$distributorAssociations = $this->finder('DBTech\eCommerce:ProductDistributorValue')
			->where('user_id', $distributorIds);
		
		/** @var \DBTech\eCommerce\Entity\ProductDistributorValue $distributorValue */
		foreach ($distributorAssociations->fetch() AS $distributorValue)
		{
			$newCache[$distributorValue->user_id][$distributorValue->product_id] = $distributorValue->toArray();
		}

		foreach ($distributorIds AS $distributorId)
		{
			if (!isset($newCache[$distributorId]))
			{
				$newCache[$distributorId] = [];
			}
		}

		$this->updateAssociationCache($newCache);
	}

	/**
	 * @param array $cache
	 */
	protected function updateAssociationCache(array $cache)
	{
		$distributorIds = array_keys($cache);
		$distributors = $this->em->findByIds('DBTech\eCommerce:Distributor', $distributorIds);

		foreach ($distributors AS $distributor)
		{
			/** @var \DBTech\eCommerce\Entity\Distributor $distributor */
			$distributor->available_products = $cache[$distributor->user_id];
			$distributor->saveIfChanged();
		}
	}
}