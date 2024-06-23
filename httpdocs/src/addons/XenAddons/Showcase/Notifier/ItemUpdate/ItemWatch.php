<?php

namespace XenAddons\Showcase\Notifier\ItemUpdate;

use XF\Notifier\AbstractNotifier;

class ItemWatch extends AbstractNotifier
{
	/**
	 * @var \XenAddons\Showcase\Entity\ItemUpdate
	 */
	protected $update;

	public function __construct(\XF\App $app, \XenAddons\Showcase\Entity\ItemUpdate $update)
	{
		parent::__construct($app);

		$this->update = $update;
	}

	public function canNotify(\XF\Entity\User $user)
	{
		$update = $this->update;

		if ($user->user_id == $update->user_id || $user->isIgnoring($update->user_id))
		{
			return false;
		}

		return true;
	}

	public function sendAlert(\XF\Entity\User $user)
	{
		$update = $this->update;

		return $this->basicAlert(
			$user, $update->user_id, $update->username, 'sc_update', $update->item_update_id, 'insert'
		);
	}

	public function sendEmail(\XF\Entity\User $user)
	{
		if (!$user->email || $user->user_state != 'valid')
		{
			return false;
		}

		$update = $this->update;

		$params = [
			'update' => $update,
			'content' => $update->Content,
			'receiver' => $user
		];

		$this->app()->mailer()->newMail()
			->setToUser($user)
			->setTemplate('xa_sc_watched_item_update_insert', $params)
			->queue();

		return true;
	}

	public function getDefaultNotifyData()
	{
		$update = $this->update;
		$content = $update->Content;

		if (!$content)
		{
			return [];
		}

		$finder = $this->app()->finder('XenAddons\Showcase:ItemWatch');

		$finder->where('item_id', $update->item_id)
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