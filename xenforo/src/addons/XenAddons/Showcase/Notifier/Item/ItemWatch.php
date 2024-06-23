<?php

namespace XenAddons\Showcase\Notifier\Item;

use XF\Notifier\AbstractNotifier;

class ItemWatch extends AbstractNotifier
{
	/**
	 * @var \XenAddons\Showcase\Entity\Item
	 */
	protected $item;

	public function __construct(\XF\App $app, \XenAddons\Showcase\Entity\Item $item)
	{
		parent::__construct($app);

		$this->item = $item;
	}

	public function canNotify(\XF\Entity\User $user)
	{
		$item = $this->item;

		if ($user->user_id == $item->user_id || $user->isIgnoring($item->user_id))
		{
			return false;
		}

		return true;
	}

	public function sendAlert(\XF\Entity\User $user)
	{
		$item = $this->item;

		return $this->basicAlert(
			$user, $item->user_id, $item->username, 'sc_item', $item->item_id, 'update'
		);
	}

	public function sendEmail(\XF\Entity\User $user)
	{
		if (!$user->email || $user->user_state != 'valid')
		{
			return false;
		}

		$item = $this->item;

		$params = [
			'item' => $item,
			'receiver' => $user
		];

		$this->app()->mailer()->newMail()
			->setToUser($user)
			->setTemplate('xa_sc_watched_item_update', $params)
			->queue();

		return true;
	}

	public function getDefaultNotifyData()
	{
		$item = $this->item;

		if (!$item)
		{
			return [];
		}

		$finder = $this->app()->finder('XenAddons\Showcase:ItemWatch');

		$finder->where('item_id', $item->item_id)
			->where('User.user_state', '=', 'valid')
			->where('User.is_banned', '=', 0);

		$activeLimit = $this->app()->options()->watchAlertActiveOnly;
		if (!empty($activeLimit['enabled']))
		{
			$finder->where('User.last_activity', '>=', \XF::$time - 86400 * $activeLimit['days']);
		}

		$notifyData = [];
		foreach ($finder->fetchColumns(['user_id', 'email_subscribe']) AS $watch)
		{
			$notifyData[$watch['user_id']] = [
				'alert' => true,
				'email' => (bool)$watch['email_subscribe']
			];
		}

		return $notifyData;
	}
}