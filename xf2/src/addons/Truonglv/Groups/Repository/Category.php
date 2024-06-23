<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Repository;

use function array_merge;
use XF\Mvc\Entity\Entity;
use XF\Repository\AbstractCategoryTree;

class Category extends AbstractCategoryTree
{
    /**
     * @param Entity|null $withinCategory
     * @param null|string $with
     * @return \XF\Mvc\Entity\Finder
     */
    public function findCategoryList(Entity $withinCategory = null, $with = null)
    {
        $with = null;

        return parent::findCategoryList($withinCategory, $with);
    }

    /**
     * @param array $extras
     * @param array $childExtras
     * @return array
     */
    public function mergeCategoryListExtras(array $extras, array $childExtras)
    {
        $output = array_merge([
            'childCount' => 0,
            'group_count' => 0
        ], $extras);

        foreach ($childExtras as $childExtra) {
            $output['group_count'] += isset($childExtra['group_count']) ? $childExtra['group_count'] : 0;
            $output['childCount'] += isset($childExtra['childCount']) ? $childExtra['childCount'] : 0;
        }

        return $output;
    }

    /**
     * @return string
     */
    protected function getClassName()
    {
        return 'Truonglv\Groups:Category';
    }
}
