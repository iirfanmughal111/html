<?php

namespace XenAddons\Showcase\Entity;

use XF\Entity\AbstractFieldMap;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property int $category_id
 * @property int $field_id
 *
 * RELATIONS
 * @property \XenAddons\Showcase\Entity\UpdateField $Field   
 * @property \XenAddons\Showcase\Entity\Category $Category
 */
class CategoryUpdateField extends AbstractFieldMap
{
	public static function getContainerKey()
	{
		return 'category_id';
	}

	public static function getStructure(Structure $structure)
	{
		self::setupDefaultStructure($structure, 'xf_xa_sc_category_update_field', 'XenAddons\Showcase:CategoryUpdateField', 'XenAddons\Showcase:UpdateField');

		$structure->relations['Category'] = [
			'entity' => 'XenAddons\Showcase:Category',
			'type' => self::TO_ONE,
			'conditions' => 'category_id',
			'primary' => true
		];

		return $structure;
	}
}