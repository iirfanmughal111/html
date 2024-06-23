<?php

namespace XenAddons\Showcase\Notifier\Page;

use XF\Notifier\AbstractNotifier;

class ItemWatch extends AbstractNotifier
{
	/**
	 * @var \XenAddons\Showcase\Entity\ItemPage
	 */
	protected $page;

	public function __construct(\XF\App $app, \XenAddons\Showcase\Entity\ItemPage $page)
	{
		parent::__construct($app);

		$this->page = $page;
	}

	public function canNotify(\XF\Entity\User $user)
	{
		$page = $this->page;

		if ($user->user_id == $page->Item->user_id || $user->isIgnoring($page->Item->user_id))
		{
			return false;
		}

		return true;
	}

	public function sendAlert(\XF\Entity\User $user)
	{
		$page = $this->page;

		return $this->basicAlert(
			$user, $page->Item->user_id, $page->Item->username, 'sc_page', $page->page_id, 'insert'
		);
	}

	public function sendEmail(\XF\Entity\User $user)
	{
		if (!$user->email || $user->user_state != 'valid')
		{
			return false;
		}

		$page = $this->page;

		$params = [
			'page' => $page,
			'item' => $page->Item,
			'receiver' => $user
		];

		$this->app()->mailer()->newMail()
			->setToUser($user)
			->setTemplate('xa_sc_watched_item_page', $params)
			->queue();

		return true;
	}

	public function getDefaultNotifyData()
	{
		$page = $this->page;
		$item = $page->Item;

		if (!$item)
		{
			return [];
		}

		$finder = $this->app()->finder('XenAddons\Showcase:ItemWatch');

		$finder->where('item_id', $page->item_id)
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