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

class Event extends AbstractHandler
{
    /**
     * @param mixed $forView
     * @return array
     */
    public function getEntityWith($forView = false)
    {
        return ['Group'];
    }

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
     * @return mixed
     */
    public function getContentVisibility(Entity $entity)
    {
        if ($entity instanceof \Truonglv\Groups\Entity\Event) {
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
        /** @var \Truonglv\Groups\Entity\Group|null $group */
        $group = null;
        /** @var \Truonglv\Groups\Entity\Event|null $event */
        $event = null;
        if ($entity instanceof \Truonglv\Groups\Entity\Event) {
            $event = $entity;
            $group = $event->Group;
        } elseif ($entity instanceof \Truonglv\Groups\Entity\Group) {
            $group = $entity;
        }
        if ($group === null) {
            throw new InvalidArgumentException('Entity must be Event or Group.');
        }

        if ($event !== null) {
            $edit = $event->canEditTags();
            $removeOthers = XF::visitor()->hasPermission(App::PERMISSION_GROUP, 'manageTagAny')
                || ($group->Member !== null && $group->Member->hasRole(App::MEMBER_ROLE_PERM_KEY_EVENT, 'editAny'));
        } else {
            $removeOthers = false;
            $edit = $group->canEditTags();
        }

        return [
            'edit' => $edit,
            'removeOthers' => $removeOthers,
            // there are no required to creating tags in event
            'minTotal' => 0
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
            'event' => $entity,
            'options' => $options
        ];
    }

    /**
     * @return string
     */
    public function getTemplateName()
    {
        return 'public:tlg_search_result_event';
    }
}
