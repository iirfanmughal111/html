<?php

namespace DBTech\eCommerce\Warning;

use XF\Entity\Warning;
use XF\Mvc\Entity\Entity;
use XF\Warning\AbstractHandler;

/**
 * Class ProductRating
 *
 * @package DBTech\eCommerce\Warning
 */
class ProductRating extends AbstractHandler
{
	/**
	 * @param Entity $entity
	 *
	 * @return string
	 */
	public function getStoredTitle(Entity $entity): string
	{
		/** @var \DBTech\eCommerce\Entity\ProductRating $entity */
		return $entity->Product ? $entity->Product->title : '';
	}
	
	/**
	 * @param $title
	 *
	 * @return \XF\Phrase
	 */
	public function getDisplayTitle($title): \XF\Phrase
	{
		return \XF::phrase('dbtech_ecommerce_product_review_in_x', ['title' => $title]);
	}
	
	/**
	 * @param Entity $entity
	 *
	 * @return mixed|null
	 */
	public function getContentForConversation(Entity $entity)
	{
		/** @var \DBTech\eCommerce\Entity\ProductRating $entity */
		return $entity->message;
	}
	
	/**
	 * @param Entity $entity
	 * @param bool $canonical
	 *
	 * @return mixed|string
	 */
	public function getContentUrl(Entity $entity, $canonical = false): string
	{
		return \XF::app()->router('public')->buildLink(($canonical ? 'canonical:' : '') . 'dbtech-ecommerce/review', $entity);
	}
	
	/**
	 * @param Entity $entity
	 *
	 * @return mixed|null
	 */
	public function getContentUser(Entity $entity)
	{
		/** @var \DBTech\eCommerce\Entity\ProductRating $entity */
		return $entity->User;
	}
	
	/**
	 * @param Entity $entity
	 * @param null $error
	 *
	 * @return bool
	 */
	public function canViewContent(Entity $entity, &$error = null): bool
	{
		/** @var \DBTech\eCommerce\Entity\ProductRating $entity */
		return $entity->canView();
	}
	
	/**
	 * @param Entity $entity
	 * @param Warning $warning
	 *
	 * @throws \LogicException
	 * @throws \Exception
	 * @throws \XF\PrintableException
	 */
	public function onWarning(Entity $entity, Warning $warning)
	{
		/** @var \DBTech\eCommerce\Entity\ProductRating $entity */
		$entity->warning_id = $warning->warning_id;
		$entity->save();
	}
	
	/**
	 * @param Entity $entity
	 * @param Warning $warning
	 *
	 * @throws \LogicException
	 * @throws \Exception
	 * @throws \XF\PrintableException
	 */
	public function onWarningRemoval(Entity $entity, Warning $warning)
	{
		/** @var \DBTech\eCommerce\Entity\ProductRating $entity */
		$entity->warning_id = 0;
		$entity->save();
	}
	
	/**
	 * @param Entity $entity
	 * @param $action
	 * @param array $options
	 *
	 * @throws \InvalidArgumentException
	 * @throws \LogicException
	 * @throws \Exception
	 * @throws \XF\PrintableException
	 */
	public function takeContentAction(Entity $entity, $action, array $options)
	{
		/** @var \DBTech\eCommerce\Entity\ProductRating $entity */
		if ($action == 'delete')
		{
			$reason = isset($options['reason']) ? $options['reason'] : '';
			if (!is_string($reason))
			{
				$reason = '';
			}

			/** @var \DBTech\eCommerce\Service\ProductRating\Delete $deleter */
			$deleter = \XF::app()->service('DBTech\eCommerce:ProductRating\Delete', $entity);
			$deleter->delete('soft', $reason);
		}
	}
	
	/**
	 * @param Entity $entity
	 *
	 * @return bool|mixed
	 */
	protected function canDeleteContent(Entity $entity): bool
	{
		/** @var \DBTech\eCommerce\Entity\ProductRating $entity */
		return $entity->canDelete();
	}
	
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