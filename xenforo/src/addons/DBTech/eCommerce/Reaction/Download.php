<?php

namespace DBTech\eCommerce\Reaction;

use XF\Reaction\AbstractHandler;
use XF\Mvc\Entity\Entity;

/**
 * Class Download
 * @package DBTech\eCommerce\Reaction
 */
class Download extends AbstractHandler
{
	/**
	 * @param Entity $entity
	 * @return mixed
	 */
	public function reactionsCounted(Entity $entity): bool
	{
		if (!$entity->Product || !$entity->Product->Category)
		{
			return false;
		}
		
		return ($entity->download_state == 'visible' && $entity->Product->product_state == 'visible');
	}

	/**
	 * @param Entity $entity
	 * @return int|mixed|null
	 */
	public function getContentUserId(Entity $entity): ?int
	{
		return $entity->Product->user_id;
	}

	/**
	 * @return array
	 */
	public function getEntityWith(): array
	{
		return [
			'Product',
			'Product.permissionSet'
		];
	}
}