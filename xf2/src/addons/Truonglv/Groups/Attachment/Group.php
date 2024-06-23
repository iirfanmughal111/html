<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Attachment;

use XF;
use XF\Entity\Attachment;
use XF\Mvc\Entity\Entity;
use InvalidArgumentException;
use XF\Attachment\AbstractHandler;
use Truonglv\Groups\Entity\Category;

class Group extends AbstractHandler
{
    /**
     * @param Attachment $attachment
     * @param Entity $container
     * @param mixed $error
     * @return bool
     */
    public function canView(Attachment $attachment, Entity $container, & $error = null)
    {
        if ($container instanceof \Truonglv\Groups\Entity\Group) {
            return $container->canView($error);
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
        if (!($container instanceof \Truonglv\Groups\Entity\Group)) {
            return;
        }

        if ($attachment->attachment_id === $container->cover_attachment_id) {
            $container->cover_attachment_id = 0;
            $container->cover_crop_data = [];
        } elseif ($attachment->attachment_id === $container->avatar_attachment_id) {
            $container->avatar_attachment_id = 0;
        }

        $container->saveIfChanged();
    }

    /**
     * @param array $context
     * @return int|null
     */
    public function getContainerIdFromContext(array $context)
    {
        return isset($context['group_id']) ? intval($context['group_id']) : null;
    }

    /**
     * @param array $context
     * @param mixed $error
     * @return bool
     */
    public function canManageAttachments(array $context, & $error = null)
    {
        /** @var Category|null $category */
        $category = null;
        $em = XF::em();

        if (isset($context['group_id'])) {
            /** @var \Truonglv\Groups\Entity\Group|null $group */
            $group = $em->find('Truonglv\Groups:Group', $context['group_id']);
            if ($group === null || !$group->canEdit()) {
                return false;
            }

            $category = $group->Category;
        } elseif (isset($context['category_id'])) {
            /** @var Category|null $category */
            $category = $em->find('Truonglv\Groups:Category', $context['category_id']);
        }

        return $category !== null && $category->canUploadAndManageAttachments($error);
    }

    /**
     * @param array $context
     * @return array
     */
    public function getConstraints(array $context)
    {
        /** @var \XF\Repository\Attachment $attachRepo */
        $attachRepo = XF::repository('XF:Attachment');

        return $attachRepo->getDefaultAttachmentConstraints();
    }

    /**
     * @param Entity $container
     * @param array $extraParams
     * @return mixed|string
     */
    public function getContainerLink(Entity $container, array $extraParams = [])
    {
        return $container
            ->app()
            ->router('public')
            ->buildLink('canonical:groups', $container, $extraParams);
    }

    /**
     * @param Entity|null $entity
     * @param array $extraContext
     * @return array
     */
    public function getContext(Entity $entity = null, array $extraContext = [])
    {
        if ($entity instanceof Category) {
            $extraContext['category_id'] = $entity->category_id;
        } elseif ($entity instanceof \Truonglv\Groups\Entity\Group) {
            $extraContext['group_id'] = $entity->group_id;
        } else {
            throw new InvalidArgumentException('Entity must be Category or Group');
        }

        return $extraContext;
    }
}
