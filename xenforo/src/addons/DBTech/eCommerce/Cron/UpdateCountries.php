<?php

namespace DBTech\eCommerce\Cron;

/**
 * Class UpdateCountries
 *
 * @package DBTech\eCommerce\Cron
 */
class UpdateCountries
{
	/**
	 * @throws \Exception
	 * @throws \XF\PrintableException
	 */
	public static function runUpdate()
	{
		$app = \XF::app();
		
		/** @var \DBTech\eCommerce\Repository\Country $countryRepo */
		$countryRepo = $app->repository('DBTech\eCommerce:Country');
		$countryRepo->updateCountryList();
	}
}