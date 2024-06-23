<?php

namespace XenAddons\Showcase\ContentVote;

use XF\ContentVote\AbstractHandler;
use XF\Mvc\Entity\Entity;

class ItemRating extends AbstractHandler
{
	public function isCountedForContentUser(Entity $entity)
	{
		/** @var \XenAddons\Showcase\Entity\ItemRating $entity */

		if ($entity->is_anonymous)
		{
			return false;
		}

		return $entity->isVisible();
	}

	public function getEntityWith()
	{
		$visitor = \XF::visitor();
		return ['Item', 'Item.Category', 'Item.Category.Permissions|' . $visitor->permission_combination_id];
	}
}