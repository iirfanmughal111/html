<?php

namespace XenAddons\Showcase\Notifier\Comment;

use XF\Notifier\AbstractNotifier;

class ItemWatch extends AbstractNotifier
{
	/**
	 * @var \XenAddons\Showcase\Entity\Comment
	 */
	protected $comment;
	
	protected $isApplicable;
	
	protected $userReadDates = [];
	protected $previousComments = null;

	public function __construct(\XF\App $app, \XenAddons\Showcase\Entity\Comment $comment)
	{
		parent::__construct($app);

		$this->comment = $comment;
		$this->isApplicable = $this->isApplicable();
	}
	
	protected function isApplicable()
	{
		if (!$this->comment->isVisible())
		{
			return false;
		}
	
		return true;
	}

	public function canNotify(\XF\Entity\User $user)
	{
		if (!$this->isApplicable)
		{
			return false;
		}
		
		if (!isset($this->userReadDates[$user->user_id]))
		{
			// this should have a record for every user, so generally shouldn't happen
			return false;
		}
		
		$userReadDate = $this->userReadDates[$user->user_id];
		$comment = $this->comment;

		if ($user->user_id == $comment->user_id || $user->isIgnoring($comment->user_id))
		{
			return false;
		}
		
		if ($userReadDate > $comment->Content->last_comment_date)
		{
			return false;
		}
		
		$previousVisibleComment = null;
		foreach ($this->getPreviousComments() AS $previousComment)
		{
			if (!$user->isIgnoring($previousComment->user_id))
			{
				$previousVisibleComment = $previousComment;
				break;
			}
		}
		
		$autoReadDate = \XF::$time - $this->app()->options()->readMarkingDataLifetime * 86400;
		if (!$previousVisibleComment || $previousVisibleComment->comment_date < $autoReadDate)
		{
			// always alert
		}
		else if ($previousVisibleComment->comment_date > $userReadDate)
		{
			return false;
		}

		return true;
	}

	public function sendAlert(\XF\Entity\User $user)
	{
		$comment = $this->comment;

		return $this->basicAlert(
			$user, 
			$comment->user_id, 
			$comment->username, 
			'sc_comment', 
			$comment->comment_id, 
			'insert'
		);
	}

	public function sendEmail(\XF\Entity\User $user)
	{
		if (!$user->email || $user->user_state != 'valid')
		{
			return false;
		}

		$comment = $this->comment;

		$params = [
			'comment' => $comment,
			'content' => $comment->Content,
			'receiver' => $user
		];

		$this->app()->mailer()->newMail()
			->setToUser($user)
			->setTemplate('xa_sc_watched_item_comment', $params)
			->queue();

		return true;
	}

	public function getDefaultNotifyData()
	{
		if (!$this->isApplicable)
		{
			return [];
		}
		
		$comment = $this->comment;
		$content = $comment->Content;

		if (!$content)
		{
			return [];
		}

		$finder = $this->app()->finder('XenAddons\Showcase:ItemWatch');

		$finder->where('item_id', $comment->item_id)
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
	
	public function getUserData(array $userIds)
	{
		$users = parent::getUserData($userIds);
		$this->userReadDates = $this->getUserReadDates($userIds);
	
		return $users;
	}
	
	protected function getUserReadDates(array $userIds)
	{
		if (!$userIds)
		{
			return [];
		}
	
		$autoReadDate = \XF::$time - $this->app()->options()->readMarkingDataLifetime * 86400;
		$comment = $this->comment;
	
		$db = $this->app()->db();
		$readDates = $db->fetchPairs("
			SELECT user.user_id,
				GREATEST(
					COALESCE(item_read.item_read_date, 0),
					?
				)
			FROM xf_user AS user
			LEFT JOIN xf_xa_sc_item_read AS item_read ON
				(item_read.user_id = user.user_id AND item_read.item_id = ?)
			WHERE user.user_id IN (" . $db->quote($userIds) . ")
		", [$autoReadDate, $comment->item_id]);
	
		foreach ($userIds AS $userId)
		{
			if (!isset($readDates[$userId]))
			{
				$readDates[$userId] = $autoReadDate;
			}
		}
	
		return $readDates;
	}
	
	protected function getPreviousComments()
	{
		if ($this->previousComments === null)
		{
			$autoReadDate = \XF::$time - $this->app()->options()->readMarkingDataLifetime * 86400;
	
			$finder = $this->app()->finder('XenAddons\Showcase:Comment')
				->where('item_id', $this->comment->item_id)
				->where('comment_state', 'visible')
				->where('comment_date', '<', $this->comment->comment_date)
				->where('comment_date', '>=', $autoReadDate)
				->order('comment_date', 'desc');
	
			$this->previousComments = $finder->fetch(15);
		}
	
		return $this->previousComments;
	}
}