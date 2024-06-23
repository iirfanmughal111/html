<?php

namespace XenAddons\Showcase\Cron;

class AutoFeature
{
	public static function runAutoFeatureItems()
	{
		$app = \XF::app();

		/** @var \XenAddons\Showcase\Repository\Item $itemRepo */
		$itemRepo = $app->repository('XenAddons\Showcase:Item');
		$itemRepo->autoFeatureItems();
	}
}