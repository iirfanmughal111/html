<?php

namespace Siropu\Chat\XF\Service\CronEntry;

class CalculateNextRun extends XFCP_CalculateNextRun
{
	public function calculateNextRunTimeCustom(array $runRules, $currentTime = null)
	{
          $currentTime = ($currentTime === null ? \XF::$time : $currentTime);

		$nextRun = new \DateTime('@' . $currentTime);
          $nextRun->setTimezone(new \DateTimeZone(\XF::options()->guestTimeZone));
		$nextRun->modify('+1 minute');

		if (empty($runRules['minutes']))
		{
			$runRules['minutes'] = [-1];
		}
		$this->modifyRunTimeMinutes($runRules['minutes'], $nextRun);

		if (empty($runRules['hours']))
		{
			$runRules['hours'] = [-1];
		}
		$this->modifyRunTimeHours($runRules['hours'], $nextRun);

		if (!empty($runRules['day_type']))
		{
			if ($runRules['day_type'] == 'dow')
			{
				if (empty($runRules['dow']))
				{
					$runRules['dow'] = [-1];
				}
				$this->modifyRunTimeDayOfWeek($runRules['dow'], $nextRun);
			}
			else
			{
				if (empty($runRules['dom']))
				{
					$runRules['dom'] = [-1];
				}
				$this->modifyRunTimeDayOfMonth($runRules['dom'], $nextRun);
			}
		}

		return $nextRun->format('Y-m-d H:i');
	}
}
