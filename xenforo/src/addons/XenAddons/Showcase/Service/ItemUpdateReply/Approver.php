<?php

namespace XenAddons\Showcase\Service\ItemUpdateReply;

use XenAddons\Showcase\Entity\ItemUpdateReply;

class Approver extends \XF\Service\AbstractService
{
	/**
	 * @var ItemUpdateReply
	 */
	protected $reply;

	protected $notifyRunTime = 3;

	public function __construct(\XF\App $app, ItemUpdateReply $reply)
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
			/** @var \XenAddons\Showcase\Service\ItemUpdateReply\Notifier $notifier */
			$notifier = $this->service('XenAddons\Showcase:ItemUpdateReply\Notifier', $this->reply);
			$notifier->notify();
		}
	}
}