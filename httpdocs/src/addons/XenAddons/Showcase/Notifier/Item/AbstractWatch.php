<?php

namespace XenAddons\Showcase\Notifier\Item;

use XF\Notifier\AbstractNotifier;

abstract class AbstractWatch extends AbstractNotifier
{
	/**
	 * @var \XenAddons\Showcase\Entity\Item
	 */
	protected $item;

	protected $actionType;
	protected $isApplicable;

	abstract protected function getDefaultWatchNotifyData();
	abstract protected function getApplicableActionTypes();
	abstract protected function getWatchEmailTemplateName();

	public function __construct(\XF\App $app, \XenAddons\Showcase\Entity\Item $item, $actionType)
	{
		parent::__construct($app);

		$this->item = $item;
		$this->actionType = $actionType;
		$this->isApplicable = $this->isApplicable();
	}

	protected function isApplicable()
	{
		if (!in_array($this->actionType, $this->getApplicableActionTypes()))
		{
			return false;
		}

		if (!$this->item->isVisible())
		{
			return false;
		}

		return true;
	}

	public function canNotify(\XF\Entity\User $user)
	{
		if (!$this->isApplicable)
		{
			return false;
		}

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
			$user, $item->user_id, $item->username, 'sc_item', $item->item_id, 'insert'
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
			'category' => $item->Category,
			'receiver' => $user
		];

		$template = $this->getWatchEmailTemplateName();

		$this->app()->mailer()->newMail()
			->setToUser($user)
			->setTemplate($template, $params)
			->queue();

		return true;
	}

	public function getDefaultNotifyData()
	{
		if (!$this->isApplicable)
		{
			return [];
		}

		return $this->getDefaultWatchNotifyData();
	}
}