<?php

namespace DBTech\eCommerce\Repository;

use XF\Repository\AbstractFieldMap;

/**
 * Class CategoryReviewField
 *
 * @package DBTech\eCommerce\Repository
 */
class CategoryReviewField extends AbstractFieldMap
{
	/**
	 * @return string
	 */
	protected function getMapEntityIdentifier(): string
	{
		return 'DBTech\eCommerce:CategoryReviewField';
	}

	/**
	 * @param \XF\Entity\AbstractField $field
	 *
	 * @return mixed|\XF\Mvc\Entity\Entity|\XF\Mvc\Entity\Entity[]|\XF\Mvc\Entity\FinderCollection|null
	 */
	protected function getAssociationsForField(\XF\Entity\AbstractField $field)
	{
		return $field->getRelation('CategoryReviewFields');
	}

	/**
	 * @param array $cache
	 */
	protected function updateAssociationCache(array $cache)
	{
		$categoryIds = array_keys($cache);
		$categories = $this->em->findByIds('DBTech\eCommerce:Category', $categoryIds);

		foreach ($categories AS $category)
		{
			/** @var \DBTech\eCommerce\Entity\Category $category */
			$category->review_field_cache = $cache[$category->category_id];
			$category->saveIfChanged();
		}
	}
}