<?php

namespace ThemeHouse\PostComments\Notifier\Post;

class ThreadWatch extends \XF\Notifier\Post\AbstractWatch
{
	/**
	 * @var \ThemeHouse\PostComments\XF\Entity\Post
	 */
	protected $post;

	protected function getApplicableActionTypes()
	{
		return ['reply'];
	}

	public function canNotify(\XF\Entity\User $user)
	{
		if (!$this->isApplicable)
		{
			return false;
		}

		$post = $this->post;

		if ($user->user_id == $post->user_id || $user->isIgnoring($post->user_id))
		{
			return false;
		}

		return true;
	}

	protected function getUserReadDates(array $userIds)
	{
		return [];
	}

	protected function getDefaultWatchNotifyData()
	{
		$post = $this->post;

		if ($post->isFirstPost())
		{
			return [];
		}

		$finder = $this->app()->finder('XF:ThreadWatch');

		$finder->where('thread_id', $post->thread_id)
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

	public function sendAlert(\XF\Entity\User $user)
	{
		$post = $this->post;

		return $this->basicAlert(
			$user,
			$post->user_id,
			$post->username,
			'post',
			$post->post_id,
			'thpostcomments_insert'
		);
	}

	protected function getWatchEmailTemplateName()
	{
		return 'thpostcomments_watched_thread_comment';
	}
}