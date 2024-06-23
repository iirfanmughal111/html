<?php

namespace DBTech\eCommerce\Entity;

use XF\Mvc\Entity\Structure;
use XF\Mvc\Entity\Entity;

/**
 * COLUMNS
 * @property string $country_code
 * @property int $shipping_zone_id
 *
 * RELATIONS
 * @property \DBTech\eCommerce\Entity\Country $Country
 * @property \DBTech\eCommerce\Entity\ShippingZone $ShippingZone
 */
class CountryShippingZoneMap extends Entity
{
	/**
	 * @param \XF\Mvc\Entity\Structure $structure
	 *
	 * @return \XF\Mvc\Entity\Structure
	 */
	public static function getStructure(Structure $structure): Structure
	{
		$structure->table = 'xf_dbtech_ecommerce_country_shipping_zone_map';
		$structure->shortName = 'DBTech\eCommerce:CountryShippingZoneMap';
		$structure->primaryKey = ['country_code', 'shipping_zone_id'];
		$structure->columns = [
			'country_code' => ['type' => self::STR, 'maxLength' => 2, 'required' => true],
			'shipping_zone_id' => ['type' => self::UINT, 'required' => true]
		];
		$structure->getters = [];
		$structure->relations = [
			'Country' => [
				'entity' => 'DBTech\eCommerce:Country',
				'type' => self::TO_ONE,
				'conditions' => 'country_code',
				'primary' => true
			],
			'ShippingZone' => [
				'entity' => 'DBTech\eCommerce:ShippingZone',
				'type' => self::TO_ONE,
				'conditions' => 'shipping_zone_id',
				'primary' => true
			]
		];
		$structure->defaultWith = [
			'Country'
		];

		return $structure;
	}
}