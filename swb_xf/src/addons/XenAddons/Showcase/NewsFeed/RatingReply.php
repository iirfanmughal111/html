<?php

namespace XenAddons\Showcase\NewsFeed;

use XF\Mvc\Entity\Entity;
use XF\NewsFeed\AbstractHandler;

class RatingReply extends AbstractHandler
{
	public function isPublishable(Entity $entity, $action)
	{
		if ($action == 'insert')
		{

		}

		return true;
	}

	public function getEntityWith()
	{
		$visitor = \XF::visitor();

		return ['ItemRating', 'ItemRating.Item', 'ItemRating.Item.User', 'ItemRating.Item.Category', 'ItemRating.Item.Category.Permissions|' . $visitor->permission_combination_id];
	}
}