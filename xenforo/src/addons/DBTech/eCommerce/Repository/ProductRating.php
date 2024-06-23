<?php

namespace DBTech\eCommerce\Repository;

use XF\Mvc\Entity\Repository;

/**
 * Class ProductRating
 *
 * @package DBTech\eCommerce\Repository
 */
class ProductRating extends Repository
{
	/**
	 * @param \DBTech\eCommerce\Entity\Product $product
	 * @param array $limits
	 *
	 * @return \DBTech\eCommerce\Finder\ProductRating
	 * @throws \InvalidArgumentException
	 */
	public function findReviewsInProduct(\DBTech\eCommerce\Entity\Product $product, array $limits = []): \DBTech\eCommerce\Finder\ProductRating
	{
		/** @var \DBTech\eCommerce\Finder\ProductRating $finder */
		$finder = $this->finder('DBTech\eCommerce:ProductRating');
		$finder->inProduct($product, $limits)
			->where('is_review', 1)
			->setDefaultOrder('rating_date', 'desc');

		return $finder;
	}
	
	/**
	 * @param array|null $viewableCategoryIds
	 *
	 * @return \DBTech\eCommerce\Finder\ProductRating
	 * @throws \InvalidArgumentException
	 */
	public function findLatestReviews(array $viewableCategoryIds = null): \DBTech\eCommerce\Finder\ProductRating
	{
		/** @var \DBTech\eCommerce\Finder\ProductRating $finder */
		$finder = $this->finder('DBTech\eCommerce:ProductRating')
			->with('Product.Permissions|' . \XF::visitor()->permission_combination_id);

		if (is_array($viewableCategoryIds))
		{
			$finder->where('Product.product_category_id', $viewableCategoryIds);
		}
		else
		{
			$finder->with('Product.Category.Permissions|' . \XF::visitor()->permission_combination_id);
		}

		$finder->where([
				'Product.product_state' => 'visible',
				'rating_state' => 'visible',
				'is_review' => 1
			])
			->with('Product', true)
			->with(['Product.Category', 'User'])
			->setDefaultOrder('rating_date', 'desc');

		$cutOffDate = \XF::$time - ($this->options()->readMarkingDataLifetime * 86400);
		$finder->where('rating_date', '>', $cutOffDate);

		return $finder;
	}
	
	/**
	 * @param \DBTech\eCommerce\Entity\Category|null $withinCategory
	 *
	 * @return \DBTech\eCommerce\Finder\ProductRating
	 */
	public function findLatestReviewsForApi(\DBTech\eCommerce\Entity\Category $withinCategory = null): \DBTech\eCommerce\Finder\ProductRating
	{
		/** @var \DBTech\eCommerce\Finder\ProductRating $finder */
		$finder = $this->finder('DBTech\eCommerce:ProductRating');
		
		if (\XF::isApiCheckingPermissions())
		{
			$categoryIds = $this->repository('DBTech\eCommerce:Category')->getViewableCategoryIds($withinCategory);
			$finder->where('Product.product_category_id', $categoryIds);
		}
		
		$finder->where([
			'Product.product_state' => 'visible',
			'rating_state' => 'visible',
			'is_review' => 1
		])
			->with('Product', true)
			->with('api')
			->setDefaultOrder('rating_date', 'desc');
		
		$cutOffDate = \XF::$time - ($this->options()->readMarkingDataLifetime * 86400);
		$finder->where('rating_date', '>', $cutOffDate);
		
		return $finder;
	}
	
	/**
	 * @param int $productId
	 * @param int $userId
	 *
	 * @return \DBTech\eCommerce\Entity\ProductRating|\XF\Mvc\Entity\Entity|null
	 */
	public function getCountableRating(int $productId, int $userId)
	{
		/** @var \DBTech\eCommerce\Finder\ProductRating $finder */
		$finder = $this->finder('DBTech\eCommerce:ProductRating');
		$finder->where([
			'product_id' => $productId,
			'user_id' => $userId,
			'rating_state' => 'visible'
		])->order('rating_date', 'desc');

		return $finder->fetchOne();
	}

	/**
	 * Returns the ratings that are counted for the the given product user. This should normally return one.
	 * In general, only a bug would have it return more than one but the code is written so that this can be resolved.
	 *
	 * @param int $productId
	 * @param int $userId
	 *
	 * @return \XF\Mvc\Entity\AbstractCollection
	 */
	public function getCountedRatings(int $productId, int $userId): \XF\Mvc\Entity\AbstractCollection
	{
		/** @var \DBTech\eCommerce\Finder\ProductRating $finder */
		$finder = $this->finder('DBTech\eCommerce:ProductRating');
		$finder->where([
			'product_id' => $productId,
			'user_id' => $userId,
			'count_rating' => 1
		])->order('rating_date', 'desc');

		return $finder->fetch();
	}
	
	/**
	 * @param \DBTech\eCommerce\Entity\ProductRating $rating
	 * @param string $action
	 * @param string $reason
	 * @param array $extra
	 *
	 * @return bool
	 */
	public function sendModeratorActionAlert(
		\DBTech\eCommerce\Entity\ProductRating $rating,
		string $action,
		string $reason = '',
		array $extra = []
	): bool {
		$product = $rating->Product;

		if (!$product || !$product->user_id || !$product->User)
		{
			return false;
		}

		$extra = array_merge([
			'title' => $product->title,
			'prefix_id' => $product->prefix_id,
			'link' => $this->app()->router('public')->buildLink('nopath:dbtech-ecommerce/review', $rating),
			'productLink' => $this->app()->router('public')->buildLink('nopath:dbtech-ecommerce', $product),
			'reason' => $reason,
			'depends_on_addon_id' => 'DBTech/eCommerce',
		], $extra);

		/** @var \XF\Repository\UserAlert $alertRepo */
		$alertRepo = $this->repository('XF:UserAlert');
		$alertRepo->alert(
			$rating->User,
			0,
			'',
			'user',
			$rating->user_id,
			"dbt_ecom_rating_{$action}",
			$extra
		);

		return true;
	}
	
	/**
	 * @param \DBTech\eCommerce\Entity\ProductRating $rating
	 *
	 * @return bool
	 */
	public function sendReviewAlertToProductAuthor(\DBTech\eCommerce\Entity\ProductRating $rating): bool
	{
		if (!$rating->isVisible() || !$rating->is_review)
		{
			return false;
		}

		$product = $rating->Product;
		$productAuthor = $product->User;

		if (!$productAuthor)
		{
			return false;
		}

		if ($rating->is_anonymous)
		{
			$senderId = 0;
			$senderName = \XF::phrase('anonymous')->render('raw');
		}
		else
		{
			$senderId = $rating->user_id;
			$senderName = $rating->User ? $rating->User->username : \XF::phrase('unknown')->render('raw');
		}

		/** @var \XF\Repository\UserAlert $alertRepo */
		$alertRepo = $this->repository('XF:UserAlert');
		return $alertRepo->alert(
			$productAuthor,
			$senderId,
			$senderName,
			'dbtech_ecommerce_rating',
			$rating->product_rating_id,
			'review',
			['depends_on_addon_id' => 'DBTech/eCommerce']
		);
	}

	/**
	 * @param \DBTech\eCommerce\Entity\ProductRating $rating
	 *
	 * @return bool
	 */
	public function sendAuthorReplyAlert(\DBTech\eCommerce\Entity\ProductRating $rating): bool
	{
		if (!$rating->isVisible() || !$rating->is_review || !$rating->User)
		{
			return false;
		}

		$product = $rating->Product;

		$alertRepo = $this->repository('XF:UserAlert');
		return $alertRepo->alert(
			$rating->User,
			$product->user_id,
			$product->username,
			'dbtech_ecommerce_rating',
			$rating->product_rating_id,
			'reply',
			[],
			[
				'depends_on_addon_id' => 'DBTech/eCommerce',
				'autoRead' => false
			]
		);
	}
}