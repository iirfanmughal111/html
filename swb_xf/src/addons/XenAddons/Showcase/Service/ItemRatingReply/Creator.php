<?php

namespace XenAddons\Showcase\Service\ItemRatingReply;

use XenAddons\Showcase\Entity\ItemRating;
use XenAddons\Showcase\Entity\ItemRatingReply;
use XF\Entity\User;

class Creator extends \XF\Service\AbstractService
{
	use \XF\Service\ValidateAndSavableTrait;

	/**
	 * @var ItemRating
	 */
	protected $itemRating;
	
	/**
	 * @var ItemRatingReply
	 */
	protected $reply;

	/**
	 * @var User
	 */
	protected $user;

	/**
	 * @var \XenAddons\Showcase\Service\ItemRatingReply\Preparer
	 */
	protected $preparer;

	public function __construct(\XF\App $app, ItemRating $itemRating)
	{
		parent::__construct($app);
		$this->setItemRating($itemRating);
		$this->setUser(\XF::visitor());
		$this->setDefaults();
	}

	protected function setItemRating(ItemRating $itemRating)
	{
		$this->itemRating = $itemRating;
		$this->reply = $itemRating->getNewReply();
		$this->preparer = $this->service('XenAddons\Showcase:ItemRatingReply\Preparer', $this->reply);
	}

	public function getItemRating()
	{
		return $this->itemRating;
	}

	public function getReply()
	{
		return $this->reply;
	}

	public function getItemRatingReplyPreparer()
	{
		return $this->preparer;
	}

	public function logIp($logIp)
	{
		$this->preparer->logIp($logIp);
	}

	protected function setUser(\XF\Entity\User $user)
	{
		$this->user = $user;
	}

	protected function setDefaults()
	{
		$this->reply->reply_state = $this->itemRating->getNewReplyState();
		$this->reply->user_id = $this->user->user_id;
		$this->reply->username = $this->user->username;
	}

	public function setContent($message, $format = true)
	{
		return $this->preparer->setMessage($message, $format);
	}

	public function checkForSpam()
	{
		if ($this->reply->reply_state == 'visible' && $this->user->isSpamCheckRequired())
		{
			$this->preparer->checkForSpam();
		}
	}

	protected function finalSetup()
	{
		$this->reply->reply_date = time();
	}

	protected function _validate()
	{
		$this->finalSetup();

		$this->reply->preSave();
		return $this->reply->getErrors();
	}

	protected function _save()
	{
		$reply = $this->reply;
		$reply->save();

		$this->preparer->afterInsert();

		return $reply;
	}

	public function sendNotifications()
	{
		if ($this->reply->isVisible())
		{
			/** @var \XenAddons\Showcase\Service\ItemRatingReply\Notifier $notifier */
			$notifier = $this->service('XenAddons\Showcase:ItemRatingReply\Notifier', $this->reply);
			$notifier->setNotifyMentioned($this->preparer->getMentionedUserIds());
			$notifier->notify();
		}
	}
}