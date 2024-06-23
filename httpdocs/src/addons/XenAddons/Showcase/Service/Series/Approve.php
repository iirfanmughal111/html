<?php

namespace XenAddons\Showcase\Service\Series;

use XenAddons\Showcase\Entity\SeriesItem;

class Approve extends \XF\Service\AbstractService
{
	/**
	 * @var SeriesItem
	 */
	protected $series;

	protected $notifyRunTime = 3;

	public function __construct(\XF\App $app, SeriesItem $series)
	{
		parent::__construct($app);
		$this->series = $series;
	}

	public function getSeries()
	{
		return $this->series;
	}

	public function setNotifyRunTime($time)
	{
		$this->notifyRunTime = $time;
	}

	public function approve()
	{
		if ($this->series->series_state == 'moderated')
		{
			$this->series->series_state = 'visible';
			$this->series->save();

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
		$series = $this->series;

		if ($series)
		{
			/** @var \XenAddons\Showcase\Service\Series\Notify $notifier */
			//$notifier = $this->service('XenAddons\Showcase:Series\Notify', $series, 'series');
			//$notifier->notifyAndEnqueue($this->notifyRunTime);
			
			// Sends an alert to the series author letting them know that their Item has been approved. 
			if ($series->user_id != $visitor->user_id)
			{
				/** @var \XenAddons\Showcase\Repository\Series $seriesRepo */
				$seriesRepo = $this->repository('XenAddons\Showcase:Series');
				$seriesRepo->sendModeratorActionAlert($series, 'approve');
			}
		}
	}
}