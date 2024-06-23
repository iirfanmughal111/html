<?php

namespace DBTech\eCommerce\Spam\Cleaner;

use XF\Spam\Cleaner\AbstractHandler;

/**
 * Class ProductRating
 *
 * @package DBTech\eCommerce\Spam\Cleaner
 */
class ProductRating extends AbstractHandler
{
	/**
	 * @param array $options
	 *
	 * @return bool
	 */
	public function canCleanUp(array $options = []): bool
	{
		return !empty($options['delete_messages']);
	}
	
	/**
	 * @param array $log
	 * @param null $error
	 *
	 * @return bool
	 * @throws \InvalidArgumentException
	 * @throws \LogicException
	 * @throws \Exception
	 * @throws \XF\PrintableException
	 */
	public function cleanUp(array &$log, &$error = null): bool
	{
		$app = \XF::app();

		$ratings = $app->finder('DBTech\eCommerce:ProductRating')
			->where('user_id', $this->user->user_id)
			->fetch();

		if ($ratings->count())
		{
			$submitter = $app->container('spam.contentSubmitter');
			$submitter->submitSpam('dbtech_ecommerce_rating', $ratings->keys());

			$deleteType = $app->options()->spamMessageAction == 'delete' ? 'hard' : 'soft';

			$log['dbtech_ecommerce_rating'] = [
				'deleteType' => $deleteType,
				'ratingIds' => []
			];

			foreach ($ratings AS $ratingId => $rating)
			{
				$log['dbtech_ecommerce_rating']['ratingIds'][] = $ratingId;

				/** @var \DBTech\eCommerce\Entity\ProductRating $rating */
				$rating->setOption('log_moderator', false);
				if ($deleteType == 'soft')
				{
					$rating->softDelete();
				}
				else
				{
					$rating->delete();
				}
			}
		}

		return true;
	}
	
	/**
	 * @param array $log
	 * @param null $error
	 *
	 * @return bool
	 * @throws \InvalidArgumentException
	 * @throws \LogicException
	 * @throws \Exception
	 * @throws \XF\PrintableException
	 */
	public function restore(array $log, &$error = null): bool
	{
		if ($log['deleteType'] == 'soft')
		{
			$ratings = \XF::app()->finder('DBTech\eCommerce:ProductRating')
				->where('product_rating_id', $log['ratingIds'])
				->fetch();

			foreach ($ratings AS $rating)
			{
				/** @var \DBTech\eCommerce\Entity\ProductRating $rating */
				$rating->setOption('log_moderator', false);
				$rating->rating_state = 'visible';
				$rating->save();
			}
		}

		return true;
	}
}