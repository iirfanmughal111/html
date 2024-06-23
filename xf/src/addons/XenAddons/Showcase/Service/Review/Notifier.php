<?php

namespace XenAddons\Showcase\Service\Review;

use XF\Service\AbstractNotifier;
use XenAddons\Showcase\Entity\ItemRating;

class Notifier extends AbstractNotifier
{
	/**
	 * @var ItemRating
	 */
	protected $rating;

	public function __construct(\XF\App $app, ItemRating $rating)
	{
		parent::__construct($app);

		$this->rating = $rating;
	}

	public static function createForJob(array $extraData)
	{
		$rating = \XF::app()->find('XenAddons\Showcase:ItemRating', $extraData['ratingId']);
		if (!$rating)
		{
			return null;
		}

		return \XF::service('XenAddons\Showcase:Review\Notifier', $rating);
	}

	protected function getExtraJobData()
	{
		return [
			'ratingId' => $this->rating->rating_id
		];
	}

	protected function loadNotifiers()
	{
		$notifiers = [
			'mention' => $this->app->notifier('XenAddons\Showcase:Review\Mention', $this->rating)
		];

		$notifiers['ItemWatch'] = $this->app->notifier('XenAddons\Showcase:Review\ItemWatch', $this->rating);


		return $notifiers;
	}

	protected function loadExtraUserData(array $users)
	{
		return;
	}

	protected function canUserViewContent(\XF\Entity\User $user)
	{
		return \XF::asVisitor(
			$user,
			function() { return $this->rating->canView(); }
		);
	}
}