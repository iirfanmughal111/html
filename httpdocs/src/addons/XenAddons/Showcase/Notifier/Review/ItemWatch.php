<?php

namespace XenAddons\Showcase\Notifier\Review;

use XF\Notifier\AbstractNotifier;

class ItemWatch extends AbstractNotifier
{
	/**
	 * @var \XenAddons\Showcase\Entity\ItemRating
	 */
	protected $review;

	public function __construct(\XF\App $app, \XenAddons\Showcase\Entity\ItemRating $review)
	{
		parent::__construct($app);

		$this->review = $review;
	}

	public function canNotify(\XF\Entity\User $user)
	{
		$review = $this->review;

		if ($user->user_id == $review->user_id || $user->isIgnoring($review->user_id))
		{
			return false;
		}

		return true;
	}

	public function sendAlert(\XF\Entity\User $user)
	{
		$review = $this->review;
		
		// need to check to see if the review is ANONYMOUS and not include USER information for the alert!
		
		if ($review->is_anonymous)
		{
			return $this->basicAlert(
				$user, 0, 'Anonymous', 'sc_rating', $review->rating_id, 'insert'
			);
		}
		else
		{
			return $this->basicAlert(
				$user, $review->user_id, $review->username, 'sc_rating', $review->rating_id, 'insert'
			);
		}
	}

	public function sendEmail(\XF\Entity\User $user)
	{
		if (!$user->email || $user->user_state != 'valid')
		{
			return false;
		}

		$review = $this->review;

		$params = [
			'review' => $review,
			'content' => $review->Content,
			'receiver' => $user
		];

		$this->app()->mailer()->newMail()
			->setToUser($user)
			->setTemplate('xa_sc_watched_item_review', $params)
			->queue();

		return true;
	}

	public function getDefaultNotifyData()
	{
		$review = $this->review;
		$content = $review->Content;

		if (!$content)
		{
			return [];
		}

		$finder = $this->app()->finder('XenAddons\Showcase:ItemWatch');

		$finder->where('item_id', $review->item_id)
			->where('User.user_state', '=', 'valid')
			->where('User.is_banned', '=', 0);

		$activeLimit = $this->app()->options()->watchAlertActiveOnly;
		if (!empty($activeLimit['enabled']))
		{
			$finder->where('User.last_activity', '>=', \XF::$time - 86400 * $activeLimit['days']);
		}

		$notifyData = [];
		foreach ($finder->fetchColumns(['user_id', 'email_subscribe']) AS $watch)
		{
			$notifyData[$watch['user_id']] = [
				'alert' => true,
				'email' => (bool)$watch['email_subscribe']
			];
		}

		return $notifyData;
	}
}