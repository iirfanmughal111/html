<?php

namespace DBTech\eCommerce\Entity;

use XF\Mvc\Entity\Structure;
use XF\Mvc\Entity\Entity;

/**
 * COLUMNS
 * @property int $product_id
 * @property int $user_id
 * @property int $available_licenses
 *
 * RELATIONS
 * @property \DBTech\eCommerce\Entity\Product $Product
 * @property \DBTech\eCommerce\Entity\Distributor $Distributor
 */
class ProductDistributorValue extends Entity
{
	/**
	 * @param \XF\Mvc\Entity\Structure $structure
	 *
	 * @return \XF\Mvc\Entity\Structure
	 */
	public static function getStructure(Structure $structure): Structure
	{
		$structure->table = 'xf_dbtech_ecommerce_product_distributor_value';
		$structure->shortName = 'DBTech\eCommerce:ProductDistributorValue';
		$structure->primaryKey = ['product_id', 'user_id'];
		$structure->columns = [
			'product_id' => ['type' => self::UINT, 'required' => true],
			'user_id' => ['type' => self::UINT, 'required' => true],
			'available_licenses' => ['type' => self::INT, 'default' => -1],
		];
		$structure->getters = [];
		$structure->relations = [
			'Product' => [
				'entity' => 'DBTech\eCommerce:Product',
				'type' => self::TO_ONE,
				'conditions' => 'product_id',
				'primary' => true
			],
			'Distributor' => [
				'entity' => 'DBTech\eCommerce:Distributor',
				'type' => self::TO_ONE,
				'conditions' => 'user_id',
				'primary' => true
			],
		];

		return $structure;
	}
}