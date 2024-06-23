<?php

namespace DBTech\eCommerce\Api\Controller;

use XF\Mvc\Entity\Entity;
use XF\Mvc\ParameterBag;

/**
 * @api-group Reviews
 */
class ProductReviews extends AbstractLoggableEndpoint
{
	/**
	 * @param $action
	 * @param ParameterBag $params
	 *
	 * @throws \XF\PrintableException
	 * @throws \XF\Mvc\Reply\Exception
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function preDispatchController($action, ParameterBag $params)
	{
		parent::preDispatchController($action, $params);

		$this->assertApiScopeByRequestMethod('dbtech_ecommerce_rating');
	}

	/**
	 * @api-desc Gets a list of all reviews.
	 *
	 * @api-in int $page
	 *
	 * @api-out ProductRating[] $reviews Reviews on this page
	 * @api-out pagination $pagination Pagination information
	 *
	 * @return \XF\Api\Mvc\Reply\ApiResult
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionGet(): \XF\Api\Mvc\Reply\ApiResult
	{
		$ratingRepo = $this->repository('DBTech\eCommerce:ProductRating');
		$finder = $ratingRepo->findLatestReviewsForApi();

		$total = $finder->total();
		$page = $this->filterPage();
		$perPage = $this->options()->dbtechEcommerceReviewsPerPage;

		$this->assertValidApiPage($page, $perPage, $total);

		$reviews = $finder->limitByPage($page, $perPage)->fetch();

		if (\XF::isApiCheckingPermissions())
		{
			$reviews = $reviews->filterViewable();
		}

		return $this->apiResult([
			'reviews' => $reviews->toApiResults(Entity::VERBOSITY_NORMAL, ['with_product' => true]),
			'pagination' => $this->getPaginationData($reviews, $page, $perPage, $total)
		]);
	}

	/**
	 * @api-desc Reviews a specified product
	 *
	 * @api-in <req> int $product_id Which product version you want to review.
	 * @api-in <req> int $rating The star rating you wish to give this product.
	 *
	 * @api-see self::setupProductRate()
	 *
	 * @api-out ProductRating $review The review information.
	 *
	 * @param ParameterBag $params
	 *
	 * @return \XF\Api\Mvc\Reply\ApiResult|\XF\Mvc\Reply\AbstractReply|\XF\Mvc\Reply\Error|\XF\Mvc\Reply\View
	 * @throws \XF\Mvc\Reply\Exception
	 * @throws \Exception
	 */
	public function actionPost(ParameterBag $params)
	{
		$this->assertRequiredApiInput(['product_id', 'rating']);

		$productId = $this->filter('product_id', 'uint');

		/** @var \DBTech\eCommerce\Entity\Product $product */
		$product = $this->assertViewableApiRecord('DBTech\eCommerce:Product', $productId);

		if (\XF::isApiCheckingPermissions())
		{
			if (!$product->canRate(true, $error))
			{
				return $this->noPermission($error);
			}

			/** @var \DBTech\eCommerce\Entity\ProductRating|null $existingRating */
			$existingRating = $product->Ratings[\XF::visitor()->user_id];
			if ($existingRating && !$existingRating->canUpdate($error))
			{
				return $this->noPermission($error);
			}
		}

		$rater = $this->setupProductRate($product);

		if (\XF::isApiCheckingPermissions())
		{
			$rater->checkForSpam();
		}

		if (!$rater->validate($errors))
		{
			return $this->error($errors);
		}

		/** @var \DBTech\eCommerce\Entity\ProductRating $review */
		$review = $rater->save();

		return $this->apiSuccess([
			'review' => $review->toApiResult()
		]);
	}

	/**
	 * @api-in int $rating The star rating you wish to give this product.
	 * @api-in str $message The text of the review.
	 * @api-in bool $is_anonymous Whether the review should be left anonymously. Depends on permissions.
	 *
	 * @param \DBTech\eCommerce\Entity\Product $product
	 *
	 * @return \DBTech\eCommerce\Service\Product\Rate
	 */
	protected function setupProductRate(\DBTech\eCommerce\Entity\Product $product): \DBTech\eCommerce\Service\Product\Rate
	{
		/** @var \DBTech\eCommerce\Service\Product\Rate $rater */
		$rater = $this->service('DBTech\eCommerce:Product\Rate', $product);

		$input = $this->filter([
			'rating' => 'uint',
			'message' => 'str',
			'is_anonymous' => 'bool'
		]);

		$rater->setRating($input['rating'], $input['message']);

		if ($this->options()->dbtechEcommerceAllowAnonReview && $input['is_anonymous'])
		{
			$rater->setIsAnonymous();
		}

		return $rater;
	}
}