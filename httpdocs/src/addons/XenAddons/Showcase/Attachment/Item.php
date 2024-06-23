<?php

namespace XenAddons\Showcase\Attachment;

use XF\Attachment\AbstractHandler;
use XF\Entity\Attachment;
use XF\Mvc\Entity\Entity;

class Item extends AbstractHandler
{
	public function getContainerWith()
	{
		$visitor = \XF::visitor();
		
		return ['Category', 'Category.Permissions|' . $visitor->permission_combination_id];
	}

	public function canView(Attachment $attachment, Entity $container, &$error = null)
	{
		/** @var \XenAddons\Showcase\Entity\Item $container */
		if (!$container->canView())
		{
			return false;
		}
		
		return $container->canViewItemAttachments();
	}

	public function canManageAttachments(array $context, &$error = null)
	{
		$category = $this->getCategoryFromContext($context);

		return $category && $category->canUploadAndManageItemAttachments();
	}

	public function onAttachmentDelete(Attachment $attachment, Entity $container = null)
	{
		if (!$container)
		{
			return;
		}

		/** @var \XenAddons\Showcase\Entity\Item $container */
		$container->attach_count--;
		$container->save();
	}

	public function getConstraints(array $context)
	{
		/** @var \XF\Repository\Attachment $attachRepo */
		$attachRepo = \XF::repository('XF:Attachment');
		
		$constraints = \XF::repository('XenAddons\Showcase:Item')->getItemAttachmentConstraints();
		
		$category = $this->getCategoryFromContext($context);
		
		$maxAllowedAttachmentsPerItem = -1;
		
		if ($category)
		{
			$maxAllowedAttachmentsPerItem = $category->getMaxAllowedAttachmentsPerItem();
		}
		
		if ($maxAllowedAttachmentsPerItem == 0) // in this case, we want 0 to count as unlimited
		{
			$maxAllowedAttachmentsPerItem = -1;
		}
		$constraints['count'] = $maxAllowedAttachmentsPerItem;
		
		if ($category && $category->canUploadItemVideos())
		{
			$constraints = $attachRepo->applyVideoAttachmentConstraints($constraints);
		}
		
		return $constraints;
	}

	public function getContainerIdFromContext(array $context)
	{
		return isset($context['item_id']) ? intval($context['item_id']) : null;
	}

	public function getContainerLink(Entity $container, array $extraParams = [])
	{
		return \XF::app()->router('public')->buildLink('showcase', $container, $extraParams);
	}

	public function getContext(Entity $entity = null, array $extraContext = [])
	{
		if ($entity instanceof \XenAddons\Showcase\Entity\Item)
		{
			$extraContext['item_id'] = $entity->item_id;
		}
		else if ($entity instanceof \XenAddons\Showcase\Entity\Category)
		{
			$extraContext['category_id'] = $entity->category_id;
		}
		else
		{
			throw new \InvalidArgumentException("Entity must be item or category");
		}

		return $extraContext;
	}
	
	protected function getCategoryFromContext(array $context)
	{
		$em = \XF::em();
		
		if (!empty($context['item_id']))
		{
			/** @var \XenAddons\Showcase\Entity\Item $item */
			$item = $em->find('XenAddons\Showcase:Item', intval($context['item_id']), ['Category']);
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
			if (!$category || !$category->canView() || !$category->canAddItem())
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