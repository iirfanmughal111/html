<?php

namespace DBTech\eCommerce\Repository;

use XF\Mvc\Entity\ArrayCollection;
use XF\Mvc\Entity\Repository;

/**
 * Class Product
 * @package DBTech\eCommerce\Repository
 */
class Product extends Repository
{
	/**
	 * @param array|null $viewableCategoryIds
	 * @param array $limits
	 *
	 * @return \DBTech\eCommerce\Finder\Product
	 * @throws \InvalidArgumentException
	 */
	public function findProductsForOverviewList(array $viewableCategoryIds = null, array $limits = []): \DBTech\eCommerce\Finder\Product
	{
		$limits = array_replace([
			'visibility' => true,
			'allowOwnPending' => false
		], $limits);
		
		/** @var \DBTech\eCommerce\Finder\Product $productFinder */
		$productFinder = $this->finder('DBTech\eCommerce:Product')
			->with('Sale')
			->with('LatestVersion')
			->with('Permissions|' . \XF::visitor()->permission_combination_id);
		
		if (is_array($viewableCategoryIds))
		{
			$productFinder->where('product_category_id', $viewableCategoryIds);
		}
		else
		{
			$productFinder->with('Category.Permissions|' . \XF::visitor()->permission_combination_id);
		}
		
		$productFinder
			->where('parent_product_id', 0)
			->where('is_listed', true)
		;
		
		$productFinder
			->with('full|category')
			->useDefaultOrder();
		
		if ($limits['visibility'])
		{
			$productFinder->applyGlobalVisibilityChecks($limits['allowOwnPending']);
		}
		
		return $productFinder;
	}

	/**
	 * @param array|null $viewableCategoryIds
	 *
	 * @return \DBTech\eCommerce\Finder\Product
	 * @throws \InvalidArgumentException
	 */
	public function findNewProducts(array $viewableCategoryIds = null): \DBTech\eCommerce\Finder\Product
	{
		/** @var \DBTech\eCommerce\Finder\Product $productFinder */
		$productFinder = $this->finder('DBTech\eCommerce:Product');

		if (is_array($viewableCategoryIds))
		{
			$productFinder->where('product_category_id', $viewableCategoryIds);
		}
		else
		{
			$productFinder->with('Category.Permissions|' . \XF::visitor()->permission_combination_id);
		}

		$productFinder
			->where('product_state', 'visible')
			->where('parent_product_id', 0)
			->where('is_listed', true)
			->with('User')
			->with('Permissions|' . \XF::visitor()->permission_combination_id)
			->order('last_update', 'desc');

		return $productFinder;
	}

	/**
	 * @param array|null $viewableCategoryIds
	 *
	 * @return \DBTech\eCommerce\Finder\Product
	 * @throws \InvalidArgumentException
	 */
	public function findRandomProducts(array $viewableCategoryIds = null): \DBTech\eCommerce\Finder\Product
	{
		/** @var \DBTech\eCommerce\Finder\Product $productFinder */
		$productFinder = $this->finder('DBTech\eCommerce:Product');

		if (is_array($viewableCategoryIds))
		{
			$productFinder->where('product_category_id', $viewableCategoryIds);
		}
		else
		{
			$productFinder->with('Category.Permissions|' . \XF::visitor()->permission_combination_id);
		}

		$productFinder
			->where('product_state', 'visible')
			->where('parent_product_id', 0)
			->where('is_listed', true)
			->with('User')
			->with('Permissions|' . \XF::visitor()->permission_combination_id)
			->order($productFinder->expression('RAND()'))
		;

		return $productFinder;
	}
	
	/**
	 * @param array|null $viewableCategoryIds
	 *
	 * @return \DBTech\eCommerce\Finder\Product
	 * @throws \InvalidArgumentException
	 */
	public function findTopProducts(array $viewableCategoryIds = null): \DBTech\eCommerce\Finder\Product
	{
		/** @var \DBTech\eCommerce\Finder\Product $productFinder */
		$productFinder = $this->finder('DBTech\eCommerce:Product');
		
		if (is_array($viewableCategoryIds))
		{
			$productFinder->where('product_category_id', $viewableCategoryIds);
		}
		else
		{
			$productFinder->with('Category.Permissions|' . \XF::visitor()->permission_combination_id);
		}
		
		$productFinder
			->where('parent_product_id', 0)
			->where('is_listed', true)
			->where('rating_count', '>', 0)
			->where('product_state', 'visible')
			->with(['User', 'Permissions|' . \XF::visitor()->permission_combination_id])
			->setDefaultOrder('rating_weighted', 'desc');
		
		return $productFinder;
	}
	
	/**
	 * @param \XF\Entity\User|int|null $userId
	 *
	 * @return \DBTech\eCommerce\Finder\Product
	 * @throws \InvalidArgumentException
	 */
	public function findProductsForWatchedList($userId = null): \DBTech\eCommerce\Finder\Product
	{
		if ($userId === null)
		{
			$userId = \XF::visitor()->user_id;
		}
		$userId = (int)$userId;
		
		/** @var \DBTech\eCommerce\Finder\Product $finder */
		$finder = $this->finder('DBTech\eCommerce:Product');
		
		$finder
			->with('full|category')
			->with('Watch|' . $userId, true)
			->with('LatestVersion')
			->where('product_state', 'visible')
			->setDefaultOrder('last_update', 'DESC');
		
		return $finder;
	}
	
	/**
	 * @param \DBTech\eCommerce\Entity\Category|null $withinCategory If provided, applies category-specific limits
	 *
	 * @return \DBTech\eCommerce\Finder\Product
	 */
	public function findProductsForApi(\DBTech\eCommerce\Entity\Category $withinCategory = null): \DBTech\eCommerce\Finder\Product
	{
		/** @var \DBTech\eCommerce\Finder\Product $finder */
		$finder = $this->finder('DBTech\eCommerce:Product')
			->with('api');
		
		if (\XF::isApiCheckingPermissions())
		{
			$categoryIds = $this->repository('DBTech\eCommerce:Category')->getViewableCategoryIds($withinCategory);
			$finder->where('product_category_id', $categoryIds);
			$finder->applyGlobalVisibilityChecks();
		}
		
		$finder->useDefaultOrder();
		
		return $finder;
	}
	
	/**
	 * @param \DBTech\eCommerce\Entity\Product $thisProduct
	 *
	 * @return \DBTech\eCommerce\Finder\Product
	 * @throws \InvalidArgumentException
	 */
	public function findOtherProductsByAuthor(\DBTech\eCommerce\Entity\Product $thisProduct): \DBTech\eCommerce\Finder\Product
	{
		/** @var \DBTech\eCommerce\Finder\Product $productFinder */
		$productFinder = $this->finder('DBTech\eCommerce:Product');
		
		$productFinder
			->with(['User', 'Permissions|' . \XF::visitor()->permission_combination_id, 'Category', 'Category.Permissions|' . \XF::visitor()->permission_combination_id])
			->where('product_state', 'visible')
			->where('user_id', $thisProduct->user_id)
			->where('product_id', '<>', $thisProduct->product_id)
			->setDefaultOrder('last_update', 'desc');
		
		return $productFinder;
	}
	
	/**
	 * @param int $userId
	 * @param array|null $viewableCategoryIds
	 * @param array $limits
	 *
	 * @return \DBTech\eCommerce\Finder\Product
	 * @throws \InvalidArgumentException
	 */
	public function findProductsByUser(int $userId, ?array $viewableCategoryIds = null, array $limits = []): \DBTech\eCommerce\Finder\Product
	{
		/** @var \DBTech\eCommerce\Finder\Product $productFinder */
		$productFinder = $this->finder('DBTech\eCommerce:Product');
		
		$productFinder->where('user_id', $userId)
			->with('Permissions|' . \XF::visitor()->permission_combination_id)
			->with('full|category')
			->setDefaultOrder('last_update', 'desc');
		
		if (is_array($viewableCategoryIds))
		{
			// if we have viewable category IDs, we likely have those permissions
			$productFinder->where('product_category_id', $viewableCategoryIds);
		}
		else
		{
			$productFinder->with('Category.Permissions|' . \XF::visitor()->permission_combination_id);
		}
		
		$limits = array_replace([
			'visibility' => true,
			'allowOwnPending' => $userId == \XF::visitor()->user_id
		], $limits);
		
		if ($limits['visibility'])
		{
			$productFinder->applyGlobalVisibilityChecks($limits['allowOwnPending']);
		}
		
		return $productFinder;
	}

	/**
	 * @param \XF\Entity\Forum $forum
	 *
	 * @throws \LogicException
	 *
	 * @return \DBTech\eCommerce\Finder\Product
	 */
	public function findProductForForum(\XF\Entity\Forum $forum): \DBTech\eCommerce\Finder\Product
	{
		if ($forum->forum_type_id != 'dbtech_ecommerce_ticket')
		{
			throw new \LogicException('This function can only be called on "eCommerce product support" forum types.');
		}

		/** @var \DBTech\eCommerce\Finder\Product $finder */
		$finder = $this->finder('DBTech\eCommerce:Product');
		
		$finder->where('product_id', $forum->type_config_['product_id'])
			->with('full|category')
			->with('Category.Permissions|' . \XF::visitor()->permission_combination_id);
		
		return $finder;
	}
	
	/**
	 * @throws \XF\Db\Exception
	 */
	public function resetTemporaryProductFeatures()
	{
		$db = $this->db();
		
		$db->beginTransaction();
		
		$db->query("
			UPDATE xf_dbtech_ecommerce_product
			SET is_featured = 0
			WHERE product_id IN(
			    SELECT product_id
			    FROM xf_dbtech_ecommerce_product_feature_temp
			)
		");
		
		$db->emptyTable('xf_dbtech_ecommerce_product_feature_temp');
		
		$db->commit();
	}

	/**
	 * @param \XF\Entity\Thread $thread
	 *
	 * @return \DBTech\eCommerce\Finder\Product
	 */
	public function findProductForThread(\XF\Entity\Thread $thread): \DBTech\eCommerce\Finder\Product
	{
		/** @var \DBTech\eCommerce\Finder\Product $finder */
		$finder = $this->finder('DBTech\eCommerce:Product');

		$finder->where('discussion_thread_id', $thread->thread_id)
			->with('full')
			->with('Category.Permissions|' . \XF::visitor()->permission_combination_id);

		return $finder;
	}
	
	/**
	 * @param \DBTech\eCommerce\Entity\Product $product
	 * @param string $action
	 * @param string $reason
	 * @param array $extra
	 * @param \XF\Entity\User|null $forceUser
	 *
	 * @return bool
	 */
	public function sendModeratorActionAlert(
		\DBTech\eCommerce\Entity\Product $product,
		string $action,
		string $reason = '',
		array $extra = [],
		?\XF\Entity\User $forceUser = null
	): bool {
		if (!$forceUser)
		{
			if (!$product->user_id || !$product->User)
			{
				return false;
			}
			
			$forceUser = $product->User;
		}
		
		$extra = array_merge([
			'title' => $product->title,
			'prefix_id' => $product->prefix_id,
			'link' => $this->app()->router('public')->buildLink('nopath:dbtech-ecommerce', $product),
			'reason' => $reason,
			'depends_on_addon_id' => 'DBTech/eCommerce',
		], $extra);
		
		/** @var \XF\Repository\UserAlert $alertRepo */
		$alertRepo = $this->repository('XF:UserAlert');
		$alertRepo->alert(
			$forceUser,
			0,
			'',
			'user',
			$forceUser->user_id,
			"dbt_ecom_product_{$action}",
			$extra
		);
		
		return true;
	}
	
	/**
	 * @return \DBTech\eCommerce\Finder\Product
	 */
	public function findProductsForList(): \DBTech\eCommerce\Finder\Product
	{
		/** @var \DBTech\eCommerce\Finder\Product $finder */
		$finder = $this->finder('DBTech\eCommerce:Product');

		return $finder->orderForList();
	}
	
	/**
	 * @return \DBTech\eCommerce\Finder\Product|\XF\Mvc\Entity\Finder
	 */
	public function findEntriesForPermissionList()
	{
		return $this->findProductsForList();
	}

	/**
	 * @param \XF\Mvc\Entity\ArrayCollection|null $entries
	 * @param int $rootId
	 * @return array
	 */
	public function getFlattenedProductTree(?ArrayCollection $entries = null, int $rootId = 0): array
	{
		return $this->createProductTree($entries, $rootId)->getFlattened();
	}

	/**
	 * @param \XF\Mvc\Entity\ArrayCollection|null $entries
	 * @param int $rootId
	 * @return \XF\Tree
	 */
	public function createProductTree(?ArrayCollection $entries = null, int $rootId = 0): \XF\Tree
	{
		if ($entries === null)
		{
			$entries = $this->findProductsForList()->fetch();
		}

		return new \XF\Tree($entries, 'parent_product_id', $rootId);
	}

	/**
	 * @param int $userId
	 *
	 * @return int
	 */
	public function getUserProductCount(int $userId)
	{
		return (int)$this->db()->fetchOne("
			SELECT COUNT(product_id)
			FROM xf_dbtech_ecommerce_product
			WHERE user_id = ?
				AND product_state = 'visible'
				AND parent_product_id = 0
		", $userId);
	}
	
	/**
	 * @param \XF\Tree|null $productTree
	 * @param array|null $flattenedCategoryTree
	 *
	 * @return array
	 */
	public function getProductsByCategory(?\XF\Tree $productTree = null, ?array $flattenedCategoryTree = null): array
	{
		$productTree = $productTree ?: $this->createProductTree();
		$flattenedCategoryTree = $flattenedCategoryTree
			?: $this->repository('DBTech\eCommerce:Category')
				->createCategoryTree()
				->getFlattened(0)
		;
		
		$productsByCategory = [];
		foreach ($flattenedCategoryTree as $treeEntry)
		{
			$productTree->traverse(function ($id, $record, $depth) use (&$productsByCategory, $treeEntry): bool
			{
				if ($treeEntry['record']->category_id != $record->product_category_id)
				{
					return false;
				}
				
				$productsByCategory[$record->product_category_id][] = [
					'record' => $record,
					'depth'  => $depth
				];
				
				return true;
			});
		}
		
		return [
			'categories' => $flattenedCategoryTree,
			'products' => $productsByCategory
		];
	}

	/**
	 * @param array $content
	 * @param string $metadataKey
	 * @param string $productGetterKey
	 */
	public function addProductEmbedsToContent(
		array $content,
		$metadataKey = 'embed_metadata',
		$productGetterKey = 'DBTechEcommerceProducts'
	) {
		if (!$content)
		{
			return;
		}
		
		$productIds = [];
		foreach ($content AS $item)
		{
			$metadata = $item->{$metadataKey};
			if (isset($metadata['productEmbeds']['product']))
			{
				$productIds = array_merge($productIds, $metadata['productEmbeds']['product']);
			}
		}
		
		$visitor = \XF::visitor();
		
		$attachments = [];
		$products = [];
		
		if ($productIds)
		{
			/** @var \DBTech\eCommerce\Entity\Product[]|ArrayCollection $products */
			$products = $this->finder('DBTech\eCommerce:Product')
				->with('Permissions|' . $visitor->permission_combination_id)
				->with('Category.Permissions|' . $visitor->permission_combination_id)
				->whereIds(array_unique($productIds))
				->fetch();
			
			$attachments = $this->finder('XF:Attachment')
				->with('Data', true)
				->where('content_type', 'dbtech_ecommerce_product')
				->where('content_id', array_unique($productIds))
				->order('attach_date', 'DESC')
				->fetch()
				->groupBy('content_id');
			
			foreach ($products as $product)
			{
				if (isset($attachments[$product->product_id]))
				{
					$product->hydrateRelation('Attachments', new ArrayCollection($attachments[$product->product_id]));
				}
			}
		}
		
		foreach ($content AS $item)
		{
			$metadata = $item->{$metadataKey};
			if (isset($metadata['productEmbeds']['product']))
			{
				$ecommerceProducts = [];
				foreach ($metadata['productEmbeds']['product'] AS $id)
				{
					if (!isset($products[$id]))
					{
						continue;
					}
					$ecommerceProducts[$id] = $products[$id];
				}
				
				$item->{"set$productGetterKey"}($ecommerceProducts);
			}
		}
	}

	/**
	 * @return \DBTech\eCommerce\ProductType\AbstractHandler[]
	 * @throws \Exception
	 */
	public function getProductTypeHandlers(): array
	{
		$handlers = [];

		foreach (\XF::app()->getContentTypeField('dbtech_ecommerce_product_type_handler_class') AS $contentType => $handlerClass)
		{
			if (class_exists($handlerClass))
			{
				$handlerClass = \XF::extendClass($handlerClass);
				$handlers[$contentType] = new $handlerClass($contentType);
			}
		}

		return $handlers;
	}

	/**
	 * @param string $type
	 *
	 * @return \DBTech\eCommerce\ProductType\AbstractHandler|null
	 * @throws \Exception
	 */
	public function getProductTypeHandler(string $type): ?\DBTech\eCommerce\ProductType\AbstractHandler
	{
		$handlerClass = \XF::app()->getContentTypeFieldValue($type, 'dbtech_ecommerce_product_type_handler_class');
		if (!$handlerClass)
		{
			return null;
		}

		if (!class_exists($handlerClass))
		{
			return null;
		}

		$handlerClass = \XF::extendClass($handlerClass);
		return new $handlerClass($type);
	}
}