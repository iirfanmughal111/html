<?php

namespace XenAddons\Showcase\Tag;

use XF\Mvc\Entity\Entity;
use XF\Tag\AbstractHandler;

class Item extends AbstractHandler
{
	public function getPermissionsFromContext(Entity $entity)
	{
		if ($entity instanceof \XenAddons\Showcase\Entity\Item)
		{
			$item = $entity;
			$category = $item->Category;
		}
		else if ($entity instanceof \XenAddons\Showcase\Entity\Category)
		{
			$item = null;
			$category = $entity;
		}
		else
		{
			throw new \InvalidArgumentException("Entity must be an item or category");
		}

		$visitor = \XF::visitor();

		if ($item)
		{
			if ($item->user_id == $visitor->user_id && $item->hasPermission('manageOthersTagsOwnArt'))
			{
				$removeOthers = true;
			}
			else
			{
				$removeOthers = $item->hasPermission('manageAnyTag');
			}

			$edit = $item->canEditTags();
		}
		else
		{
			$removeOthers = false;
			$edit = $category->canEditTags();
		}

		return [
			'edit' => $edit,
			'removeOthers' => $removeOthers,
			'minTotal' => $category->min_tags
		];
	}

	public function getContentDate(Entity $entity)
	{
		return $entity->create_date;
	}

	public function getContentVisibility(Entity $entity)
	{
		return $entity->item_state == 'visible';
	}

	public function getTemplateData(Entity $entity, array $options = [])
	{
		return [
			'item' => $entity,
			'options' => $options
		];
	}

	public function getEntityWith($forView = false)
	{
		$get = ['Category'];
		if ($forView)
		{
			$get[] = 'User';

			$visitor = \XF::visitor();
			$get[] = 'Category.Permissions|' . $visitor->permission_combination_id;
		}

		return $get;
	}

	public function canUseInlineModeration(Entity $entity, &$error = null)
	{
		/** @var \XenAddons\Showcase\Entity\Item $entity */
		return $entity->canUseInlineModeration($error);
	}
}