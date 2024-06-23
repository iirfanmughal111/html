<?php

namespace Z61\Classifieds\Attachment;

use XF\Attachment\AbstractHandler;
use XF\Entity\Attachment;
use XF\Mvc\Entity\Entity;

class Listing extends AbstractHandler
{
    public function getContainerWith()
    {
        $visitor = \XF::visitor();

        return ['Category', 'Category.Permissions|' . $visitor->permission_combination_id];
    }

    public function canView(Attachment $attachment, Entity $container, &$error = null)
    {
        /** @var $container \Z61\Classifieds\Entity\Listing */
        return $container->canView();
    }

    public function canManageAttachments(array $context, &$error = null)
    {
        $em = \XF::em();

        if (!empty($context['listing_id']))
        {
            /** @var \Z61\Classifieds\Entity\Listing $listing */
            $listing = $em->find('Z61\Classifieds:Listing', intval($context['listing_id']));
            if (!$listing || !$listing->canView() || !$listing->canEdit())
            {
                return false;
            }

            $category = $listing->Category;
        }
        else if (!empty($context['category_id']))
        {
            /** @var \Z61\Classifieds\Entity\Category $category */
            $category = $em->find('Z61\Classifieds:Category', intval($context['category_id']));
            if (!$category || !$category->canView() || !$category->canAddListing())
            {
                return false;
            }
        }
        else
        {
            return false;
        }

        return $category->canUploadAndManageAttachments();
    }

    public function onAttachmentDelete(Attachment $attachment, Entity $container = null)
    {
        if (!$container)
        {
            return;
        }

        /** @var \Z61\Classifieds\Entity\Listing $container */
        $container->attach_count--;
        $container->save();
    }

    public function getConstraints(array $context)
    {
        $constraints = \XF::repository('XF:Attachment')->getDefaultAttachmentConstraints();
        $constraints['extensions'] = ['jpg', 'jpeg', 'jpe', 'png', 'gif'];

        return $constraints;
    }

    public function getContainerIdFromContext(array $context)
    {
        return isset($context['listing_id']) ? intval($context['listing_id']) : null;
    }

    public function getContainerLink(Entity $container, array $extraParams = [])
    {
        return \XF::app()->router('public')->buildLink('classifieds', $container, $extraParams);
    }

    public function getContext(Entity $entity = null, array $extraContext = [])
    {
        if ($entity instanceof \Z61\Classifieds\Entity\Listing)
        {
            $extraContext['listing_id'] = $entity->listing_id;
        }
        else if ($entity instanceof \Z61\Classifieds\Entity\Category)
        {
            $extraContext['category_id'] = $entity->category_id;
        }
        else
        {
            throw new \InvalidArgumentException("Entity must be listing or category");
        }

        return $extraContext;
    }
}