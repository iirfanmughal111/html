<?php

namespace DBTech\eCommerce\Repository;

use XF\Repository\AbstractPrefixMap;

/**
 * Class CategoryPrefix
 *
 * @package DBTech\eCommerce\Repository
 */
class CategoryPrefix extends AbstractPrefixMap
{
	/**
	 * @return string
	 */
	protected function getMapEntityIdentifier(): string
	{
		return 'DBTech\eCommerce:CategoryPrefix';
	}
	
	/**
	 * @param \XF\Entity\AbstractPrefix $prefix
	 *
	 * @return mixed
	 * @throws \InvalidArgumentException
	 */
	protected function getAssociationsForPrefix(\XF\Entity\AbstractPrefix $prefix)
	{
		return $prefix->getRelation('CategoryPrefixes');
	}
	
	/**
	 * @param array $cache
	 */
	protected function updateAssociationCache(array $cache)
	{
		$ids = array_keys($cache);
		$categories = $this->em->findByIds('DBTech\eCommerce:Category', $ids);

		foreach ($categories AS $category)
		{
			/** @var \DBTech\eCommerce\Entity\Category $category */
			$category->prefix_cache = $cache[$category->category_id];
			$category->saveIfChanged();
		}
	}
}