<?php

namespace XenAddons\Showcase\NewsFeed;

use XF\Mvc\Entity\Entity;
use XF\NewsFeed\AbstractHandler;

class Rating extends AbstractHandler
{
	public function isPublishable(Entity $entity, $action)
	{
		/** @var \XenAddons\Showcase\Entity\ItemRating $entity */
		if (!$entity->is_review)
		{
			return false;
		}

		return true;
	}

	public function getEntityWith()
	{
		$visitor = \XF::visitor();

		return ['Item', 'Item.User', 'Item.Category', 'Item.Category.Permissions|' . $visitor->permission_combination_id];
	}
}