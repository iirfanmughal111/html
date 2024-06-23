<?php

namespace XenAddons\Showcase\Service\ItemRatingReply;

use XenAddons\Showcase\Entity\ItemRatingReply;

class Preparer extends \XF\Service\AbstractService
{
	/**
	 * @var ItemRatingReply
	 */
	protected $reply;

	protected $logIp = true;

	protected $mentionedUsers = [];

	public function __construct(\XF\App $app, ItemRatingReply $reply)
	{
		parent::__construct($app);
		$this->setReply($reply);
	}

	protected function setReply(ItemRatingReply $reply)
	{
		$this->reply = $reply;
	}

	public function getReply()
	{
		return $this->reply;
	}

	public function logIp($logIp)
	{
		$this->logIp = $logIp;
	}

	public function getMentionedUsers($limitPermissions = true)
	{
		if ($limitPermissions && $this->reply)
		{
			/** @var \XF\Entity\User $user */
			$user = $this->reply->User ?: $this->repository('XF:User')->getGuestUser();
			return $user->getAllowedUserMentions($this->mentionedUsers);
		}
		else
		{
			return $this->mentionedUsers;
		}
	}

	public function getMentionedUserIds($limitPermissions = true)
	{
		return array_keys($this->getMentionedUsers($limitPermissions));
	}

	public function setMessage($message, $format = true)
	{
		$preparer = $this->getMessagePreparer($format);
		$preparer->setConstraint('maxLength', $this->app->options()->xaScReviewReplyMaxLength);
		$this->reply->message = $preparer->prepare($message);
		$this->reply->embed_metadata = $preparer->getEmbedMetadata();
		
		$this->mentionedUsers = $preparer->getMentionedUsers();

		return $preparer->pushEntityErrorIfInvalid($this->reply);
	}

	/**
	 * @param bool $format
	 *
	 * @return \XF\Service\Message\Preparer
	 */
	protected function getMessagePreparer($format = true)
	{
		/** @var \XF\Service\Message\Preparer $preparer */
		$preparer = $this->service('XF:Message\Preparer', 'sc_rating_reply', $this->reply);
		$preparer->enableFilter('structuredText');
		if (!$format)
		{
			$preparer->disableAllFilters();
		}
	
		return $preparer;
	}

	public function checkForSpam()
	{
		$reply = $this->reply;

		/** @var \XF\Entity\User $user */
		$user = $reply->User ?: $this->repository('XF:User')->getGuestUser($reply->username);
		$message = $reply->message;

		$checker = $this->app->spam()->contentChecker();
		$checker->check($user, $message, [
			'content_type' => 'sc_rating_reply'
		]);

		$decision = $checker->getFinalDecision();
		switch ($decision)
		{
			case 'moderated':
				$reply->reply_state = 'moderated';
				break;

			case 'denied':
				$checker->logSpamTrigger('sc_rating_reply', null);
				$reply->error(\XF::phrase('your_content_cannot_be_submitted_try_later'));
				break;
		}
	}

	public function afterInsert()
	{
		if ($this->logIp)
		{
			$ip = ($this->logIp === true ? $this->app->request()->getIp() : $this->logIp);
			$this->writeIpLog($ip);
		}

		$checker = $this->app->spam()->contentChecker();
		$checker->logSpamTrigger('sc_rating_reply', $this->reply->reply_id);
	}

	public function afterUpdate()
	{
		$checker = $this->app->spam()->contentChecker();
		$checker->logSpamTrigger('sc_rating_reply', $this->reply->reply_id);
	}

	protected function writeIpLog($ip)
	{
		$reply = $this->reply;
		if (!$reply->user_id)
		{
			return;
		}

		/** @var \XF\Repository\IP $ipRepo */
		$ipRepo = $this->repository('XF:Ip');
		$ipEnt = $ipRepo->logIp($reply->user_id, $ip, 'sc_rating_reply', $reply->reply_id);
		if ($ipEnt)
		{
			$reply->fastUpdate('ip_id', $ipEnt->ip_id);
		}
	}
}