<?php

namespace DBTech\eCommerce\Entity;

use XF\Mvc\Entity\Structure;
use XF\Mvc\Entity\Entity;

/**
 * COLUMNS
 * @property int|null $product_feature_temp_id
 * @property int $product_id
 * @property string|null $feature_key
 * @property int $create_date
 * @property int|null $expiry_date
 *
 * RELATIONS
 * @property \DBTech\eCommerce\Entity\Product $Product
 */
class ProductFeatureTemp extends Entity
{
	protected function _preSave()
	{
		if (!$this->expiry_date)
		{
			$this->expiry_date = null;
		}
	}
	
	public static function getStructure(Structure $structure): Structure
	{
		$structure->table = 'xf_dbtech_ecommerce_product_feature_temp';
		$structure->shortName = 'DBTech\eCommerce:ProductFeatureTemp';
		$structure->primaryKey = 'product_feature_temp_id';
		$structure->columns = [
			'product_feature_temp_id' => ['type' => self::UINT, 'autoIncrement' => true, 'nullable' => true],
			'product_id' => ['type' => self::UINT, 'required' => true],
			'feature_key' => ['type' => self::STR, 'maxLength' => 50, 'required' => true, 'nullable' => true],
			'create_date' => ['type' => self::UINT, 'default' => \XF::$time],
			'expiry_date' => ['type' => self::UINT, 'required' => true, 'nullable' => true]
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