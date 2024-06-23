<?php

namespace DBTech\eCommerce\Repository;

use XF\Mvc\Entity\Repository;

/**
 * Class ProductCommission
 *
 * @package DBTech\eCommerce\Repository
 */
class ProductCommission extends Repository
{
	/**
	 * @param int $commissionId
	 * @param array $commissionInfo
	 *
	 * @throws \InvalidArgumentException
	 */
	public function updateContentAssociations(int $commissionId, array $commissionInfo)
	{
		$db = $this->db();
		$db->beginTransaction();

		$db->delete('xf_dbtech_ecommerce_product_commission_value', 'commission_id = ?', $commissionId);

		$map = [];

		foreach ($commissionInfo AS $info)
		{
			$map[] = [
				'commission_id' => $commissionId,
				'product_id' => $info['product_id'],
				'commission_type' => $info['commission_type'],
				'commission_value' => $info['commission_value']
			];
		}

		if ($map)
		{
			$db->insertBulk('xf_dbtech_ecommerce_product_commission_value', $map);
		}

		$this->rebuildContentAssociationCache([$commissionId]);

		$db->commit();
	}
	
	/**
	 * @param array $commissionIds
	 */
	public function rebuildContentAssociationCache(array $commissionIds)
	{
		if (!$commissionIds)
		{
			return;
		}

		$newCache = [];

		$commissionAssociations = $this->finder('DBTech\eCommerce:ProductCommissionValue')
			->where('commission_id', $commissionIds);
		
		/** @var \DBTech\eCommerce\Entity\ProductCommissionValue $commissionValue */
		foreach ($commissionAssociations->fetch() AS $commissionValue)
		{
			$newCache[$commissionValue->commission_id][] = $commissionValue->toArray();
		}

		foreach ($commissionIds AS $commissionId)
		{
			if (!isset($newCache[$commissionId]))
			{
				$newCache[$commissionId] = [];
			}
		}

		$this->updateAssociationCache($newCache);
	}

	/**
	 * @param array $cache
	 */
	protected function updateAssociationCache(array $cache)
	{
		$commissionIds = array_keys($cache);
		$commissions = $this->em->findByIds('DBTech\eCommerce:Commission', $commissionIds);

		foreach ($commissions AS $commission)
		{
			/** @var \DBTech\eCommerce\Entity\Commission $commission */
			$commission->product_commissions = $cache[$commission->commission_id];
			$commission->saveIfChanged();
		}
	}
}