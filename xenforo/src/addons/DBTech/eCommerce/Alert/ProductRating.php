<?php

namespace DBTech\eCommerce\Alert;

use XF\Alert\AbstractHandler;

/**
 * Class ProductRating
 *
 * @package DBTech\eCommerce\Alert
 */
class ProductRating extends AbstractHandler
{
	/**
	 * @return array
	 */
	public function getEntityWith(): array
	{
		$visitor = \XF::visitor();
		return [
			'Product',
			'Product.permissionSet'
		];
	}
	
	/**
	 * @return array
	 */
	public function getOptOutActions(): array
	{
		return [
			'review',
		];
	}
	
	/**
	 * @return int
	 */
	public function getOptOutDisplayOrder(): int
	{
		return 405;
	}
}