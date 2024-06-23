<?php

namespace XenAddons\Showcase\Attachment;

use XF\Attachment\AbstractHandler;
use XF\Entity\Attachment;
use XF\Mvc\Entity\Entity;

class Comment extends AbstractHandler
{
	public function getContainerWith()
	{
		$visitor = \XF::visitor();
		
		return ['Item', 'Item.Category', 'Item.Category.Permissions|' . $visitor->permission_combination_id];
	}

	public function canView(Attachment $attachment, Entity $container, &$error = null)
	{
		/** @var \XenAddons\Showcase\Entity\Comment $container */
		if (!$container->canView())
		{
			return false;
		}
		
		return $container->canViewCommentImages();
	}

	public function canManageAttachments(array $context, &$error = null)
	{
		$category = $this->getCategoryFromContext($context);

		return $category && $category->canUploadAndManageCommentImages();
	}

	public function onAttachmentDelete(Attachment $attachment, Entity $container = null)
	{
		if (!$container)
		{
			return;
		}

		/** @var \XenAddons\Showcase\Entity\Comment $container */
		$container->attach_count--;
		$container->save();
	}

	public function getConstraints(array $context)
	{
		/** @var \XF\Repository\Attachment $attachRepo */
		$attachRepo = \XF::repository('XF:Attachment');
		
		$constraints = \XF::repository('XenAddons\Showcase:Comment')->getCommentAttachmentConstraints();

		$category = $this->getCategoryFromContext($context);
		if ($category && $category->canUploadCommentVideos())
		{
			$constraints = $attachRepo->applyVideoAttachmentConstraints($constraints);
		}
		
		return $constraints;
	}

	public function getContainerIdFromContext(array $context)
	{
		return isset($context['comment_id']) ? intval($context['comment_id']) : null;
	}

	public function getContainerLink(Entity $container, array $extraParams = [])
	{
		return \XF::app()->router('public')->buildLink('showcase/comments', $container, $extraParams);
	}

	public function getContext(Entity $entity = null, array $extraContext = [])
	{
		if ($entity instanceof \XenAddons\Showcase\Entity\Comment)
		{
			$extraContext['comment_id'] = $entity->comment_id;
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
			throw new \InvalidArgumentException("Entity must be comment, item or category");
		}

		return $extraContext;
	}
	
	protected function getCategoryFromContext(array $context)
	{
		$em = \XF::em();
		
		if (!empty($context['comment_id']))
		{
			/** @var \XenAddons\Showcase\Entity\Comment $comment */
			$comment = $em->find('XenAddons\Showcase:Comment', intval($context['comment_id']), ['Item']);
			if (!$comment || !$comment->canView() || !$comment->canEdit())
			{
				return null;
			}
		
			$category = $comment->Item->Category;
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