<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Attachment;

use XF;
use Truonglv\Groups\App;
use XF\Entity\Attachment;
use XF\Mvc\Entity\Entity;
use InvalidArgumentException;
use Truonglv\Groups\Entity\Group;
use XF\Attachment\AbstractHandler;

class Comment extends AbstractHandler
{
    /**
     * @param Attachment $attachment
     * @param Entity $container
     * @param mixed $error
     * @return bool
     */
    public function canView(Attachment $attachment, Entity $container, & $error = null)
    {
        if (!($container instanceof \Truonglv\Groups\Entity\Comment)) {
            return false;
        }

        return $container->canView($error);
    }

    /**
     * @param Attachment $attachment
     * @param Entity|null $container
     * @throws \XF\PrintableException
     * @return void
     */
    public function onAttachmentDelete(Attachment $attachment, Entity $container = null)
    {
        if ($container instanceof \Truonglv\Groups\Entity\Comment) {
            $container->attach_count--;
            $container->save();
        }
    }

    /**
     * @param array $context
     * @param mixed $error
     * @return bool
     */
    public function canManageAttachments(array $context, & $error = null)
    {
        /** @var Group|null $group */
        $group = null;

        $em = XF::em();
        if (isset($context['comment_id'])) {
            /** @var \Truonglv\Groups\Entity\Comment|null $comment */
            $comment = $em->find('Truonglv\Groups:Comment', $context['comment_id']);
            if ($comment === null || !$comment->canEdit($error)) {
                return false;
            }

            $group = $comment->getGroup();
        } elseif (isset($context['group_id'])) {
            /** @var Group|null $group */
            $group = $em->find('Truonglv\Groups:Group', $context['group_id']);
        } else {
            return false;
        }

        return $group !== null && $group->canUploadAndManageAttachments($error);
    }

    /**
     * @param array $context
     * @return int|null
     */
    public function getContainerIdFromContext(array $context)
    {
        return isset($context['comment_id']) ? (int) $context['comment_id'] : null;
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
     * @return string
     */
    public function getContainerLink(Entity $container, array $extraParams = [])
    {
        return $container
            ->app()
            ->router('public')
            ->buildLink('canonical:group-comments', $container, $extraParams);
    }

    /**
     * @param Entity|null $entity
     * @param array $extraContext
     * @return array
     */
    public function getContext(Entity $entity = null, array $extraContext = [])
    {
        if ($entity instanceof \Truonglv\Groups\Entity\Comment) {
            $extraContext['comment_id'] = $entity->comment_id;
        } elseif ($entity instanceof Group) {
            $extraContext['group_id'] = $entity->group_id;
        } else {
            throw new InvalidArgumentException('Entity must be Comment, Event or Group');
        }

        return $extraContext;
    }

    /**
     * @return array
     */
    public function getContainerWith()
    {
        return ['full'];
    }

    /**
     * @param mixed $id
     * @return \XF\Mvc\Entity\AbstractCollection|Entity|null
     */
    public function getContainerEntity($id)
    {
        $entity = parent::getContainerEntity($id);

        if ($entity !== null) {
            App::commentRepo()->addContentIntoComments($entity);
        }

        return $entity;
    }
}
