<?php

namespace XenAddons\Showcase\Service\ItemRatingReply;

use XenAddons\Showcase\Entity\ItemRatingReply;
use XF\Service\AbstractService;

class Notifier extends AbstractService
{
	protected $reply;

	protected $notifyItemOwner;
	protected $notifyItemRatingAuthor;
	protected $notifyMentioned = [];
	protected $notifyOtherRepliers;

	protected $usersAlerted = [];

	public function __construct(\XF\App $app, ItemRatingReply $reply)
	{
		parent::__construct($app);

		$this->reply = $reply;
	}

	public function getNotifyItemRatingAuthor()
	{
		if ($this->notifyItemRatingAuthor === null)
		{
			$this->notifyItemRatingAuthor = [$this->reply->ItemRating->user_id];
		}
		return $this->notifyItemRatingAuthor;
	}

	public function getNotifyItemOwner()
	{
		if ($this->notifyItemOwner === null)
		{
			$this->notifyItemOwner = [$this->reply->ItemRating->Item->user_id];
		}
		return $this->notifyItemOwner;
	}
	
	public function setNotifyMentioned(array $mentioned)
	{
		$this->notifyMentioned = array_unique($mentioned);
	}

	public function getNotifyMentioned()
	{
		return $this->notifyMentioned;
	}

	public function getNotifyOtherRepliers()
	{
		if ($this->notifyOtherRepliers === null && $this->reply->ItemRating)
		{
			/** @var \XenAddons\Showcase\Repository\ItemRating $repo */
			$repo = $this->repository('XenAddons\Showcase:ItemRating');
			$replies = $repo->findItemRatingReplies($this->reply->ItemRating, ['visibility' => false])
				->where('reply_state', 'visible')
				->fetch();

			$this->notifyOtherRepliers = $replies->pluckNamed('user_id');
		}
		return $this->notifyOtherRepliers;
	}

	public function notify()
	{
		$notifiableUsers = $this->getUsersForNotification();
		
		$itemRatingAuthors = $this->getNotifyItemRatingAuthor();
		foreach ($itemRatingAuthors AS $userId)
		{
			if (isset($notifiableUsers[$userId]))
			{
				$this->sendNotification($notifiableUsers[$userId], 'your_review');
			}
		}
		
		$itemUserIds = $this->getNotifyItemOwner();
		foreach ($itemUserIds AS $userId)
		{
			if (isset($notifiableUsers[$userId]))
			{
				$this->sendNotification($notifiableUsers[$userId], 'your_item');
			}
		}

		$mentionUsers = $this->getNotifyMentioned();
		foreach ($mentionUsers AS $userId)
		{
			if (isset($notifiableUsers[$userId]))
			{
				$this->sendNotification($notifiableUsers[$userId], 'mention');
			}
		}

		$otherRepliers = $this->getNotifyOtherRepliers();
		foreach ($otherRepliers AS $userId)
		{
			if (isset($notifiableUsers[$userId]))
			{
				$this->sendNotification($notifiableUsers[$userId], 'other_replier');
			}
		}
	}

	protected function getUsersForNotification()
	{
		$userIds = array_merge(
			$this->getNotifyItemOwner(),
			$this->getNotifyItemRatingAuthor(),
			$this->getNotifyMentioned(),
			$this->getNotifyOtherRepliers()
		);

		$reply = $this->reply;

		$users = $this->app->em()->findByIds('XF:User', $userIds, ['Profile', 'Option']);
		if (!$users->count())
		{
			return [];
		}

		$users = $users->toArray();
		foreach ($users AS $id => $user)
		{
			/** @var \XF\Entity\User $user */
			$canView = \XF::asVisitor($user, function() use ($reply) { return $reply->canView(); });
			if (!$canView)
			{
				unset($users[$id]);
			}
		}

		return $users;
	}

	protected function sendNotification(\XF\Entity\User $user, $action)
	{
		$reply = $this->reply;
		if ($user->user_id == $reply->user_id)
		{
			return false;
		}

		if (empty($this->usersAlerted[$user->user_id]))
		{
			/** @var \XF\Repository\UserAlert $alertRepo */
			$alertRepo = $this->app->repository('XF:UserAlert');
			if ($alertRepo->alert($user, $reply->user_id, $reply->username, 'sc_rating_reply', $reply->reply_id, $action))
			{
				$this->usersAlerted[$user->user_id] = true;
				return true;
			}
		}

		return false;
	}

}