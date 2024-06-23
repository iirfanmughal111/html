<?php

namespace DBTech\eCommerce\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property int $product_id
 * @property string $sale_type
 * @property float $sale_percent
 * @property float $sale_value
 *
 * RELATIONS
 * @property \DBTech\eCommerce\Entity\Product $Product
 */
class ProductSale extends Entity
{
	/**
	 * @return int|float
	 */
	public function getApplicableDiscount()
	{
		return $this->sale_type == 'percent' ? $this->sale_percent : $this->sale_value;
	}
	
	/**
	 * @param float $cost
	 *
	 * @return float
	 */
	public function getDiscountedCost(float $cost): float
	{
		switch ($this->sale_type)
		{
			case 'percent':
				$cost *= (1 - ($this->sale_percent / 100));
				break;

			case 'value':
				$cost = max(0, $cost - $this->sale_value);
				break;
		}

		// Round to 2 decimals
		$cost = sprintf("%.2f", $cost);

		return $cost;
	}

	/**
	 * @param \XF\Mvc\Entity\Structure $structure
	 *
	 * @return \XF\Mvc\Entity\Structure
	 */
	public static function getStructure(Structure $structure): Structure
	{
		$structure->table = 'xf_dbtech_ecommerce_product_sale';
		$structure->shortName = 'DBTech\eCommerce:ProductSale';
		$structure->primaryKey = 'product_id';
		$structure->columns = [
			'product_id' => ['type' => self::UINT, 'required' => true],
			'sale_type' => ['type' => self::STR, 'default' => 'percent',
				'allowedValues' => ['percent', 'value']
			],
			'sale_percent' => ['type' => self::FLOAT, 'min' => 0, 'max' => 100, 'default' => 0.00],
			'sale_value' => ['type' => self::FLOAT, 'min' => 0, 'default' => 0.00],
		];
		$structure->behaviors = [];
		$structure->getters = [];
		$structure->relations = [
			'Product' => [
				'entity' => 'DBTech\eCommerce:Product',
				'type' => self::TO_ONE,
				'conditions' => 'product_id',
				'primary' => true
			]
		];
		$structure->defaultWith = [
			'Product',
			'Product.Sale'
		];

		return $structure;
	}
}