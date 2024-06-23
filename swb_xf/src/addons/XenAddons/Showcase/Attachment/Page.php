<?php

namespace XenAddons\Showcase\Attachment;

use XF\Attachment\AbstractHandler;
use XF\Entity\Attachment;
use XF\Mvc\Entity\Entity;

class Page extends AbstractHandler
{
	public function getContainerWith()
	{
		$visitor = \XF::visitor();
		
		return ['Item', 'Item.Category', 'Item.Category.Permissions|' . $visitor->permission_combination_id];
	}

	public function canView(Attachment $attachment, Entity $container, &$error = null)
	{
		/** @var \XenAddons\Showcase\Entity\ItemPage $container */
		if (!$container->canView())
		{
			return false;
		}
		
		return $container->canViewAttachments();
	}

	public function canManageAttachments(array $context, &$error = null)
	{
		$category = $this->getCategoryFromContext($context);

		return $category && $category->canUploadAndManagePageAttachments();
	}

	public function onAttachmentDelete(Attachment $attachment, Entity $container = null)
	{
		if (!$container)
		{
			return;
		}

		/** @var \XenAddons\Showcase\Entity\ItemPage $container */
		$container->attach_count--;
		$container->save();
	}

	public function getConstraints(array $context)
	{
		/** @var \XF\Repository\Attachment $attachRepo */
		$attachRepo = \XF::repository('XF:Attachment');
		
		$constraints = \XF::repository('XenAddons\Showcase:Item')->getItemAttachmentConstraints();

		$category = $this->getCategoryFromContext($context);
		
		$maxAllowedAttachmentsPerItemPage = -1;
		
		if ($category)
		{
			$maxAllowedAttachmentsPerItemPage = $category->getMaxAllowedAttachmentsPerItem();
		}
		
		if ($maxAllowedAttachmentsPerItemPage == 0) // in this case, we want 0 to count as unlimited
		{
			$maxAllowedAttachmentsPerItemPage = -1;
		}
		$constraints['count'] = $maxAllowedAttachmentsPerItemPage;
		
		if ($category && $category->canUploadItemVideos())
		{
			$constraints = $attachRepo->applyVideoAttachmentConstraints($constraints);
		}
		
		return $constraints;
	}

	public function getContainerIdFromContext(array $context)
	{
		return isset($context['page_id']) ? intval($context['page_id']) : null;
	}

	public function getContainerLink(Entity $container, array $extraParams = [])
	{
		return \XF::app()->router('public')->buildLink('showcase/page', $container, $extraParams);
	}

	public function getContext(Entity $entity = null, array $extraContext = [])
	{
		if ($entity instanceof \XenAddons\Showcase\Entity\ItemPage)
		{
			$extraContext['page_id'] = $entity->page_id;
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
			throw new \InvalidArgumentException("Entity must be a page, item or category");
		}

		return $extraContext;
	}
	
	protected function getCategoryFromContext(array $context)
	{
		$em = \XF::em();

		if (!empty($context['page_id']))
		{
			/** @var \XenAddons\Showcase\Entity\ItemPage $page */
			$page = $em->find('XenAddons\Showcase:ItemPage', intval($context['page_id']), ['Item']);
			if (!$page || !$page->canView() || !$page->canEdit())
			{
				return null;
			}
		
			$category = $page->Item->Category;
		}
		else if (!empty($context['item_id']))
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