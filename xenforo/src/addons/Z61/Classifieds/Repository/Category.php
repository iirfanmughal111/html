<?php

namespace Z61\Classifieds\Repository;

use XF\Repository\AbstractCategoryTree;

class Category extends AbstractCategoryTree
{
    protected function getClassName()
    {
        return 'Z61\Classifieds:Category';
    }

    public function mergeCategoryListExtras(array $extras, array $childExtras)
    {
        $output = array_merge([
            'listing_count' => 0,
            'childCount' => 0,
        ], $extras);

        foreach($childExtras as $child)
        {
            if (!empty($child['listing_count']))
            {
                $output['listing_count'] += $child['listing_count'];
            }

            $output['childCount'] += 1 + (!empty($child['childCount']) ? $child['childCount'] : 0);
        }

        return $output;
    }

    public function getListingsIdsInCategory(\Z61\Classifieds\Entity\Category $category)
    {
        $db = $this->db();

        return $db->fetchAllColumn('
			SELECT listing_id
			FROM xf_z61_classifieds_listing
			WHERE category_id = ? and listing_state = ?
			ORDER BY listing_date
		', [
		    $category->category_id,
            'visible'
        ]);
    }

    public function markCategoryReadByVisitor(\Z61\Classifieds\Entity\Category $category, $newRead = null)
    {
        $userId = \XF::visitor()->user_id;
        if (!$userId)
        {
            return false;
        }

        if ($newRead === null)
        {
            $newRead = max(\XF::$time, $category->last_listing_date);
        }

        $cutOff = \XF::$time - $this->options()->readMarkingDataLifetime * 86400;
        if ($newRead <= $cutOff)
        {
            return false;
        }

        $read = $category->Read[$userId];
        if ($read && $newRead <= $read->category_read_date)
        {
            return false;
        }

        $this->db()->insert('xf_z61_classifieds_category_read', [
            'category_id' => $category->category_id,
            'user_id' => $userId,
            'category_read_date' => $newRead
        ], false, 'category_read_date = VALUES(category_read_date)');

        return true;
    }

    /**
     * @param Entity|null $withinCategory
     * @param null $with
     *
     * @return \XF\Mvc\Entity\ArrayCollection
     */
    public function getViewableCategoriesWhere(Entity $withinCategory = null, $with = null, $where = null)
    {
        if ($with === null)
        {
            $with = [];
        }
        else
        {
            $with = (array)$with;
        }

        $with[] = 'Permissions|' . \XF::visitor()->permission_combination_id;

        $categories = $this->findCategoryList($withinCategory, $with);
        if(is_array($where))
        {
            $categories->where($where);
        }

        return $categories->fetch()->filterViewable();
    }

}