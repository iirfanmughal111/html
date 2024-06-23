<?php

namespace DBTech\eCommerce\Entity;

use XF\Mvc\Entity\Structure;
use XF\Mvc\Entity\Entity;

/**
 * COLUMNS
 * @property int $shipping_method_id
 * @property int $shipping_zone_id
 * @property string $country_code
 * @property string $cost_formula
 *
 * RELATIONS
 * @property \DBTech\eCommerce\Entity\ShippingMethod $ShippingMethod
 * @property \DBTech\eCommerce\Entity\ShippingZone $ShippingZone
 * @property \DBTech\eCommerce\Entity\Country $Country
 */
class ShippingCombination extends Entity
{
	/**
	 * @param \DBTech\eCommerce\Entity\Product $product
	 *
	 * @return bool
	 */
	public function isApplicableToProduct(Product $product): bool
	{
		return $product->ShippingZones->offsetExists($this->shipping_zone_id);
	}
	
	/**
	 * @param \XF\Mvc\Entity\Structure $structure
	 *
	 * @return \XF\Mvc\Entity\Structure
	 */
	public static function getStructure(Structure $structure): Structure
	{
		$structure->table = 'xf_dbtech_ecommerce_shipping_combination';
		$structure->shortName = 'DBTech\eCommerce:ShippingCombination';
		$structure->primaryKey = ['shipping_method_id', 'shipping_zone_id', 'country_code'];
		$structure->columns = [
			'shipping_method_id' => ['type' => self::UINT, 'required' => true],
			'shipping_zone_id' => ['type' => self::UINT, 'required' => true],
			'country_code' => ['type' => self::STR, 'maxLength' => 2, 'required' => true],
			'cost_formula' => ['type' => self::STR, 'required' => true]
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
			],
			'Country' => [
				'entity' => 'DBTech\eCommerce:Country',
				'type' => self::TO_ONE,
				'conditions' => 'country_code',
				'primary' => true
			]
		];
		$structure->defaultWith = [
			'ShippingMethod',
			'ShippingZone',
			'Country'
		];

		return $structure;
	}
}