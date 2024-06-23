<?php

namespace DBTech\eCommerce\Reaction;

use XF\Reaction\AbstractHandler;
use XF\Mvc\Entity\Entity;

/**
 * Class Product
 * @package DBTech\eCommerce\Reaction
 */
class Product extends AbstractHandler
{
	/**
	 * @param Entity $entity
	 * @return mixed
	 */
	public function reactionsCounted(Entity $entity): bool
	{
		if (!$entity->Category)
		{
			return false;
		}
		
		return $entity->product_state == 'visible';
	}

	/**
	 * @param Entity $entity
	 * @return int|mixed|null
	 */
	public function getContentUserId(Entity $entity): ?int
	{
		return $entity->user_id;
	}
	
	/**
	 * @return array
	 */
	public function getEntityWith(): array
	{
		return [
			'permissionSet'
		];
	}
}