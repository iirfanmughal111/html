<?php

namespace DBTech\eCommerce\Entity;

use XF\Mvc\Entity\Structure;
use XF\Mvc\Entity\Entity;

/**
 * COLUMNS
 * @property int $product_id
 * @property string $filter_id
 *
 * RELATIONS
 * @property \DBTech\eCommerce\Entity\Product $Product
 */
class ProductFilterMap extends Entity
{
	/**
	 * @param \XF\Mvc\Entity\Structure $structure
	 *
	 * @return \XF\Mvc\Entity\Structure
	 */
	public static function getStructure(Structure $structure): Structure
	{
		$structure->table = 'xf_dbtech_ecommerce_product_filter_map';
		$structure->shortName = 'DBTech\eCommerce:ProductFilterMap';
		$structure->primaryKey = ['product_id', 'filter_id'];
		$structure->columns = [
			'product_id' => ['type' => self::UINT, 'required' => true],
			'filter_id' => ['type' => self::BINARY, 'maxLength' => 25, 'required' => true]
		];
		$structure->getters = [];
		$structure->relations = [
			'Product' => [
				'entity' => 'DBTech\eCommerce:Product',
				'type' => self::TO_ONE,
				'conditions' => 'product_id',
				'primary' => true
			]
		];

		return $structure;
	}
}