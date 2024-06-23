<?php

namespace XenAddons\Showcase\Spam\Cleaner;

use XF\Spam\Cleaner\AbstractHandler;

class Rating extends AbstractHandler
{
	public function canCleanUp(array $options = [])
	{
		return !empty($options['delete_messages']);
	}

	public function cleanUp(array &$log, &$error = null)
	{
		$app = \XF::app();

		$ratingsFinder = $app->finder('XenAddons\Showcase:ItemRating');
		$ratings = $ratingsFinder
			->where('user_id', $this->user->user_id)
			->fetch();

		if ($ratings->count())
		{
			$ratingIds = $ratings->pluckNamed('rating_id');
			$submitter = $app->container('spam.contentSubmitter');
			$submitter->submitSpam('sc_rating', $ratingIds);

			$deleteType = $app->options()->spamMessageAction == 'delete' ? 'hard' : 'soft';

			$log['sc_rating'] = [
				'deleteType' => $deleteType,
				'ratingIds' => []
			];

			foreach ($ratings AS $ratingId => $rating)
			{
				$log['sc_rating']['ratingIds'][] = $ratingId;

				/** @var \XenAddons\Showcase\Entity\ItemRating $rating */
				$rating->setOption('log_moderator', false);
				if ($deleteType == 'soft')
				{
					$rating->rating_state = 'deleted';
					$rating->save();
				}
				else
				{
					$rating->delete();
				}
			}
		}

		return true;
	}

	public function restore(array $log, &$error = null)
	{
		$ratingsFinder = \XF::app()->finder('XenAddons\Showcase:ItemRating');

		if ($log['deleteType'] == 'soft')
		{
			$ratings = $ratingsFinder->where('rating_id', $log['ratingIds'])->fetch();
			foreach ($ratings AS $rating)
			{
				/** @var \XenAddons\Showcase\Entity\ItemRating $rating */
				$rating->setOption('log_moderator', false);
				$rating->rating_state = 'visible';
				$rating->save();
			}
		}

		return true;
	}
}