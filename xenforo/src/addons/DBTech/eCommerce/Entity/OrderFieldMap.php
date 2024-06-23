<?php

namespace DBTech\eCommerce\Entity;

use XF\Mvc\Entity\Structure;
use XF\Entity\AbstractFieldMap;

/**
 * COLUMNS
 * @property int $product_id
 * @property string $field_id
 *
 * RELATIONS
 * @property \DBTech\eCommerce\Entity\OrderField $Field
 * @property \DBTech\eCommerce\Entity\Product $Product
 */
class OrderFieldMap extends AbstractFieldMap
{
	/**
	 * @return string|void
	 */
	public static function getContainerKey(): string
	{
		return 'product_id';
	}
	
	/**
	 * @param \XF\Mvc\Entity\Structure $structure
	 *
	 * @return \XF\Mvc\Entity\Structure
	 */
	public static function getStructure(Structure $structure): Structure
	{
		self::setupDefaultStructure($structure, 'xf_dbtech_ecommerce_order_field_map', 'DBTech\eCommerce:OrderFieldMap', 'DBTech\eCommerce:OrderField');

		$structure->relations['Product'] = [
			'entity' => 'DBTech\eCommerce:Product',
			'type' => self::TO_ONE,
			'conditions' => 'product_id',
			'primary' => true
		];

		return $structure;
	}
}