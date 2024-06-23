<?php

namespace Z61\Classifieds\Search\Data;

use XF\Mvc\Entity\Entity;
use XF\Search\Data\AbstractData;
use XF\Search\IndexRecord;
use XF\Search\MetadataStructure;
use XF\Search\Query\MetadataConstraint;

class Listing extends AbstractData
{
    public function getEntityWith($forView = false)
    {
        $get = ['Category'];
        if ($forView)
        {
            $get[] = 'User';

            $visitor = \XF::visitor();
            $get[] = 'Category.Permissions|' . $visitor->permission_combination_id;
        }

        return $get;
    }

    public function getIndexData(Entity $entity)
    {
        /** @var \Z61\Classifieds\Entity\Listing $entity */

        if (!$entity->Category)
        {
            return null;
        }

        $index = IndexRecord::create('classifieds_listing', $entity->listing_id, [
            'title' => $entity->title_,
            'message' => $entity->content,
            'date' => $entity->listing_date,
            'user_id' => $entity->user_id,
            'discussion_id' => $entity->discussion_thread_id,
            'metadata' => $this->getMetaData($entity)
        ]);

        if (!$entity->isVisible())
        {
            $index->setHidden();
        }

        if ($entity->tags)
        {
            $index->indexTags($entity->tags);
        }

        return $index;
    }

    protected function getMetaData(\Z61\Classifieds\Entity\Listing $entity)
    {
        $metadata = [
            'classifiedscat' => $entity->category_id,
            'listing' => $entity->listing_id
        ];
        if ($entity->prefix_id)
        {
            $metadata['classifiedsprefix'] = $entity->prefix_id;
        }

        return $metadata;
    }

    public function setupMetadataStructure(MetadataStructure $structure)
    {
        $structure->addField('classifiedscat', MetadataStructure::INT);
        $structure->addField('listing', MetadataStructure::INT);
        $structure->addField('classifiedsprefix', MetadataStructure::INT);
    }

    public function getResultDate(Entity $entity)
    {
        return $entity->listing_date;
    }

    public function getTemplateData(Entity $entity, array $options = [])
    {
        return [
            'listing' => $entity,
            'options' => $options
        ];
    }

    public function canUseInlineModeration(Entity $entity, &$error = null)
    {
        /** @var \Z61\Classifieds\Entity\Listing $entity */
        return $entity->canUseInlineModeration($error);
    }

    public function getSearchableContentTypes()
    {
        return ['classifieds_listing'];
    }

    public function getSearchFormTab()
    {
        /** @var \Z61\Classifieds\XF\Entity\User $visitor */
        $visitor = \XF::visitor();
        if (!method_exists($visitor, 'canViewClassifieds') || !$visitor->canViewClassifieds())
        {
            return null;
        }

        return [
            'title' => \XF::phrase('z61_classifieds_search_listings'),
            'order' => 300
        ];
    }

    public function getSectionContext()
    {
        return 'classifieds';
    }

    public function getSearchFormData()
    {
        $prefixListData = $this->getPrefixListData();

        return [
            'prefixGroups' => $prefixListData['prefixGroups'],
            'prefixesGrouped' => $prefixListData['prefixesGrouped'],

            'categoryTree' => $this->getSearchableCategoryTree()
        ];
    }

    /**
     * @return \XF\Tree
     */
    protected function getSearchableCategoryTree()
    {
        /** @var \Z61\Classifieds\Repository\Category $categoryRepo */
        $categoryRepo = \XF::repository('Z61\Classifieds:Category');
        return $categoryRepo->createCategoryTree($categoryRepo->getViewableCategories());
    }

    protected function getPrefixListData()
    {
        /** @var \Z61\Classifieds\Repository\ListingPrefix $prefixRepo */
        $prefixRepo = \XF::repository('Z61\Classifieds:ListingPrefix');
        return $prefixRepo->getPrefixListData();
    }

    public function applyTypeConstraintsFromInput(\XF\Search\Query\Query $query, \XF\Http\Request $request, array &$urlConstraints)
    {
        $prefixes = $request->filter('c.prefixes', 'array-uint');
        $prefixes = array_unique($prefixes);
        if ($prefixes && reset($prefixes))
        {
            $query->withMetadata('classifiedsprefix', $prefixes);
        }
        else
        {
            unset($urlConstraints['prefixes']);
        }

        $categoryIds = $request->filter('c.categories', 'array-uint');
        $categoryIds = array_unique($categoryIds);
        if ($categoryIds && reset($categoryIds))
        {
            if ($request->filter('c.child_categories', 'bool'))
            {
                $categoryTree = $this->getSearchableCategoryTree();

                $searchCategoryIds = array_fill_keys($categoryIds, true);
                $categoryTree->traverse(function($id, $category) use (&$searchCategoryIds)
                {
                    if (isset($searchCategoryIds[$id]) || isset($searchCategoryIds[$category->parent_category_id]))
                    {
                        $searchCategoryIds[$id] = true;
                    }
                });

                $categoryIds = array_unique(array_keys($searchCategoryIds));
            }
            else
            {
                unset($urlConstraints['child_categories']);
            }

            $query->withMetadata('classifiedscat', $categoryIds);
        }
        else
        {
            unset($urlConstraints['categories']);
            unset($urlConstraints['child_categories']);
        }
    }

    public function getTypePermissionConstraints(\XF\Search\Query\Query $query, $isOnlyType)
    {
        /** @var \Z61\Classifieds\Repository\Category $categoryRepo */
        $categoryRepo = \XF::repository('Z61\Classifieds:Category');

        $with = ['Permissions|' . \XF::visitor()->permission_combination_id];
        $categories = $categoryRepo->findCategoryList(null, $with)->fetch();

        $skip = [];
        foreach ($categories AS $category)
        {
            /** @var \Z61\Classifieds\Entity\Category $category */
            if (!$category->canView())
            {
                $skip[] = $category->category_id;
            }
        }

        if ($skip)
        {
            return [
                new MetadataConstraint('classifiedscat', $skip, MetadataConstraint::MATCH_NONE)
            ];
        }
        else
        {
            return [];
        }
    }

    public function getGroupByType()
    {
        return 'classifieds_listing';
    }
}