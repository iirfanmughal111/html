<?php

namespace XenAddons\Showcase\Notifier\Review;

use XF\Notifier\AbstractNotifier;
use XenAddons\Showcase\Entity\ItemRating;

class Mention extends AbstractNotifier
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

	public function canNotify(\XF\Entity\User $user)
	{
		return ($this->rating->isVisible() && $user->user_id != $this->rating->user_id);
	}

	public function sendAlert(\XF\Entity\User $user)
	{
		$review = $this->rating;
		
		// need to check to see if the review is ANONYMOUS and not include USER information for the alert!
		
		if ($review->is_anonymous)
		{
			return $this->basicAlert(
				$user, 0, 'Anonymous', 'sc_rating', $review->rating_id, 'mention'
			);
		}
		else
		{
			return $this->basicAlert(
				$user, $review->user_id, $review->username, 'sc_rating', $review->rating_id, 'mention'
			);
		}
	}
}