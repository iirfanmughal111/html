<?php

namespace DBTech\eCommerce\Entity;

use XF\Mvc\Entity\Structure;
use XF\Mvc\Entity\Entity;

/**
 * COLUMNS
 * @property int $shipping_method_id
 * @property int $shipping_zone_id
 *
 * RELATIONS
 * @property \DBTech\eCommerce\Entity\ShippingMethod $ShippingMethod
 * @property \DBTech\eCommerce\Entity\ShippingZone $ShippingZone
 */
class ShippingMethodShippingZoneMap extends Entity
{
	/**
	 * @param \XF\Mvc\Entity\Structure $structure
	 *
	 * @return \XF\Mvc\Entity\Structure
	 */
	public static function getStructure(Structure $structure): Structure
	{
		$structure->table = 'xf_dbtech_ecommerce_shipping_method_shipping_zone_map';
		$structure->shortName = 'DBTech\eCommerce:ShippingMethodShippingZoneMap';
		$structure->primaryKey = ['shipping_zone_id', 'shipping_method_id'];
		$structure->columns = [
			'shipping_method_id' => ['type' => self::UINT, 'required' => true],
			'shipping_zone_id' => ['type' => self::UINT, 'required' => true]
		];
		$structure->getters = [];
		$structure->relations = [
			'ShippingMethod' => [
				'entity' => 'DBTech\eCommerce:ShippingMethod',
				'type' => self::TO_ONE,
				'conditions' => 'shipping_method_id',
				'primary' => true
			],
			'ShippingZone' => [
				'entity' => 'DBTech\eCommerce:ShippingZone',
				'type' => self::TO_ONE,
				'conditions' => 'shipping_zone_id',
				'primary' => true
			]
		];

		return $structure;
	}
}