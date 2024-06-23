<?php

namespace DBTech\eCommerce\Entity;

use XF\Entity\AbstractPrefixMap;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property int $category_id
 * @property int $prefix_id
 *
 * RELATIONS
 * @property \DBTech\eCommerce\Entity\ProductPrefix $Prefix
 * @property \DBTech\eCommerce\Entity\Category $Category
 */
class CategoryPrefix extends AbstractPrefixMap
{
	/**
	 * @return string
	 */
	public static function getContainerKey(): string
	{
		return 'category_id';
	}
	
	/**
	 * @param \XF\Mvc\Entity\Structure $structure
	 *
	 * @return \XF\Mvc\Entity\Structure
	 */
	public static function getStructure(Structure $structure): Structure
	{
		self::setupDefaultStructure($structure, 'xf_dbtech_ecommerce_category_prefix', 'DBTech\eCommerce:CategoryPrefix', 'DBTech\eCommerce:ProductPrefix');

		$structure->relations['Category'] = [
			'entity' => 'DBTech\eCommerce:Category',
			'type' => self::TO_ONE,
			'conditions' => 'category_id',
			'primary' => true
		];

		return $structure;
	}
}