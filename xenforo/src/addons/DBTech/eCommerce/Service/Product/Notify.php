<?php

namespace DBTech\eCommerce\Service\Product;

use DBTech\eCommerce\Entity\Product;
use XF\Service\AbstractNotifier;

/**
 * Class Notify
 *
 * @package DBTech\eCommerce\Service\Product
 */
class Notify extends AbstractNotifier
{
	/** @var \DBTech\eCommerce\Entity\Product */
	protected $product;


	/**
	 * Notify constructor.
	 *
	 * @param \XF\App $app
	 * @param Product $product
	 */
	public function __construct(\XF\App $app, Product $product)
	{
		parent::__construct($app);

		$this->product = $product;
	}
	
	/**
	 * @param array $extraData
	 *
	 * @return null|\XF\Service\AbstractService
	 */
	public static function createForJob(array $extraData): ?\XF\Service\AbstractService
	{
		$product = \XF::app()->find('DBTech\eCommerce:Product', $extraData['productId'], ['Category']);
		if (!$product)
		{
			return null;
		}

		return \XF::service('DBTech\eCommerce:Product\Notify', $product);
	}
	
	/**
	 * @return array
	 */
	protected function getExtraJobData(): array
	{
		return [
			'productId' => $this->product->product_id,
		];
	}
	
	/**
	 * @return array
	 */
	protected function loadNotifiers(): array
	{
		return [
			'mention' => $this->app->notifier('DBTech\eCommerce:Product\Mention', $this->product),
			'categoryWatch' => $this->app->notifier('DBTech\eCommerce:Product\CategoryWatch', $this->product),
		];
	}
	
	/**
	 * @param array $users
	 */
	protected function loadExtraUserData(array $users)
	{
		$permCombinationIds = [];
		foreach ($users AS $user)
		{
			$id = $user->permission_combination_id;
			$permCombinationIds[$id] = $id;
		}
		
		$this->app->permissionCache()->cacheMultipleContentPermsForContent(
			$permCombinationIds,
			'dbtech_ecommerce_category',
			$this->product->product_category_id
		);
		
		$this->app->permissionCache()->cacheMultipleContentPermsForContent(
			$permCombinationIds,
			'dbtech_ecommerce_product',
			$this->product->product_id
		);
	}
	
	/**
	 * @param \XF\Entity\User $user
	 *
	 * @return mixed
	 * @throws \Exception
	 */
	protected function canUserViewContent(\XF\Entity\User $user)
	{
		/** @var \DBTech\eCommerce\XF\Entity\User $user */
		return \XF::asVisitor(
			$user,
			function () use ($user): bool
			{
				return (
				$this->product->Category
				&& $this->product->Category->canView()
				&& $user->hasDbtechEcommerceCategoryPermission($this->product->product_category_id, 'view')
			);
			}
		);
	}
	
	/**
	 * @param \DBTech\eCommerce\Entity\Category $category
	 */
	public function skipUsersWatchingCategory(\DBTech\eCommerce\Entity\Category $category)
	{
		$checkCategories = array_keys($category->breadcrumb_data);
		$checkCategories[] = $category->category_id;
		
		$db = $this->db();
		
		$watchers = $db->fetchAll('
			SELECT user_id, send_alert, send_email
			FROM xf_dbtech_ecommerce_category_watch
			WHERE category_id IN (' . $db->quote($checkCategories) . ')
				AND (category_id = ? OR include_children > 0)
				AND (send_alert = 1 OR send_email = 1)
		', $category->category_id);
		
		foreach ($watchers AS $watcher)
		{
			if ($watcher['send_alert'])
			{
				$this->setUserAsAlerted($watcher['user_id']);
			}
			if ($watcher['send_email'])
			{
				$this->setUserAsEmailed($watcher['user_id']);
			}
		}
	}
	
	/**
	 * @param Product $product
	 */
	public function skipUsersWatchingProduct(Product $product)
	{
		$db = $this->db();
		
		$watchers = $db->fetchAll('
			SELECT user_id, email_subscribe
			FROM xf_dbtech_ecommerce_product_watch
			WHERE product_id = ?
				AND email_subscribe = 1
		', $product->product_id);
		
		foreach ($watchers AS $watcher)
		{
			$this->setUserAsAlerted($watcher['user_id']);

			if ($watcher['email_subscribe'])
			{
				$this->setUserAsEmailed($watcher['user_id']);
			}
		}
	}
}