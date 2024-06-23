<?php

namespace DBTech\eCommerce\NewsFeed;

use XF\Mvc\Entity\Entity;
use XF\NewsFeed\AbstractHandler;

/**
 * Class ProductRating
 *
 * @package DBTech\eCommerce\NewsFeed
 */
class ProductRating extends AbstractHandler
{
	/**
	 * @param Entity $entity
	 * @param $action
	 *
	 * @return bool
	 */
	public function isPublishable(Entity $entity, $action): bool
	{
		/** @var \DBTech\eCommerce\Entity\ProductRating $entity */
		if (!$entity->is_review)
		{
			return false;
		}

		return true;
	}
	
	/**
	 * @return array
	 */
	public function getEntityWith(): array
	{
		return [
			'Product',
			'Product.User',
			'Product.permissionSet'
		];
	}
}