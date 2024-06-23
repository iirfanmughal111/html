<?php

namespace XenAddons\Showcase\Notifier\ItemUpdate;

use XF\Notifier\AbstractNotifier;
use XenAddons\Showcase\Entity\ItemUpdate;

class Mention extends AbstractNotifier
{
	/**
	 * @var ItemUpdate
	 */
	protected $update;

	public function __construct(\XF\App $app, ItemUpdate $update)
	{
		parent::__construct($app);

		$this->update = $update;
	}

	public function canNotify(\XF\Entity\User $user)
	{
		return ($this->update->isVisible() && $user->user_id != $this->update->user_id);
	}

	public function sendAlert(\XF\Entity\User $user)
	{
		$update = $this->update;

		return $this->basicAlert(
			$user, $update->user_id, $update->username, 'sc_update', $update->item_update_id, 'mention'
		);
	}
}