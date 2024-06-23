<?php

namespace DBTech\eCommerce\Job;

use XF\Job\AbstractJob;

/**
 * Class DownloadSchedule
 *
 * @package DBTech\eCommerce\Job
 */
class DownloadSchedule extends AbstractJob
{
	/** @var array */
	protected $defaultData = [
		'download_id' => null
	];
	
	/**
	 * @param $maxRunTime
	 *
	 * @return \XF\Job\JobResult
	 * @throws \LogicException
	 * @throws \InvalidArgumentException
	 * @throws \Exception
	 * @throws \XF\PrintableException
	 */
	public function run($maxRunTime): \XF\Job\JobResult
	{
		if (!$this->data['download_id'])
		{
			throw new \InvalidArgumentException('Cannot release downloads without a download_id.');
		}
		
		/** @var \DBTech\eCommerce\Entity\Download $download */
		$download = $this->app->em()->find('DBTech\eCommerce:Download', $this->data['download_id']);
		
		if (!$download || !$download->isScheduled())
		{
			return $this->complete();
		}
		
		/** @var \DBTech\eCommerce\Service\Download\Scheduler $downloadScheduleService */
		$downloadScheduleService = \XF::app()->service('DBTech\eCommerce:Download\Scheduler', $download);
		$downloadScheduleService->releaseDownload();
		$downloadScheduleService->sendNotifications();
		
		return $this->complete();
	}

	/**
	 * @return string
	 */
	public function getStatusMessage(): string
	{
		$actionPhrase = \XF::phrase('dbtech_ecommerce_releasing');
		$typePhrase = \XF::phrase('dbtech_ecommerce_downloads');
		return sprintf('%s... %s', $actionPhrase, $typePhrase);
	}

	/**
	 * @return bool
	 */
	public function canCancel(): bool
	{
		return true;
	}

	/**
	 * @return bool
	 */
	public function canTriggerByChoice(): bool
	{
		return false;
	}
}