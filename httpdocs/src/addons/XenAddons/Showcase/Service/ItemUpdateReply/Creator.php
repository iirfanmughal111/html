<?php

namespace XenAddons\Showcase\Service\ItemUpdateReply;

use XenAddons\Showcase\Entity\ItemUpdate;
use XenAddons\Showcase\Entity\ItemUpdateReply;
use XF\Entity\User;

class Creator extends \XF\Service\AbstractService
{
	use \XF\Service\ValidateAndSavableTrait;

	/**
	 * @var ItemUpdate
	 */
	protected $itemUpdate;
	
	/**
	 * @var ItemUpdateReply
	 */
	protected $reply;

	/**
	 * @var User
	 */
	protected $user;

	/**
	 * @var \XenAddons\Showcase\Service\ItemUpdateReply\Preparer
	 */
	protected $preparer;

	public function __construct(\XF\App $app, ItemUpdate $itemUpdate)
	{
		parent::__construct($app);
		$this->setItemUpdate($itemUpdate);
		$this->setUser(\XF::visitor());
		$this->setDefaults();
	}

	protected function setItemUpdate(ItemUpdate $itemUpdate)
	{
		$this->itemUpdate = $itemUpdate;
		$this->reply = $itemUpdate->getNewReply();
		$this->preparer = $this->service('XenAddons\Showcase:ItemUpdateReply\Preparer', $this->reply);
	}

	public function getItemUpdate()
	{
		return $this->itemUpdate;
	}

	public function getReply()
	{
		return $this->reply;
	}

	public function getItemUpdateReplyPreparer()
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
		$this->reply->reply_state = $this->itemUpdate->getNewReplyState();
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
			/** @var \XenAddons\Showcase\Service\ItemUpdateReply\Notifier $notifier */
			$notifier = $this->service('XenAddons\Showcase:ItemUpdateReply\Notifier', $this->reply);
			$notifier->setNotifyMentioned($this->preparer->getMentionedUserIds());
			$notifier->notify();
		}
	}
}