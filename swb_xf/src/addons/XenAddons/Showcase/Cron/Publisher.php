<?php

namespace XenAddons\Showcase\Cron;

class Publisher
{
	
	public static function runPublishScheduledItems()
	{
		$app = \XF::app();

		/** @var \XenAddons\Showcase\Repository\Item $itemRepo */
		$itemRepo = $app->repository('XenAddons\Showcase:Item');
		$itemRepo->publishScheduledItems();
	}
}