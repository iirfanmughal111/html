<?php

namespace DBTech\eCommerce\Alert;

use XF\Alert\AbstractHandler;

/**
 * Class License
 *
 * @package DBTech\eCommerce\Alert
 */
class License extends AbstractHandler
{
	/**
	 * @return array
	 */
	public function getOptOutActions(): array
	{
		return [
			'expiring',
			'expired',
		];
	}

	/**
	 * @return int
	 */
	public function getOptOutDisplayOrder(): int
	{
		return 395;
	}
}