<?php

namespace Truonglv\Groups\Attachment;

use XF;
use XF\Entity\Attachment;
use XF\Mvc\Entity\Entity;
use XF\Attachment\AbstractHandler;

class ResourceItem extends AbstractHandler
{
    /**
     * @param Attachment $attachment
     * @param Entity $container
     * @param mixed $error
     * @return bool
     */
    public function canView(Attachment $attachment, Entity $container, & $error = null)
    {
        if ($container instanceof \Truonglv\Groups\Entity\ResourceItem) {
            if (!$container->canView($error)) {
                return false;
            }

            return $container->canDownload($error);
        }

        return false;
    }

    /**
     * @param array $context
     * @param mixed $error
     * @return bool
     */
    public function canManageAttachments(array $context, & $error = null)
    {
        $em = XF::em();
        if (isset($context['resource_id'])) {
            /** @var \Truonglv\Groups\Entity\ResourceItem|null $resource */
            $resource = $em->find('Truonglv\Groups:ResourceItem', $context['resource_id']);
            if ($resource === null || !$resource->canEdit($error)) {
                return false;
            }

            return true;
        } elseif (isset($context['group_id'])) {
            /** @var \Truonglv\Groups\Entity\Group|null $group */
            $group = $em->find('Truonglv\Groups:Group', $context['group_id']);
            if ($group === null || !$group->canAddResource($error)) {
                return false;
            }

            return true;
        }

        return false;
    }

    /**
     * @param Attachment $attachment
     * @param Entity|null $container
     * @return void
     */
    public function onAttachmentDelete(Attachment $attachment, Entity $container = null)
    {
        if ($container instanceof \Truonglv\Groups\Entity\ResourceItem) {
            $container->attach_count--;
            $container->saveIfChanged();
        }
    }

    /**
     * @param array $context
     * @return array
     */
    public function getConstraints(array $context)
    {
        /** @var \XF\Repository\Attachment $attachmentRepo */
        $attachmentRepo = XF::repository('XF:Attachment');
        $constraints = $attachmentRepo->getDefaultAttachmentConstraints();

        $constraints['count'] = 1;

        return $constraints;
    }

    /**
     * @param array $context
     * @return int|null
     */
    public function getContainerIdFromContext(array $context)
    {
        return isset($context['resource_id']) ? $context['resource_id'] : null;
    }

    /**
     * @param Entity $container
     * @param array $extraParams
     * @return string
     */
    public function getContainerLink(Entity $container, array $extraParams = [])
    {
        return $container->app()->router('public')
            ->buildLink('group-resources', $container, $extraParams);
    }

    /**
     * @param Entity|null $entity
     * @param array $extraContext
     * @return array
     */
    public function getContext(Entity $entity = null, array $extraContext = [])
    {
        if ($entity instanceof \Truonglv\Groups\Entity\ResourceItem) {
            $extraContext['resource_id'] = $entity->resource_id;
        } elseif ($entity instanceof \Truonglv\Groups\Entity\Group) {
            $extraContext['group_id'] = $entity->group_id;
        }

        return $extraContext;
    }
}
