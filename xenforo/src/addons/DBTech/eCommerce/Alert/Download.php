<?php

namespace DBTech\eCommerce\Alert;

use XF\Alert\AbstractHandler;

/**
 * Class Download
 *
 * @package DBTech\eCommerce\Alert
 */
class Download extends AbstractHandler
{
	/**
	 * @return array
	 */
	public function getEntityWith(): array
	{
		return [
			'Product',
			'Product.permissionSet',
		];
	}
	
	/**
	 * @return array
	 */
	public function getOptOutActions(): array
	{
		return [
			'insert',
			'reaction'
		];
	}
	
	/**
	 * @return int
	 */
	public function getOptOutDisplayOrder(): int
	{
		return 400;
	}
}