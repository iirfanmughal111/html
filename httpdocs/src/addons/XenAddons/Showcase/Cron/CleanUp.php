<?php

namespace XenAddons\Showcase\Cron;

class CleanUp
{
	public static function runHourlyCleanUp()
	{
		$app = \XF::app();
	
		/** @var \XenAddons\Showcase\Repository\ItemReplyBan $itemReplyBanRepo */
		$itemReplyBanRepo = $app->repository('XenAddons\Showcase:ItemReplyBan');
		$itemReplyBanRepo->cleanUpExpiredBans();
	}
	
	public static function runDailyCleanUp()
	{
		$app = \XF::app();

		/** @var \XenAddons\Showcase\Repository\Item $itemRepo */
		$itemRepo = $app->repository('XenAddons\Showcase:Item');
		$itemRepo->pruneItemReadLogs();
		$itemRepo->autoUnfeatureItems();
	}
}