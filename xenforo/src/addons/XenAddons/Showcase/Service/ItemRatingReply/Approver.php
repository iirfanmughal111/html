<?php

namespace XenAddons\Showcase\Service\ItemRatingReply;

use XenAddons\Showcase\Entity\ItemRatingReply;

class Approver extends \XF\Service\AbstractService
{
	/**
	 * @var ItemRatingReply
	 */
	protected $reply;

	protected $notifyRunTime = 3;

	public function __construct(\XF\App $app, ItemRatingReply $reply)
	{
		parent::__construct($app);
		$this->reply = $reply;
	}

	public function getReply()
	{
		return $this->reply;
	}

	public function setNotifyRunTime($time)
	{
		$this->notifyRunTime = $time;
	}

	public function approve()
	{
		if ($this->reply->reply_state == 'moderated')
		{
			$this->reply->reply_state = 'visible';
			$this->reply->save();

			$this->onApprove();
			return true;
		}
		else
		{
			return false;
		}
	}

	protected function onApprove()
	{
		if ($this->reply->isLastReply())
		{
			/** @var \XenAddons\Showcase\Service\ItemRatingReply\Notifier $notifier */
			$notifier = $this->service('XenAddons\Showcase:ItemRatingReply\Notifier', $this->reply);
			$notifier->notify();
		}
	}
}