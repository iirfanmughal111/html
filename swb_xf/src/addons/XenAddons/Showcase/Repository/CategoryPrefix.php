<?php

namespace XenAddons\Showcase\Repository;

use XF\Mvc\Entity\Finder;
use XF\Mvc\Entity\Repository;
use XF\Repository\AbstractPrefixMap;

class CategoryPrefix extends AbstractPrefixMap
{
	protected function getMapEntityIdentifier()
	{
		return 'XenAddons\Showcase:CategoryPrefix';
	}

	protected function getAssociationsForPrefix(\XF\Entity\AbstractPrefix $prefix)
	{
		return $prefix->getRelation('CategoryPrefixes');
	}

	protected function updateAssociationCache(array $cache)
	{
		$ids = array_keys($cache);
		$categories = $this->em->findByIds('XenAddons\Showcase:Category', $ids);

		foreach ($categories AS $category)
		{
			/** @var \XenAddons\Showcase\Entity\Category $category */
			$category->prefix_cache = $cache[$category->category_id];
			$category->saveIfChanged();
		}
	}
}