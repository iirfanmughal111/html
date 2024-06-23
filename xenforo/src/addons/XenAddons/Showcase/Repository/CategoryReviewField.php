<?php

namespace XenAddons\Showcase\Repository;

use XF\Mvc\Entity\Finder;
use XF\Mvc\Entity\Repository;
use XF\Repository\AbstractFieldMap;

class CategoryReviewField extends AbstractFieldMap
{
	protected function getMapEntityIdentifier()
	{
		return 'XenAddons\Showcase:CategoryReviewField';
	}

	protected function getAssociationsForField(\XF\Entity\AbstractField $field)
	{
		return $field->getRelation('CategoryReviewFields');
	}

	protected function updateAssociationCache(array $cache)
	{
		$categoryIds = array_keys($cache);
		$categories = $this->em->findByIds('XenAddons\Showcase:Category', $categoryIds);

		foreach ($categories AS $category)
		{
			/** @var \XenAddons\Showcase\Entity\Category $category */
			$category->review_field_cache = $cache[$category->category_id];
			$category->saveIfChanged();
		}
	}
}