<?php

namespace XenAddons\Showcase\Job;

use XF\Job\AbstractJob;

class Feeder extends AbstractJob
{
	protected $defaultData = [
		'steps' => 0
	];

	public function run($maxRunTime)
	{
		$start = microtime(true);

		$this->data['steps']++;

		/** @var \XenAddons\Showcase\Repository\Feed $feedRepo */
		$feedRepo = $this->app->repository('XenAddons\Showcase:Feed');

		/** @var \XenAddons\Showcase\Service\Feed\Feeder $feederService */
		$feederService = $this->app->service('XenAddons\Showcase:Feed\Feeder');

		/** @var \XenAddons\Showcase\Entity\Feed[]|\XF\Mvc\Entity\ArrayCollection $dueFeeds */
		$dueFeeds = $feedRepo->findDueFeeds()->fetch();
		
		if (!$dueFeeds->count())
		{
			return $this->complete();
		}

		foreach ($dueFeeds AS $feed)
		{
			if (microtime(true) - $start >= $maxRunTime)
			{
				break;
			}

			if (!$feed->Category)
			{
				continue;
			}

			if ($feederService->setupImport($feed) && $feederService->countPendingEntries())
			{
				$feederService->importEntries();
			}
		}

		return $this->resume();
	}

	public function getStatusMessage()
	{
		$actionPhrase = \XF::phrase('fetching');
		$typePhrase = \XF::phrase('registered_feeds');
		return sprintf('%s... %s %s', $actionPhrase, $typePhrase, str_repeat('. ', $this->data['steps']));
	}

	public function canCancel()
	{
		return false;
	}

	public function canTriggerByChoice()
	{
		return false;
	}
}

