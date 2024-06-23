<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Tag;

use XF;
use Truonglv\Groups\App;
use XF\Mvc\Entity\Entity;
use XF\Tag\AbstractHandler;
use InvalidArgumentException;
use Truonglv\Groups\Entity\Category;

class Group extends AbstractHandler
{
    /**
     * @param Entity $entity
     * @return int
     */
    public function getContentDate(Entity $entity)
    {
        return (int) $entity->get('created_date');
    }

    /**
     * @param Entity $entity
     * @return bool
     */
    public function getContentVisibility(Entity $entity)
    {
        if ($entity instanceof \Truonglv\Groups\Entity\Group) {
            return $entity->isVisible();
        }

        return false;
    }

    /**
     * @param Entity $entity
     * @return array
     */
    public function getPermissionsFromContext(Entity $entity)
    {
        /** @var Category|null $category */
        $category = null;
        /** @var \Truonglv\Groups\Entity\Group|null $group */
        $group = null;

        if ($entity instanceof \Truonglv\Groups\Entity\Group) {
            $group = $entity;
            $category = $entity->Category;
        } elseif ($entity instanceof \Truonglv\Groups\Entity\Category) {
            $group = null;
            $category = $entity;
        }

        if ($category === null) {
            throw new InvalidArgumentException('Entity must be Category or Group');
        }

        if ($group !== null) {
            $edit = $group->canEditTags();
            $removeOthers = XF::visitor()->hasPermission(App::PERMISSION_GROUP, 'manageTagAny');
        } else {
            $edit = $category->canEditTags();
            $removeOthers = false;
        }

        return [
            'edit' => $edit,
            'removeOthers' => $removeOthers,
            'minTotal' => $category->min_tags
        ];
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
     * @return string
     */
    public function getTemplateName()
    {
        return 'public:tlg_search_result_group';
    }
}
