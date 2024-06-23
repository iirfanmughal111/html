<?php

namespace DBTech\eCommerce\Repository;

use DBTech\eCommerce\Entity\ShippingMethod;
use DBTech\eCommerce\Entity\ShippingZone;

use XF\Mvc\Entity\Repository;

/**
 * Class ShippingCombination
 *
 * @package DBTech\eCommerce\Repository
 */
class ShippingCombination extends Repository
{
	/**
	 * @param ShippingMethod $shippingMethod
	 *
	 * @throws \InvalidArgumentException
	 */
	public function updateShippingCombinationsForShippingMethod(ShippingMethod $shippingMethod)
	{
		$db = $this->db();
		$db->beginTransaction();

		$db->delete('xf_dbtech_ecommerce_shipping_combination', 'shipping_method_id = ?', $shippingMethod->shipping_method_id);
		
		$shippingMethodShippingZoneMap = \XF::finder('DBTech\eCommerce:ShippingMethodShippingZoneMap')
			->where('shipping_method_id', $shippingMethod->shipping_method_id)
			->fetch();
		
		$map = [];
		
		/** @var \DBTech\eCommerce\Entity\ShippingMethodShippingZoneMap $shippingMethodMap */
		foreach ($shippingMethodShippingZoneMap AS $shippingMethodMap)
		{
			$countryShippingZoneMap = \XF::finder('DBTech\eCommerce:CountryShippingZoneMap')
				->where('shipping_zone_id', $shippingMethodMap->shipping_zone_id)
				->fetch();
			
			/** @var \DBTech\eCommerce\Entity\CountryShippingZoneMap $shippingZoneMap */
			foreach ($countryShippingZoneMap as $shippingZoneMap)
			{
				$map[] = [
					'shipping_method_id' => $shippingMethod->shipping_method_id,
					'shipping_zone_id'   => $shippingMethodMap->shipping_zone_id,
					'country_code' => $shippingZoneMap->country_code,
					'cost_formula' => $shippingMethod->cost_formula
				];
			}
		}

		if ($map)
		{
			$db->insertBulk('xf_dbtech_ecommerce_shipping_combination', $map);
		}

		$db->commit();
	}
	
	/**
	 * @param \DBTech\eCommerce\Entity\Country $country
	 *
	 * @throws \InvalidArgumentException
	 */
	public function updateShippingCombinationsForCountry(\DBTech\eCommerce\Entity\Country $country)
	{
		$db = $this->db();
		$db->beginTransaction();
		
		$db->delete('xf_dbtech_ecommerce_shipping_combination', 'country_code = ?', $country->country_code);
		
		$countryShippingZoneMap = \XF::finder('DBTech\eCommerce:CountryShippingZoneMap')
			->where('country_code', $country->country_code)
			->fetch();
		
		$map = [];
		
		/** @var \DBTech\eCommerce\Entity\CountryShippingZoneMap $shippingZoneMap */
		foreach ($countryShippingZoneMap AS $shippingZoneMap)
		{
			$shippingMethodShippingZoneMap = \XF::finder('DBTech\eCommerce:ShippingMethodShippingZoneMap')
				->where('shipping_zone_id', $shippingZoneMap->shipping_zone_id)
				->fetch();
			
			/** @var \DBTech\eCommerce\Entity\ShippingMethodShippingZoneMap $shippingMethodMap */
			foreach ($shippingMethodShippingZoneMap as $shippingMethodMap)
			{
				$map[] = [
					'shipping_method_id' => $shippingMethodMap->shipping_method_id,
					'shipping_zone_id' => $shippingZoneMap->shipping_zone_id,
					'country_code' => $country->country_code,
					'cost_formula' => $shippingMethodMap->ShippingMethod->cost_formula
				];
			}
		}
		
		if ($map)
		{
			$db->insertBulk('xf_dbtech_ecommerce_shipping_combination', $map);
		}
		
		$db->commit();
	}
	
	
	
	/**
	 * @param ShippingZone $shippingZone
	 *
	 * @throws \InvalidArgumentException
	 */
	public function updateShippingCombinationsForShippingZone(ShippingZone $shippingZone)
	{
		$db = $this->db();
		$db->beginTransaction();
		
		$db->delete('xf_dbtech_ecommerce_shipping_combination', 'shipping_zone_id = ?', $shippingZone->shipping_zone_id);
		
		$countryShippingZoneMap = \XF::finder('DBTech\eCommerce:CountryShippingZoneMap')
			->where('shipping_zone_id', $shippingZone->shipping_zone_id)
			->fetch();
		
		$shippingMethodShippingZoneMap = \XF::finder('DBTech\eCommerce:ShippingMethodShippingZoneMap')
			->where('shipping_zone_id', $shippingZone->shipping_zone_id)
			->fetch();
		
		$map = [];
		
		/** @var \DBTech\eCommerce\Entity\CountryShippingZoneMap $countryMap */
		foreach ($countryShippingZoneMap AS $countryMap)
		{
			/** @var \DBTech\eCommerce\Entity\ShippingMethodShippingZoneMap $shippingMethodMap */
			foreach ($shippingMethodShippingZoneMap as $shippingMethodMap)
			{
				$map[] = [
					'shipping_method_id' => $shippingMethodMap->shipping_method_id,
					'shipping_zone_id' => $shippingZone->shipping_zone_id,
					'country_code' => $countryMap->country_code,
					'cost_formula' => $shippingMethodMap->ShippingMethod->cost_formula
				];
			}
		}
		
		if ($map)
		{
			$db->insertBulk('xf_dbtech_ecommerce_shipping_combination', $map);
		}
		
		$db->commit();
	}
}