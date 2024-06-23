<?php

namespace DBTech\eCommerce\ContentVote;

use XF\ContentVote\AbstractHandler;
use XF\Mvc\Entity\Entity;

/**
 * Class ProductRating
 *
 * @package DBTech\eCommerce\ContentVote
 */
class ProductRating extends AbstractHandler
{
	/**
	 * @param \XF\Mvc\Entity\Entity $entity
	 *
	 * @return bool
	 */
	public function isCountedForContentUser(Entity $entity): bool
	{
		/** @var \DBTech\eCommerce\Entity\ProductRating $entity */

		if ($entity->is_anonymous)
		{
			return false;
		}

		return $entity->isVisible();
	}

	/**
	 * @return array
	 */
	public function getEntityWith(): array
	{
		$visitor = \XF::visitor();
		return [
			'Product',
			'Product.Category',
			'Product.Category.Permissions|' . $visitor->permission_combination_id
		];
	}
}