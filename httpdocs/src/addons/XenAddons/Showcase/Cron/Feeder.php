<?php

namespace XenAddons\Showcase\Cron;

/**
 * Cron entry for showcase feed importer.
 */
class Feeder
{
	/**
	 * Imports feeds.
	 */
	public static function importFeeds()
	{
		$app = \XF::app();

		/** @var \XenAddons\Showcase\Repository\Feed $feedRepo */
		$feedRepo = $app->repository('XenAddons\Showcase:Feed');

		$dueFeeds = $feedRepo->findDueFeeds()->fetch();
		if ($dueFeeds->count())
		{
			$app->jobManager()->enqueueUnique('scFeederImport', 'XenAddons\Showcase:Feeder', [], false);
		}
	}
}