<?php

namespace DBTech\Credits\Cron;

/**
 * Class Event
 *
 * @package DBTech\Credits\Cron
 */
class Event
{
	/**
	 * @throws \Exception
	 */
	public static function birthday()
	{
		/** @var \DBTech\Credits\Repository\EventTrigger $eventTriggerRepo */
		$eventTriggerRepo = \XF::repository('DBTech\Credits:EventTrigger');
		$eventTriggerRepo->cronBirthday();
	}

	/**
	 * @throws \Exception
	 */
	public static function dailyCredits()
	{
		/** @var \DBTech\Credits\Repository\EventTrigger $eventTriggerRepo */
		$eventTriggerRepo = \XF::repository('DBTech\Credits:EventTrigger');

		$daily = $eventTriggerRepo->getHandler('daily');
		$interest = $eventTriggerRepo->getHandler('interest');
		$taxation = $eventTriggerRepo->getHandler('taxation');
		$paycheck = $eventTriggerRepo->getHandler('paycheck');

		if ($daily->isActive() || $interest->isActive() || $taxation->isActive() || $paycheck->isActive())
		{
			\XF::app()->jobManager()->enqueueUnique(
				'dbtechCreditsDaily',
				'DBTech\Credits:DailyCredits',
				[],
				false
			);
		}
	}
}