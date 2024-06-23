<?php

namespace XenAddons\Showcase\NewsFeed;

use XF\Mvc\Entity\Entity;
use XF\NewsFeed\AbstractHandler;

class ItemUpdateReply extends AbstractHandler
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

		return ['ItemUpdate', 'ItemUpdate.Item', 'ItemUpdate.Item.User', 'ItemUpdate.Item.Category', 'ItemUpdate.Item.Category.Permissions|' . $visitor->permission_combination_id];
	}
}