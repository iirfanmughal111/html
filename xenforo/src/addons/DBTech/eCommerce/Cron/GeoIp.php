<?php

namespace DBTech\eCommerce\Cron;

/**
 * Class GeoIp
 *
 * @package DBTech\eCommerce\Cron
 */
class GeoIp
{
	/**
	 *
	 */
	public static function geoIpUpdate()
	{
		$app = \XF::app();
		
		/** @var \DBTech\eCommerce\Repository\GeoIp $geoIpRepo */
		$geoIpRepo = $app->repository('DBTech\eCommerce:GeoIp');
		$geoIpRepo->geoIpUpdate();
	}
}