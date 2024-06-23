<?php


namespace Z61\Classifieds\Repository;


use XF\Repository\AbstractFieldMap;

class CategoryField extends AbstractFieldMap
{
    protected function getMapEntityIdentifier()
    {
        return 'Z61\Classifieds:CategoryField';
    }

    protected function getAssociationsForField(\XF\Entity\AbstractField $field)
    {
        return $field->getRelation('CategoryFields');
    }

    protected function updateAssociationCache(array $cache)
    {
        $categoryIds = array_keys($cache);
        $categories = $this->em->findByIds('Z61\Classifieds:Category', $categoryIds);

        foreach ($categories AS $category)
        {
            /** @var \Z61\Classifieds\Entity\Category $category */
            $category->field_cache = $cache[$category->category_id];
            $category->saveIfChanged();
        }
    }
}