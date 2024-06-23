<?php

namespace DBTech\eCommerce\Repository;

use XF\Mvc\Entity\Repository;

/**
 * Class ProductFilterMap
 *
 * @package DBTech\eCommerce\Repository
 */
class ProductFilterMap extends Repository
{
	/**
	 * @param int $productId
	 * @param array $filterIds
	 *
	 * @throws \InvalidArgumentException
	 */
	public function updateProductAssociations(int $productId, array $filterIds)
	{
		$db = $this->db();
		$db->beginTransaction();

		$db->delete('xf_dbtech_ecommerce_product_filter_map', 'product_id = ?', $productId);

		$map = [];

		foreach ($filterIds AS $filterId)
		{
			$map[] = [
				'product_id' => $productId,
				'filter_id' => $filterId
			];
		}

		if ($map)
		{
			$db->insertBulk('xf_dbtech_ecommerce_product_filter_map', $map);
		}

		$this->rebuildProductAssociationCache([$productId]);

		$db->commit();
	}
	
	/**
	 * @param array $productIds
	 */
	public function rebuildProductAssociationCache(array $productIds)
	{
		if (!$productIds)
		{
			return;
		}

		$newCache = [];

		$filterAssociations = $this->finder('DBTech\eCommerce:ProductFilterMap')
			->where('product_id', $productIds);
		foreach ($filterAssociations->fetch() AS $filterMap)
		{
			$newCache[$filterMap->product_id][] = $filterMap->filter_id;
		}

		foreach ($productIds AS $productId)
		{
			if (!isset($newCache[$productId]))
			{
				$newCache[$productId] = [];
			}
		}

		$this->updateAssociationCache($newCache);
	}

	/**
	 * @param array $cache
	 */
	protected function updateAssociationCache(array $cache)
	{
		$productIds = array_keys($cache);
		$products = $this->em->findByIds('DBTech\eCommerce:Product', $productIds);

		foreach ($products AS $product)
		{
			/** @var \DBTech\eCommerce\Entity\Product $product */
			$product->product_filters = $cache[$product->product_id];
			$product->saveIfChanged();
		}
	}
}