<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Search\Data;

use XF;
use function count;
use function reset;
use function array_keys;
use Truonglv\Groups\App;
use XF\Mvc\Entity\Entity;
use function array_unique;
use XF\Search\IndexRecord;
use function array_fill_keys;
use XF\Search\Data\AbstractData;
use XF\Search\MetadataStructure;

class Group extends AbstractData
{
    /**
     * @param mixed $forView
     * @return array
     */
    public function getEntityWith($forView = false)
    {
        $with = ['Category'];
        $forViewBool = (bool) $forView;
        if ($forViewBool) {
            $with[] = 'User';
        }

        return $with;
    }

    /**
     * @param Entity $entity
     * @return int
     */
    public function getResultDate(Entity $entity)
    {
        if (!($entity instanceof \Truonglv\Groups\Entity\Group)) {
            return 0;
        }

        return $entity->created_date;
    }

    /**
     * @param Entity $entity
     * @return IndexRecord|null
     */
    public function getIndexData(Entity $entity)
    {
        if (!($entity instanceof \Truonglv\Groups\Entity\Group)) {
            return null;
        }

        /** @var \Truonglv\Groups\Entity\Category|null $category */
        $category = $entity->Category;
        if ($category === null) {
            return null;
        }

        $index = IndexRecord::create(App::CONTENT_TYPE_GROUP, $entity->group_id, [
            'title' => $entity->name_,
            'message' => $entity->short_description_ . ' ' . $entity->description_,
            'date' => $entity->created_date,
            'user_id' => $entity->owner_user_id,
            'discussion_id' => $entity->group_id,
            'metadata' => $this->getMetaData($entity)
        ]);

        if (!$entity->isVisible()) {
            $index->setHidden();
        }

        if (count($entity->tags) > 0) {
            $index->indexTags($entity->tags);
        }

        return $index;
    }

    /**
     * @param Entity $entity
     * @param array $options
     * @return array
     */
    public function getTemplateData(Entity $entity, array $options = [])
    {
        return [
            'group' => $entity,
            'options' => $options
        ];
    }

    /**
     * @return array
     */
    public function getSearchFormTab()
    {
        if (!App::hasPermission('view')) {
            return [];
        }

        return [
            'title' => XF::phrase('tlg_search_groups'),
            'order' => 300
        ];
    }

    /**
     * @return string
     */
    public function getTypeFormTemplate()
    {
        return 'public:tlg_search_form_group';
    }

    /**
     * @return array
     */
    public function getSearchFormData()
    {
        return [
            'categoryTree' => $this->getSearchableCategoryTree()
        ];
    }

    /**
     * @return string
     */
    public function getSectionContext()
    {
        return 'tl_groups';
    }

    /**
     * @return array
     */
    public function getSearchableContentTypes()
    {
        $contentTypes = [
            App::CONTENT_TYPE_GROUP,
            App::CONTENT_TYPE_COMMENT,
        ];

        if (App::isEnabledResources()) {
            $contentTypes[] = App::CONTENT_TYPE_RESOURCE;
        }

        if (App::isEnabledEvents()) {
            $contentTypes[] = App::CONTENT_TYPE_EVENT;
        }

        return $contentTypes;
    }

    /**
     * @return \XF\Tree
     */
    protected function getSearchableCategoryTree()
    {
        $categoryRepo = App::categoryRepo();
        $categoryTree = $categoryRepo->createCategoryTree($categoryRepo->getViewableCategories());

        return $categoryTree;
    }

    /**
     * @param MetadataStructure $structure
     * @return void
     */
    public function setupMetadataStructure(MetadataStructure $structure)
    {
        $structure->addField('category', MetadataStructure::INT);
        $structure->addField('group', MetadataStructure::INT);
    }

    /**
     * @param Entity $entity
     * @param mixed $error
     * @return bool
     */
    public function canUseInlineModeration(Entity $entity, & $error = null)
    {
        if (!($entity instanceof \Truonglv\Groups\Entity\Group)) {
            return false;
        }

        return $entity->canUseInlineModeration($error);
    }

    /**
     * @return string
     */
    public function getTemplateName()
    {
        return 'public:tlg_search_result_group';
    }

    /**
     * @param \XF\Search\Query\Query $query
     * @param \XF\Http\Request $request
     * @param array $urlConstraints
     * @return void
     */
    public function applyTypeConstraintsFromInput(
        \XF\Search\Query\Query $query,
        \XF\Http\Request $request,
        array & $urlConstraints
    ) {
        $categoryIds = $request->filter('c.categories', 'array-uint');
        $categoryIds = array_unique($categoryIds);
        if (count($categoryIds) > 0 && reset($categoryIds)) {
            if ($request->filter('c.child_categories', 'bool')) {
                $categoryTree = $this->getSearchableCategoryTree();

                $searchCategoryIds = array_fill_keys($categoryIds, true);
                $categoryTree->traverse(function ($id, $category) use (&$searchCategoryIds) {
                    if (isset($searchCategoryIds[$id]) || isset($searchCategoryIds[$category->parent_category_id])) {
                        $searchCategoryIds[$id] = true;
                    }
                });

                $categoryIds = array_unique(array_keys($searchCategoryIds));
            } else {
                unset($urlConstraints['child_categories']);
            }

            $query->withMetadata('category', $categoryIds);
        } else {
            unset($urlConstraints['categories']);
            unset($urlConstraints['child_categories']);
        }
    }

    /**
     * @param \Truonglv\Groups\Entity\Group $entity
     * @return array
     */
    protected function getMetaData(\Truonglv\Groups\Entity\Group $entity)
    {
        $metadata = [
            'category' => $entity->category_id,
            'group' => $entity->group_id
        ];

        return $metadata;
    }
}
