<?php

namespace XenAddons\Showcase\Notifier\SeriesPart;

use XF\Notifier\AbstractNotifier;

class SeriesWatch extends AbstractNotifier
{
	/**
	 * @var \XenAddons\Showcase\Entity\SeriesPart
	 */
	protected $seriesPart;

	public function __construct(\XF\App $app, \XenAddons\Showcase\Entity\SeriesPart $seriesPart)
	{
		parent::__construct($app);

		$this->seriesPart = $seriesPart;
	}

	public function canNotify(\XF\Entity\User $user)
	{
		$seriesPart = $this->seriesPart;

		if ($user->user_id == $seriesPart->user_id || $user->isIgnoring($seriesPart->user_id))
		{
			return false;
		}

		return true;
	}

	public function sendAlert(\XF\Entity\User $user)
	{
		$seriesPart = $this->seriesPart;
		$series = $this->seriesPart->Series;
		$item = $this->seriesPart->Item;

		return $this->basicAlert(
			$user, $seriesPart->user_id, $seriesPart->User->username, 'sc_series_part', $seriesPart->series_part_id, 'insert'
		);
	}

	public function sendEmail(\XF\Entity\User $user)
	{
		if (!$user->email || $user->user_state != 'valid')
		{
			return false;
		}

		$seriesPart = $this->seriesPart;

		$params = [
			'seriePart' => $seriesPart,
			'series' => $seriesPart->Series,
			'item' => $seriesPart->Item,
			'receiver' => $user
		];

		$this->app()->mailer()->newMail()
			->setToUser($user)
			->setTemplate('xa_sc_watched_series_item', $params)
			->queue();

		return true;
	}

	public function getDefaultNotifyData()
	{
		$seriesPart = $this->seriesPart;
		$series = $this->seriesPart->Series;
		$item = $seriesPart->Item;

		if (!$series)
		{
			return [];
		}

		$finder = $this->app()->finder('XenAddons\Showcase:SeriesWatch');

		$finder->where('series_id', $seriesPart->series_id)
			->where('User.user_state', '=', 'valid')
			->where('User.is_banned', '=', 0)
			->where('notify_on', 'series_part')
			->whereOr(
				['send_alert', '>', 0],
				['send_email', '>', 0]
			);
		
		$activeLimit = $this->app()->options()->watchAlertActiveOnly;
		if (!empty($activeLimit['enabled']))
		{
			$finder->where('User.last_activity', '>=', \XF::$time - 86400 * $activeLimit['days']);
		}
		
		$notifyData = [];
		foreach ($finder->fetchColumns(['user_id', 'send_alert', 'send_email']) AS $watch)
		{
			$notifyData[$watch['user_id']] = [
				'alert' => (bool)$watch['send_alert'],
				'email' => (bool)$watch['send_email']
			];
		}
		
		return $notifyData;
	}
}