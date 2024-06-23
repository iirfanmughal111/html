<?php

namespace DBTech\eCommerce\Api\Controller;

use XF\Mvc\Entity\Entity;
use XF\Mvc\ParameterBag;

/**
 * @api-group Reviews
 */
class ProductReview extends AbstractLoggableEndpoint
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
	 * @api-desc Gets information about the specified review
	 *
	 * @api-out ProductRating $review
	 *
	 * @param ParameterBag $params
	 *
	 * @return \XF\Api\Mvc\Reply\ApiResult
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionGet(ParameterBag $params): \XF\Api\Mvc\Reply\ApiResult
	{
		$review = $this->assertViewableReview($params->product_rating_id);

		$result = [
			'review' => $review->toApiResult(Entity::VERBOSITY_VERBOSE, ['with_product' => true])
		];
		return $this->apiResult($result);
	}

	/**
	 * @api-desc Deletes the specified product. Defaults to soft deletion.
	 *
	 * @api-in bool $hard_delete
	 * @api-in str $reason
	 * @api-in bool $author_alert
	 * @api-in str $author_alert_reason
	 *
	 * @api-out bool $success
	 *
	 * @param ParameterBag $params
	 *
	 * @return \XF\Api\Mvc\Reply\ApiResult|\XF\Mvc\Reply\AbstractReply|\XF\Mvc\Reply\Error|\XF\Mvc\Reply\View
	 * @throws \XF\Mvc\Reply\Exception
	 * @throws \XF\PrintableException
	 */
	public function actionDelete(ParameterBag $params)
	{
		$review = $this->assertViewableReview($params->product_rating_id);

		if (\XF::isApiCheckingPermissions() && !$review->canDelete('soft', $error))
		{
			return $this->noPermission($error);
		}

		$type = 'soft';
		$reason = $this->filter('reason', 'str');

		if ($this->filter('hard_delete', 'bool'))
		{
			$this->assertApiScope('dbtech_ecommerce_rating:delete_hard');

			if (\XF::isApiCheckingPermissions() && !$review->canDelete('hard', $error))
			{
				return $this->noPermission($error);
			}

			$type = 'hard';
		}

		/** @var \DBTech\eCommerce\Service\ProductRating\Delete $deleter */
		$deleter = $this->service('DBTech\eCommerce:ProductRating\Delete', $review);

		if ($this->filter('author_alert', 'bool'))
		{
			$deleter->setSendAlert(true, $this->filter('author_alert_reason', 'str'));
		}

		$deleter->delete($type, $reason);

		return $this->apiSuccess();
	}

	/**
	 * @api-desc Leaves an author response to the specified review.
	 *
	 * @api-in str $message The text of the reply.
	 *
	 * @api-out ProductRating $review The review information.
	 *
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\AbstractReply
	 * @throws \XF\Mvc\Reply\Exception
	 * @throws \XF\PrintableException
	 */
	public function actionPostAuthorReply(ParameterBag $params): \XF\Mvc\Reply\AbstractReply
	{
		$review = $this->assertViewableReview($params->product_rating_id);

		$this->assertRequiredApiInput('message');

		if (\XF::isApiCheckingPermissions() && !$review->canReply($error))
		{
			return $this->noPermission($error);
		}

		/** @var \DBTech\eCommerce\Service\ProductRating\AuthorReply $authorReplier */
		$authorReplier = $this->service('DBTech\eCommerce:ProductRating\AuthorReply', $review);

		$message = $this->filter('message', 'str');
		if (!$authorReplier->reply($message, $error))
		{
			return $this->error($error);
		}

		return $this->apiSuccess([
			'review' => $review
		]);
	}

	/**
	 * @api-desc Deletes an author reply from the specified review.
	 *
	 * @api-out bool $success
	 *
	 * @param ParameterBag $params
	 *
	 * @return \XF\Api\Mvc\Reply\ApiResult|\XF\Mvc\Reply\AbstractReply|\XF\Mvc\Reply\Error|\XF\Mvc\Reply\View
	 * @throws \XF\Mvc\Reply\Exception
	 * @throws \XF\PrintableException
	 */
	public function actionDeleteAuthorReply(ParameterBag $params)
	{
		$review = $this->assertViewableReview($params->product_rating_id);

		if (\XF::isApiCheckingPermissions() && !$review->canDeleteAuthorResponse($error))
		{
			return $this->noPermission($error);
		}

		/** @var \DBTech\eCommerce\Service\ProductRating\AuthorReplyDelete $deleter */
		$deleter = $this->service('DBTech\eCommerce:ProductRating\AuthorReplyDelete', $review);
		$deleter->delete();

		return $this->apiSuccess([
			'review' => $review
		]);
	}

	/**
	 * @param int $id
	 * @param string|array $with
	 *
	 * @return \DBTech\eCommerce\Entity\ProductRating
	 *
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function assertViewableReview($id, $with = 'api'): \DBTech\eCommerce\Entity\ProductRating
	{
		/** @var \DBTech\eCommerce\Entity\ProductRating $review */
		$review = $this->assertViewableApiRecord('DBTech\eCommerce:ProductRating', $id, $with);

		if (!$review->is_review)
		{
			throw $this->exception($this->notFound());
		}

		return $review;
	}
}