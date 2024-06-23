<?php

namespace DBTech\eCommerce\Alert;

use XF\Alert\AbstractHandler;
use XF\Mvc\Entity\Entity;

/**
 * Class Order
 *
 * @package DBTech\eCommerce\Alert
 */
class Order extends AbstractHandler
{
	/**
	 * @param \XF\Mvc\Entity\Entity $entity
	 * @param null $error
	 *
	 * @return bool
	 */
	public function canViewContent(Entity $entity, &$error = null): bool
	{
		return true;
	}
	
	/**
	 * @return array
	 */
	public function getOptOutActions(): array
	{
		return [
			'shipped',
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