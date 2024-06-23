<?php

namespace DBTech\eCommerce\Repository;

use XF\Mvc\Entity\Repository;

/**
 * Class CountryShippingZoneMap
 *
 * @package DBTech\eCommerce\Repository
 */
class CountryShippingZoneMap extends Repository
{
	/**
	 * @param int $shippingZoneId
	 * @param array $countryIds
	 *
	 * @throws \InvalidArgumentException
	 */
	public function updateShippingZoneAssociations(int $shippingZoneId, array $countryIds)
	{
		$db = $this->db();
		$db->beginTransaction();

		$db->delete('xf_dbtech_ecommerce_country_shipping_zone_map', 'shipping_zone_id = ?', $shippingZoneId);

		$map = [];

		foreach ($countryIds AS $countryId)
		{
			$map[] = [
				'shipping_zone_id' => $shippingZoneId,
				'country_code' => $countryId
			];
		}

		if ($map)
		{
			$db->insertBulk('xf_dbtech_ecommerce_country_shipping_zone_map', $map);
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

		/** @var \DBTech\eCommerce\Entity\CountryShippingZoneMap[]|\XF\Mvc\Entity\ArrayCollection $countryAssociations */
		$countryAssociations = $this->finder('DBTech\eCommerce:CountryShippingZoneMap')
			->where('shipping_zone_id', $shippingZoneIds)
			->fetch()
		;

		foreach ($countryAssociations AS $countryMap)
		{
			$newCache[$countryMap->shipping_zone_id][$countryMap->country_code] = $countryMap->country_code;
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
			$shippingZone->countries = $cache[$shippingZone->shipping_zone_id];
			$shippingZone->saveIfChanged();
		}
	}
}