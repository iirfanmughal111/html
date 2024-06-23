<?php

namespace XenAddons\Showcase\Service\Item;

use XenAddons\Showcase\Entity\Item;

class Approve extends \XF\Service\AbstractService
{
	/**
	 * @var Item
	 */
	protected $item;

	protected $notifyRunTime = 3;

	public function __construct(\XF\App $app, Item $item)
	{
		parent::__construct($app);
		$this->item = $item;
	}

	public function getItem()
	{
		return $this->item;
	}

	public function setNotifyRunTime($time)
	{
		$this->notifyRunTime = $time;
	}

	public function approve()
	{
		if ($this->item->item_state == 'moderated')
		{
			$this->item->item_state = 'visible';
			$this->item->save();

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
		$visitor = \XF::visitor();
		$item = $this->item;

		if ($item)
		{
			/** @var \XenAddons\Showcase\Service\Item\Notify $notifier */
			$notifier = $this->service('XenAddons\Showcase:Item\Notify', $item, 'item');
			$notifier->notifyAndEnqueue($this->notifyRunTime);
			
			// Sends an alert to the item author letting them know that their Item has been approved.
			if ($item->user_id != $visitor->user_id)
			{
				/** @var \XenAddons\Showcase\Repository\Item $itemRepo */
				$itemRepo = $this->repository('XenAddons\Showcase:Item');
				$itemRepo->sendModeratorActionAlert($item, 'approve');
			}
			
			// check to see if there is an associated discussion thread and set the thread to visible and open
			if (
				$item->discussion_thread_id
				&& $item->Discussion
				&& $item->Discussion->discussion_type == 'sc_item'
			)
			{
				$thread = $item->Discussion;
				$thread->discussion_state = 'visible';
				$thread->discussion_open = true;
				$thread->saveIfChanged($saved, false, false);
			}
		}
	}
}