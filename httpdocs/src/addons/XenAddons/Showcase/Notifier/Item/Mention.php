<?php

namespace XenAddons\Showcase\Notifier\Item;

use XF\Notifier\AbstractNotifier;
use XenAddons\Showcase\Entity\Item;

class Mention extends AbstractNotifier
{
	/**
	 * @var Item
	 */
	protected $item;

	public function __construct(\XF\App $app, Item $item)
	{
		parent::__construct($app);

		$this->item = $item;
	}

	public function canNotify(\XF\Entity\User $user)
	{
		return ($this->item->isVisible() && $user->user_id != $this->item->user_id);
	}

	public function sendAlert(\XF\Entity\User $user)
	{
		$item = $this->item;

		return $this->basicAlert(
			$user, $item->user_id, $item->username, 'sc_item', $item->item_id, 'mention'
		);
	}
}