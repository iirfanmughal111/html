<?php

namespace Z61\Classifieds\Repository;

use XF\Repository\AbstractPrefixMap;

class CategoryPrefix extends AbstractPrefixMap
{
    protected function getMapEntityIdentifier()
    {
        return 'Z61\Classifieds:CategoryPrefix';
    }

    protected function getAssociationsForPrefix(\XF\Entity\AbstractPrefix $prefix)
    {
        return $prefix->getRelation('CategoryPrefixes');
    }

    protected function updateAssociationCache(array $cache)
    {
        $ids = array_keys($cache);
        $categories = $this->em->findByIds('Z61\Classifieds:Category', $ids);

        foreach ($categories AS $category)
        {
            /** @var \Z61\Classifieds\Entity\Category $category */
            $category->prefix_cache = $cache[$category->category_id];
            $category->saveIfChanged();
        }
    }
}