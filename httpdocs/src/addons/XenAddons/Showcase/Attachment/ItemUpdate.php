<?php

namespace XenAddons\Showcase\Attachment;

use XF\Attachment\AbstractHandler;
use XF\Entity\Attachment;
use XF\Mvc\Entity\Entity;

class ItemUpdate extends AbstractHandler
{
	public function getContainerWith()
	{
		$visitor = \XF::visitor();

		return ['Item', 'Item.Category', 'Item.Category.Permissions|' . $visitor->permission_combination_id];
	}

	public function canView(Attachment $attachment, Entity $container, &$error = null)
	{
		/** @var \XenAddons\Showcase\Entity\ItemUpdate $container */
		if (!$container->canView())
		{
			return false;
		}
		
		return $container->canViewUpdateImages();
	}

	public function canManageAttachments(array $context, &$error = null)
	{
		$category = $this->getCategoryFromContext($context);

		return $category && $category->canUploadAndManageUpdateImages();
	}

	public function onAttachmentDelete(Attachment $attachment, Entity $container = null)
	{
		if (!$container)
		{
			return;
		}

		/** @var \XenAddons\Showcase\Entity\ItemUpdate $container */
		$container->attach_count--;
		$container->save();
	}

	public function getConstraints(array $context)
	{
		/** @var \XF\Repository\Attachment $attachRepo */
		$attachRepo = \XF::repository('XF:Attachment');
		
		$constraints = \XF::repository('XenAddons\Showcase:Item')->getItemAttachmentConstraints();

		$category = $this->getCategoryFromContext($context);
		if ($category && $category->canUploadUpdateVideos())
		{
			$constraints = $attachRepo->applyVideoAttachmentConstraints($constraints);
		}
		
		return $constraints;
	}

	public function getContainerIdFromContext(array $context)
	{
		return isset($context['item_update_id']) ? intval($context['item_update_id']) : null;
	}

	public function getContainerLink(Entity $container, array $extraParams = [])
	{
		return \XF::app()->router('public')->buildLink('showcase/update', $container, $extraParams);
	}

	public function getContext(Entity $entity = null, array $extraContext = [])
	{
		if ($entity instanceof \XenAddons\Showcase\Entity\ItemUpdate)
		{
			$extraContext['item_update_id'] = $entity->item_update_id;
		}
		else if ($entity instanceof \XenAddons\Showcase\Entity\Item)
		{
			$extraContext['item_id'] = $entity->item_id;
		}
		else if ($entity instanceof \XenAddons\Showcase\Entity\Category)
		{
			$extraContext['category_id'] = $entity->category_id;
		}
		else
		{
			throw new \InvalidArgumentException("Entity must be item update, item or category");
		}

		return $extraContext;
	}
	
	protected function getCategoryFromContext(array $context)
	{
		$em = \XF::em();
		
		if (!empty($context['item_update_id']))
		{
			/** @var \XenAddons\Showcase\Entity\ItemUpdate $update */
			$update = $em->find('XenAddons\Showcase:ItemUpdate', intval($context['item_update_id']), ['Item']);
			if (!$update || !$update->canView() || !$update->canEdit())
			{
				return null;
			}
		
			$category = $update->Item->Category;
		}
		else if (!empty($context['item_id']))
		{
			/** @var \XenAddons\Showcase\Entity\Item $item */
			$item = $em->find('XenAddons\Showcase:Item', intval($context['item_id']));
			if (!$item || !$item->canView())
			{
				return null;
			}
			
			$category = $item->Category;
		}
		else if (!empty($context['category_id']))
		{
			/** @var \XenAddons\Showcase\Entity\Category $category */
			$category = $em->find('XenAddons\Showcase:Category', intval($context['category_id']));
			if (!$category || !$category->canView())
			{
				return null;
			}
		}
		else
		{
			return null;
		}
		
		return $category;		
	}
}