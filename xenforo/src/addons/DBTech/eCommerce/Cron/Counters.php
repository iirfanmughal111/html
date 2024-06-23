<?php

namespace DBTech\eCommerce\Cron;

/**
 * Class Counters
 *
 * @package DBTech\eCommerce\Cron
 */
class Counters
{
	/**
	 * Log daily statistics
	 */
	public static function recordDailyIncomeStats()
	{
		/** @var \DBTech\eCommerce\Repository\IncomeStats $statsRepo */
		$statsRepo = \XF::app()->repository('DBTech\eCommerce:IncomeStats');
		
		// get the the timestamp of 00:00 UTC for today
		$time = \XF::$time - \XF::$time % 86400;
		$statsRepo->build($time - 86400, $time);
	}
}