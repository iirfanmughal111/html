<?php

namespace XenAddons\Showcase\Entity;

use XF\Entity\AbstractPrefixMap;
use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property int $category_id
 * @property int $prefix_id
 *
 * RELATIONS
 * @property \XenAddons\Showcase\Entity\ItemPrefix $Prefix
 * @property \XenAddons\Showcase\Entity\Category $Category
 */
class CategoryPrefix extends AbstractPrefixMap
{
	public static function getContainerKey()
	{
		return 'category_id';
	}

	public static function getStructure(Structure $structure)
	{
		self::setupDefaultStructure($structure, 'xf_xa_sc_category_prefix', 'XenAddons\Showcase:CategoryPrefix', 'XenAddons\Showcase:ItemPrefix');

		$structure->relations['Category'] = [
			'entity' => 'XenAddons\Showcase:Category',
			'type' => self::TO_ONE,
			'conditions' => 'category_id',
			'primary' => true
		];

		return $structure;
	}
}