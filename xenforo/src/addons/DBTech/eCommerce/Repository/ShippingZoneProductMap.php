<?php

namespace DBTech\eCommerce\Repository;

use XF\Mvc\Entity\Repository;

/**
 * Class ShippingZoneProductMap
 *
 * @package DBTech\eCommerce\Repository
 */
class ShippingZoneProductMap extends Repository
{
	/**
	 * @param int $productId
	 * @param array $shippingZoneIds
	 *
	 * @throws \InvalidArgumentException
	 */
	public function updateProductAssociations(int $productId, array $shippingZoneIds)
	{
		$db = $this->db();
		$db->beginTransaction();

		$db->delete('xf_dbtech_ecommerce_shipping_zone_product_map', 'product_id = ?', $productId);

		$map = [];

		foreach ($shippingZoneIds AS $shippingZoneId)
		{
			$map[] = [
				'product_id' => $productId,
				'shipping_zone_id' => $shippingZoneId
			];
		}

		if ($map)
		{
			$db->insertBulk('xf_dbtech_ecommerce_shipping_zone_product_map', $map);
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

		$shippingZoneAssociations = $this->finder('DBTech\eCommerce:ShippingZoneProductMap')
			->where('product_id', $productIds);
		foreach ($shippingZoneAssociations->fetch() AS $shippingZoneMap)
		{
			$newCache[$shippingZoneMap->product_id][] = $shippingZoneMap->shipping_zone_id;
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
			$product->shipping_zones = $cache[$product->product_id];
			$product->saveIfChanged();
		}
	}
}