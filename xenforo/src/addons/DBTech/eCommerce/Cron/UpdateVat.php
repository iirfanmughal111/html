<?php

namespace DBTech\eCommerce\Cron;

/**
 * Class UpdateVat
 *
 * @package DBTech\eCommerce\Cron
 */
class UpdateVat
{
	
	/**
	 * @throws \Exception
	 * @throws \UnexpectedValueException
	 */
	public static function runUpdate()
	{
		$app = \XF::app();
		
		if (!$app->options()->dbtechEcommerceSalesTax['enableVat'])
		{
			return;
		}
		
		/** @var \DBTech\eCommerce\Repository\Country $countryRepo */
		$countryRepo = $app->repository('DBTech\eCommerce:Country');
		$countryRepo->updateVatRates();
	}
}