<?php

namespace XenAddons\Showcase\Repository;

use XF\Mvc\Entity\Finder;
use XF\Mvc\Entity\Repository;
use XF\Repository\AbstractFieldMap;

class CategoryUpdateField extends AbstractFieldMap
{
	protected function getMapEntityIdentifier()
	{
		return 'XenAddons\Showcase:CategoryUpdateField';
	}

	protected function getAssociationsForField(\XF\Entity\AbstractField $field)
	{
		return $field->getRelation('CategoryUpdateFields');
	}

	protected function updateAssociationCache(array $cache)
	{
		$categoryIds = array_keys($cache);
		$categories = $this->em->findByIds('XenAddons\Showcase:Category', $categoryIds);

		foreach ($categories AS $category)
		{
			/** @var \XenAddons\Showcase\Entity\Category $category */
			$category->update_field_cache = $cache[$category->category_id];
			$category->saveIfChanged();
		}
	}
}