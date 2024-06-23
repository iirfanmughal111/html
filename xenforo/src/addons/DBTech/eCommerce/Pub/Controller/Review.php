<?php

namespace DBTech\eCommerce\Pub\Controller;

use XF\Mvc\ParameterBag;

/**
 * Class Review
 *
 * @package DBTech\eCommerce\Pub\Controller
 */
class Review extends AbstractController
{
	/**
	 * @param $action
	 * @param ParameterBag $params
	 *
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function preDispatchController($action, ParameterBag $params)
	{
		/** @var \DBTech\eCommerce\XF\Entity\User $visitor */
		$visitor = \XF::visitor();

		if (!$visitor->canViewDbtechEcommerceProducts($error))
		{
			throw $this->exception($this->noPermission($error));
		}
	}
	
	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\Redirect
	 * @throws \InvalidArgumentException
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionIndex(ParameterBag $params): \XF\Mvc\Reply\Redirect
	{
		$review = $this->assertViewableReview($params->product_rating_id);

		return $this->redirectToReview($review);
	}

	/**
	 * @param \XF\Mvc\ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\AbstractReply
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionVote(ParameterBag $params): \XF\Mvc\Reply\AbstractReply
	{
		$review = $this->assertViewableReview($params->product_rating_id);

		/** @var \XF\ControllerPlugin\ContentVote $votePlugin */
		$votePlugin = $this->plugin('XF:ContentVote');

		return $votePlugin->actionVote(
			$review,
			$this->buildLink('dbtech-ecommerce/review', $review),
			$this->buildLink('dbtech-ecommerce/review/vote', $review)
		);
	}
	
	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\AbstractReply
	 * @throws \InvalidArgumentException
	 * @throws \LogicException
	 * @throws \Exception
	 * @throws \XF\Mvc\Reply\Exception
	 * @throws \XF\PrintableException
	 */
	public function actionDelete(ParameterBag $params): \XF\Mvc\Reply\AbstractReply
	{
		$review = $this->assertViewableReview($params->product_rating_id);
		if (!$review->canDelete('soft', $error))
		{
			return $this->noPermission($error);
		}

		if ($this->isPost())
		{
			$type = $this->filter('hard_delete', 'bool') ? 'hard' : 'soft';
			$reason = $this->filter('reason', 'str');

			if (!$review->canDelete($type, $error))
			{
				return $this->noPermission($error);
			}

			/** @var \DBTech\eCommerce\Service\ProductRating\Delete $deleter */
			$deleter = $this->service('DBTech\eCommerce:ProductRating\Delete', $review);

			if ($this->filter('author_alert', 'bool') && $review->canSendModeratorActionAlert())
			{
				$deleter->setSendAlert(true, $this->filter('author_alert_reason', 'str'));
			}

			$deleter->delete($type, $reason);

			return $this->redirect(
				$this->getDynamicRedirect($this->buildLink('dbtech-ecommerce', $review->Product), false)
			);
		}
		
		$viewParams = [
			'review' => $review,
			'product' => $review->Product
		];
		return $this->view('DBTech\eCommerce:ProductReview\Delete', 'dbtech_ecommerce_product_review_delete', $viewParams);
	}

	/**
	 * @param \XF\Mvc\ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\AbstractReply|\XF\Mvc\Reply\Error|\XF\Mvc\Reply\Redirect|\XF\Mvc\Reply\View
	 * @throws \XF\Mvc\Reply\Exception
	 * @throws \XF\PrintableException
	 */
	public function actionUndelete(ParameterBag $params)
	{
		$this->assertValidCsrfToken($this->filter('t', 'str'));

		$review = $this->assertViewableReview($params->product_rating_id);
		if (!$review->canUndelete($error))
		{
			return $this->noPermission($error);
		}

		if ($review->rating_state == 'deleted')
		{
			$review->rating_state = 'visible';
			$review->save();
		}

		return $this->redirect($this->buildLink('dbtech-ecommerce/review', $review));
	}
	
	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\AbstractReply
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionReport(ParameterBag $params): \XF\Mvc\Reply\AbstractReply
	{
		$review = $this->assertViewableReview($params->product_rating_id);
		if (!$review->canReport($error))
		{
			return $this->noPermission($error);
		}

		/** @var \XF\ControllerPlugin\Report $reportPlugin */
		$reportPlugin = $this->plugin('XF:Report');
		return $reportPlugin->actionReport(
			'dbtech_ecommerce_rating',
			$review,
			$this->buildLink('dbtech-ecommerce/review/report', $review),
			$this->buildLink('dbtech-ecommerce/review', $review)
		);
	}
	
	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\AbstractReply
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionWarn(ParameterBag $params): \XF\Mvc\Reply\AbstractReply
	{
		$review = $this->assertViewableReview($params->product_rating_id);

		if (!$review->canWarn($error))
		{
			return $this->noPermission($error);
		}

		$product = $review->Product;
		$breadcrumbs = $product->Category->getBreadcrumbs();

		/** @var \XF\ControllerPlugin\Warn $warnPlugin */
		$warnPlugin = $this->plugin('XF:Warn');
		return $warnPlugin->actionWarn(
			'dbtech_ecommerce_rating',
			$review,
			$this->buildLink('dbtech-ecommerce/review/warn', $review),
			$breadcrumbs
		);
	}
	
	/**
	 * @param \DBTech\eCommerce\Entity\ProductRating $review
	 *
	 * @return \XF\Mvc\Reply\Redirect
	 * @throws \InvalidArgumentException
	 */
	protected function redirectToReview(\DBTech\eCommerce\Entity\ProductRating $review): \XF\Mvc\Reply\Redirect
	{
		$product = $review->Product;

		$newerFinder = $this->getRatingRepo()->findReviewsInProduct($product);
		$newerFinder->where('rating_date', '>', $review->rating_date);
		$totalNewer = $newerFinder->total();

		$perPage = $this->options()->dbtechEcommerceReviewsPerPage;
		$page = ceil(($totalNewer + 1) / $perPage);

		if ($page > 1)
		{
			$params = ['page' => $page];
		}
		else
		{
			$params = [];
		}

		return $this->redirect(
			$this->buildLink('dbtech-ecommerce/reviews', $product, $params)
			. '#product-review-' . $review->product_rating_id
		);
	}

	/**
	 * @param int|null $productRatingId
	 * @param array $extraWith
	 *
	 * @return \DBTech\eCommerce\Entity\ProductRating
	 *
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function assertViewableReview(?int $productRatingId, array $extraWith = []): \DBTech\eCommerce\Entity\ProductRating
	{
		$visitor = \XF::visitor();

		$extraWith[] = 'Product';
		$extraWith[] = 'Product.User';
		$extraWith[] = 'Product.Category';
		$extraWith[] = 'Product.Category.Permissions|' . $visitor->permission_combination_id;

		/** @var \DBTech\eCommerce\Entity\ProductRating $review */
		$review = $this->em()->find('DBTech\eCommerce:ProductRating', $productRatingId, $extraWith);
		if (!$review)
		{
			throw $this->exception($this->notFound(\XF::phrase('dbtech_ecommerce_requested_review_not_found')));
		}

		$error = null;
		if (!$review->is_review || !$review->canView($error))
		{
			throw $this->exception($this->noPermission($error));
		}

		return $review;
	}

	/**
	 * @return \DBTech\eCommerce\Repository\ProductRating|\XF\Mvc\Entity\Repository
	 */
	protected function getRatingRepo()
	{
		return $this->repository('DBTech\eCommerce:ProductRating');
	}
	
	/**
	 * @param array $activities
	 *
	 * @return bool|\XF\Phrase
	 */
	public static function getActivityDetails(array $activities)
	{
		return \XF::phrase('dbtech_ecommerce_viewing_products');
	}
}