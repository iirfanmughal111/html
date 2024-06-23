<?php

namespace DBTech\eCommerce\Repository;

use XF\Mvc\Entity\Repository;

/**
 * Class ShippingMethodShippingZoneMap
 *
 * @package DBTech\eCommerce\Repository
 */
class ShippingMethodShippingZoneMap extends Repository
{
	/**
	 * @param int $shippingZoneId
	 * @param array $shippingMethodIds
	 *
	 * @throws \InvalidArgumentException
	 */
	public function updateShippingZoneAssociations(int $shippingZoneId, array $shippingMethodIds)
	{
		$db = $this->db();
		$db->beginTransaction();

		$db->delete('xf_dbtech_ecommerce_shipping_method_shipping_zone_map', 'shipping_zone_id = ?', $shippingZoneId);

		$map = [];

		foreach ($shippingMethodIds AS $shippingMethodId)
		{
			$map[] = [
				'shipping_zone_id' => $shippingZoneId,
				'shipping_method_id' => $shippingMethodId
			];
		}

		if ($map)
		{
			$db->insertBulk('xf_dbtech_ecommerce_shipping_method_shipping_zone_map', $map);
		}

		$this->rebuildShippingZoneAssociationCache([$shippingZoneId]);

		$db->commit();
	}
	
	/**
	 * @param array $shippingZoneIds
	 */
	public function rebuildShippingZoneAssociationCache(array $shippingZoneIds)
	{
		if (!$shippingZoneIds)
		{
			return;
		}

		$newCache = [];

		$shippingMethodAssociations = $this->finder('DBTech\eCommerce:ShippingMethodShippingZoneMap')
			->where('shipping_zone_id', $shippingZoneIds);
		foreach ($shippingMethodAssociations->fetch() AS $shippingMethodMap)
		{
			$newCache[$shippingMethodMap->shipping_zone_id][] = $shippingMethodMap->shipping_method_id;
		}

		foreach ($shippingZoneIds AS $shippingZoneId)
		{
			if (!isset($newCache[$shippingZoneId]))
			{
				$newCache[$shippingZoneId] = [];
			}
		}

		$this->updateAssociationCache($newCache);
	}

	/**
	 * @param array $cache
	 */
	protected function updateAssociationCache(array $cache)
	{
		$shippingZoneIds = array_keys($cache);
		$shippingZones = $this->em->findByIds('DBTech\eCommerce:ShippingZone', $shippingZoneIds);

		foreach ($shippingZones AS $shippingZone)
		{
			/** @var \DBTech\eCommerce\Entity\ShippingZone $shippingZone */
			$shippingZone->shipping_methods = $cache[$shippingZone->shipping_zone_id];
			$shippingZone->saveIfChanged();
		}
	}
}