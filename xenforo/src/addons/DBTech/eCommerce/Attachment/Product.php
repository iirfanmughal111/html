<?php

namespace DBTech\eCommerce\Attachment;

use XF\Attachment\AbstractHandler;
use XF\Entity\Attachment;
use XF\Mvc\Entity\Entity;

/**
 * Class Product
 * @package DBTech\eCommerce\Attachment
 */
class Product extends AbstractHandler
{
	/**
	 * @return array
	 */
	public function getContainerWith(): array
	{
		$visitor = \XF::visitor();
		
		return ['Permissions|' . $visitor->permission_combination_id, 'Category', 'Category.Permissions|' . $visitor->permission_combination_id];
	}
	
	/**
	 * @param Attachment $attachment
	 * @param Entity $container
	 * @param null $error
	 * @return bool
	 */
	public function canView(Attachment $attachment, Entity $container, &$error = null): bool
	{
		if (\XF::app()->get('app.classType') == 'Admin')
		{
			// We're in the AdminCP
			return true;
		}

		/** @var \DBTech\eCommerce\Entity\Product $container */
		if (!$container->canView())
		{
			return false;
		}

		return $container->canViewProductImages();
	}

	/**
	 * @param array $context
	 * @param null $error
	 * @return bool
	 */
	public function canManageAttachments(array $context, &$error = null): bool
	{
		$em = \XF::em();

		if (!empty($context['product_id']))
		{
			/** @var \DBTech\eCommerce\Entity\Product $product */
			$product = $em->find('DBTech\eCommerce:Product', (int)$context['product_id']);
			if (!$product || !$product->canView() || !$product->canEdit())
			{
				return false;
			}

			return $product->canUploadAndManageAttachments();
		}
		
		if (!empty($context['category_id']))
		{
			/** @var \DBTech\eCommerce\Entity\Category $category */
			$category = $em->find('DBTech\eCommerce:Category', (int)$context['category_id']);
			return !(!$category || !$category->canView() || !$category->canAddProduct());
		}

		return false;
	}
	
	/**
	 * @param Attachment $attachment
	 * @param Entity|null $container
	 *
	 * @throws \LogicException
	 * @throws \Exception
	 * @throws \XF\PrintableException
	 */
	public function onAttachmentDelete(Attachment $attachment, ?Entity $container = null)
	{
		if (!$container)
		{
			return;
		}

		/** @var \DBTech\eCommerce\Entity\Product $container */
		$container->attach_count--;
		$container->save();
	}

	/**
	 * @param array $context
	 * @return mixed
	 */
	public function getConstraints(array $context)
	{
		/** @var \XF\Repository\Attachment $attachRepo */
		$attachRepo = \XF::repository('XF:Attachment');

		$constraints = $attachRepo->getDefaultAttachmentConstraints();
		$constraints['extensions'] = ['jpg', 'jpeg', 'jpe', 'png', 'gif'];

		return $constraints;
	}

	/**
	 * @param array $context
	 * @return int|null
	 */
	public function getContainerIdFromContext(array $context): ?int
	{
		return isset($context['product_id']) ? (int)$context['product_id'] : null;
	}

	/**
	 * @param Entity $container
	 * @param array $extraParams
	 * @return mixed|string
	 */
	public function getContainerLink(Entity $container, array $extraParams = []): string
	{
		return \XF::app()->router('public')->buildLink('dbtech-ecommerce/product/edit', $container, $extraParams);
	}
	
	/**
	 * @param Entity|null $entity
	 * @param array $extraContext
	 * @return array
	 * @throws \InvalidArgumentException
	 */
	public function getContext(Entity $entity = null, array $extraContext = []): array
	{
		if ($entity instanceof \DBTech\eCommerce\Entity\Product)
		{
			$extraContext['product_id'] = $entity->product_id;
		}
		elseif ($entity instanceof \DBTech\eCommerce\Entity\Category)
		{
			$extraContext['category_id'] = $entity->category_id;
		}
		else
		{
			throw new \InvalidArgumentException('Entity must be product or category');
		}

		return $extraContext;
	}
}