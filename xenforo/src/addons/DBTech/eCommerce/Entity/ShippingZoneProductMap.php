<?php

namespace DBTech\eCommerce\Entity;

use XF\Mvc\Entity\Structure;
use XF\Mvc\Entity\Entity;

/**
 * COLUMNS
 * @property int $shipping_zone_id
 * @property int $product_id
 *
 * RELATIONS
 * @property \DBTech\eCommerce\Entity\ShippingZone $ShippingZone
 * @property \DBTech\eCommerce\Entity\Product $Product
 */
class ShippingZoneProductMap extends Entity
{
	/**
	 * @param \XF\Mvc\Entity\Structure $structure
	 *
	 * @return \XF\Mvc\Entity\Structure
	 */
	public static function getStructure(Structure $structure): Structure
	{
		$structure->table = 'xf_dbtech_ecommerce_shipping_zone_product_map';
		$structure->shortName = 'DBTech\eCommerce:ShippingZoneProductMap';
		$structure->primaryKey = ['shipping_zone_id', 'product_id'];
		$structure->columns = [
			'shipping_zone_id' => ['type' => self::UINT, 'required' => true],
			'product_id' => ['type' => self::UINT, 'required' => true]
		];
		$structure->getters = [];
		$structure->relations = [
			'ShippingZone' => [
				'entity' => 'DBTech\eCommerce:ShippingZone',
				'type' => self::TO_ONE,
				'conditions' => 'shipping_zone_id',
				'primary' => true
			],
			'Product' => [
				'entity' => 'DBTech\eCommerce:Product',
				'type' => self::TO_ONE,
				'conditions' => 'product_id',
				'primary' => true
			]
		];
		$structure->defaultWith = [
			'Product'
		];

		return $structure;
	}
}