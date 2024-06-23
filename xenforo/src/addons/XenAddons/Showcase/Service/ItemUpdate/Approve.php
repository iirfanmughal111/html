<?php

namespace XenAddons\Showcase\Service\ItemUpdate;

use XenAddons\Showcase\Entity\ItemUpdate;

class Approve extends \XF\Service\AbstractService
{
	/**
	 * @var ItemUpdate
	 */
	protected $update;

	protected $notifyRunTime = 3;

	public function __construct(\XF\App $app, ItemUpdate $update)
	{
		parent::__construct($app);
		$this->update = $update;
	}

	public function getUpdate()
	{
		return $this->update;
	}

	public function setNotifyRunTime($time)
	{
		$this->notifyRunTime = $time;
	}

	public function approve()
	{
		if ($this->update->update_state == 'moderated')
		{
			$this->update->update_state = 'visible';
			$this->update->save();

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
		/** @var \XenAddons\Showcase\Service\ItemUpdate\Notifier $notifier */
		$notifier = $this->service('XenAddons\Showcase:ItemUpdate\Notifier', $this->update, 'update');
		$notifier->notifyAndEnqueue($this->notifyRunTime);
	}
}