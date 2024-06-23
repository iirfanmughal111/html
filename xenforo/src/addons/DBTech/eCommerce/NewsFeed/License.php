<?php

namespace DBTech\eCommerce\NewsFeed;

use XF\NewsFeed\AbstractHandler;

/**
 * Class License
 *
 * @package DBTech\eCommerce\NewsFeed
 */
class License extends AbstractHandler
{
	/**
	 * @return array
	 */
	public function getEntityWith(): array
	{
		return [
			'User',
			'Product',
			'Product.permissionSet'
		];
	}
}