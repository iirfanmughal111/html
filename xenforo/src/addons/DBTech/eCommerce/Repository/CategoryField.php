<?php

namespace DBTech\eCommerce\Repository;

use XF\Repository\AbstractFieldMap;

/**
 * Class CategoryField
 *
 * @package DBTech\eCommerce\Repository
 */
class CategoryField extends AbstractFieldMap
{
	/**
	 * @return string
	 */
	protected function getMapEntityIdentifier(): string
	{
		return 'DBTech\eCommerce:CategoryField';
	}
	
	/**
	 * @param \XF\Entity\AbstractField $field
	 *
	 * @return mixed
	 * @throws \InvalidArgumentException
	 */
	protected function getAssociationsForField(\XF\Entity\AbstractField $field)
	{
		return $field->getRelation('CategoryFields');
	}
	
	/**
	 * @param array $cache
	 */
	protected function updateAssociationCache(array $cache)
	{
		$categoryIds = array_keys($cache);
		$categorys = $this->em->findByIds('DBTech\eCommerce:Category', $categoryIds);
		
		foreach ($categorys AS $category)
		{
			/** @var \DBTech\eCommerce\Entity\Category $category */
			$category->field_cache = $cache[$category->category_id];
			$category->saveIfChanged();
		}
	}
}