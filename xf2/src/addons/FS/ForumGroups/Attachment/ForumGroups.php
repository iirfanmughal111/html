<?php

namespace FS\ForumGroups\Attachment;

use XF;
use XF\Entity\Attachment;
use XF\Mvc\Entity\Entity;
use InvalidArgumentException;
use XF\Attachment\AbstractHandler;

class ForumGroups extends AbstractHandler
{
    /**
     * @param Attachment $attachment
     * @param Entity $container
     * @param mixed $error
     * @return bool
     */
    public function canView(Attachment $attachment, Entity $container, &$error = null)
    {
        if ($container instanceof \XF\Entity\Node) {
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
        if (!($container instanceof \XF\Entity\Node)) {
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
        return isset($context['node_id']) ? intval($context['node_id']) : null;
    }

    /**
     * @param array $context
     * @param mixed $error
     * @return bool
     */
    public function canManageAttachments(array $context, &$error = null)
    {
        /** @var Category|null $category */
        $category = null;
        $em = XF::em();

        if (isset($context['node_id'])) {
            /** @var \XF\Entity\Node|null $group */
            $subCommunity = $em->find('XF:Node', $context['node_id']);
            if ($subCommunity) {
                return true;
            }

            // if ($group === null || !$group->canEdit()) {
            //     return false;
            // }


            // $category = $group->Category;
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
            ->buildLink('canonical:forumGroups', $container, $extraParams);
    }

    /**
     * @param Entity|null $entity
     * @param array $extraContext
     * @return array
     */
    public function getContext(Entity $entity = null, array $extraContext = [])
    {
        if ($entity instanceof \XF\Entity\Node) {
            $extraContext['node_id'] = $entity->node_id;
        } else {
            throw new InvalidArgumentException('Entity must be Node or Subcommunity');
        }

        return $extraContext;
    }
}
