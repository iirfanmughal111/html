<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Repository;

use function array_keys;
use XF\Repository\AbstractFieldMap;

class CategoryField extends AbstractFieldMap
{
    /**
     * @return string
     */
    protected function getMapEntityIdentifier()
    {
        return 'Truonglv\Groups:CategoryField';
    }

    /**
     * @param \XF\Entity\AbstractField $field
     * @return mixed
     */
    protected function getAssociationsForField(\XF\Entity\AbstractField $field)
    {
        return $field->getRelation('CategoryFields');
    }

    /**
     * @param array $cache
     * @return void
     */
    protected function updateAssociationCache(array $cache)
    {
        $categoryIds = array_keys($cache);
        $categories = $this->em->findByIds('Truonglv\Groups:Category', $categoryIds);

        foreach ($categories as $category) {
            /** @var \Truonglv\Groups\Entity\Category $category */
            $category->field_cache = $cache[$category->category_id];
            $category->saveIfChanged();
        }
    }
}
