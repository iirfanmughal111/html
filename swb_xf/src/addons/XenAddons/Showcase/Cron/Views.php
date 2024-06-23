<?php

namespace XenAddons\Showcase\Cron;

class Views
{
	public static function runViewUpdate()
	{
		$app = \XF::app();

		/** @var \XenAddons\Showcase\Repository\Item $itemRepo */
		$itemRepo = $app->repository('XenAddons\Showcase:Item');
		$itemRepo->batchUpdateItemViews();
		
		/** @var \XenAddons\Showcase\Repository\Series $seriesRepo */
		$seriesRepo = $app->repository('XenAddons\Showcase:Series');
		$seriesRepo->batchUpdateSeriesViews();
	}
}