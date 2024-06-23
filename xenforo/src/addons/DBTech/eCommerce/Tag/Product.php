<?php

namespace DBTech\eCommerce\Tag;

use XF\Mvc\Entity\Entity;
use XF\Tag\AbstractHandler;

/**
 * Class Product
 *
 * @package DBTech\eCommerce\Tag
 */
class Product extends AbstractHandler
{
	/**
	 * @param Entity $entity
	 *
	 * @return array
	 * @throws \InvalidArgumentException
	 */
	public function getPermissionsFromContext(Entity $entity): array
	{
		if ($entity instanceof \DBTech\eCommerce\Entity\Product)
		{
			$product = $entity;
			$category = $product->Category;
		}
		elseif ($entity instanceof \DBTech\eCommerce\Entity\Category)
		{
			$product = null;
			$category = $entity;
		}
		else
		{
			throw new \InvalidArgumentException('Entity must be a product or category');
		}

		$visitor = \XF::visitor();

		if ($product)
		{
			if ($product->user_id == $visitor->user_id && $product->hasPermission('manageOthersTagsOwnProd'))
			{
				$removeOthers = true;
			}
			else
			{
				$removeOthers = $product->hasPermission('manageAnyTag');
			}

			$edit = $product->canEditTags();
		}
		else
		{
			$removeOthers = false;
			$edit = $category->canEditTags();
		}

		return [
			'edit' => $edit,
			'removeOthers' => $removeOthers,
			'minTotal' => $category->min_tags
		];
	}
	
	/**
	 * @param Entity $entity
	 *
	 * @return mixed|null
	 */
	public function getContentDate(Entity $entity)
	{
		return $entity->creation_date;
	}
	
	/**
	 * @param Entity $entity
	 *
	 * @return bool
	 */
	public function getContentVisibility(Entity $entity): bool
	{
		return $entity->product_state == 'visible';
	}
	
	/**
	 * @param Entity $entity
	 * @param array $options
	 *
	 * @return array
	 */
	public function getTemplateData(Entity $entity, array $options = []): array
	{
		return [
			'product' => $entity,
			'options' => $options
		];
	}
	
	/**
	 * @param bool $forView
	 *
	 * @return array
	 */
	public function getEntityWith($forView = false): array
	{
		$get = ['Category'];
		if ($forView)
		{
			$get[] = 'User';

			$visitor = \XF::visitor();
			$get[] = 'Permissions|' . $visitor->permission_combination_id;
			$get[] = 'Category.Permissions|' . $visitor->permission_combination_id;
		}

		return $get;
	}
	
	/**
	 * @param Entity $entity
	 * @param null $error
	 *
	 * @return bool
	 */
	public function canUseInlineModeration(Entity $entity, &$error = null): bool
	{
		/** @var \DBTech\eCommerce\Entity\Product $entity */
		return $entity->canUseInlineModeration($error);
	}
}