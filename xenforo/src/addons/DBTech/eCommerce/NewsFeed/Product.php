<?php

namespace DBTech\eCommerce\NewsFeed;

use XF\NewsFeed\AbstractHandler;

/**
 * Class Product
 *
 * @package DBTech\eCommerce\NewsFeed
 */
class Product extends AbstractHandler
{
	/**
	 * @return array
	 */
	public function getEntityWith(): array
	{
		return [
			'User',
			'permissionSet'
		];
	}
}