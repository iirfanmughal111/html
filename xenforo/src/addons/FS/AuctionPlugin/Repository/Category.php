<?php

namespace FS\AuctionPlugin\Repository;

use XF\Repository\AbstractCategoryTree;

class Category extends AbstractCategoryTree
{
    protected function getClassName()
    {
        return 'FS\AuctionPlugin:Category';
    }

    public function mergeCategoryListExtras(array $extras, array $childExtras)
    {
        $output = array_merge([
            'listing_count' => 0,
            'childCount' => 0,
        ], $extras);

        foreach ($childExtras as $child) {
            if (!empty($child['listing_count'])) {
                $output['listing_count'] += $child['listing_count'];
            }

            $output['childCount'] += 1 + (!empty($child['childCount']) ? $child['childCount'] : 0);
        }

        return $output;
    }
}
