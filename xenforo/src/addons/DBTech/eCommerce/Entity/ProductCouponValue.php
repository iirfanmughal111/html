<?php

namespace DBTech\eCommerce\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property int $product_id
 * @property int $coupon_id
 * @property float $product_value
 */
class ProductCouponValue extends Entity
{
	/**
	 * @param \XF\Mvc\Entity\Structure $structure
	 *
	 * @return \XF\Mvc\Entity\Structure
	 */
	public static function getStructure(Structure $structure): Structure
	{
		$structure->table = 'xf_dbtech_ecommerce_product_coupon_value';
		$structure->shortName = 'DBTech\eCommerce:ProductCouponValue';
		$structure->primaryKey = ['product_id', 'coupon_id'];
		$structure->columns = [
			'product_id' => ['type' => self::UINT, 'required' => true],
			'coupon_id' => ['type' => self::UINT, 'required' => true],
			'product_value' => ['type' => self::FLOAT, 'default' => 0]
		];
		$structure->getters = [];
		$structure->relations = [];

		return $structure;
	}
}