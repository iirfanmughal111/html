<?php

namespace DBTech\eCommerce\Job;

use XF\Job\AbstractJob;

/**
 * Class IncomeStats
 *
 * @package DBTech\eCommerce\Job
 */
class IncomeStats extends AbstractJob
{
	/** @var array */
	protected $defaultData = [
		'position' => 0,
		'batch' => 28,
		'delete' => false
	];
	
	/**
	 * @param $maxRunTime
	 *
	 * @return \XF\Job\JobResult
	 */
	public function run($maxRunTime): \XF\Job\JobResult
	{
		$db = $this->app->db();

		if ($this->data['position'] == 0)
		{
			// delete old stats cache if required
			if ($this->data['delete'])
			{
				$db->emptyTable('xf_dbtech_ecommerce_income_stats_daily');
			}

			// an appropriate date from which to start... first thread, or earliest user reg?
			$this->data['position'] = min(
				$db->fetchOne('SELECT MIN(log_date) FROM xf_dbtech_ecommerce_purchase_log') ?: \XF::$time,
				$db->fetchOne('SELECT MIN(order_date) FROM xf_dbtech_ecommerce_order') ?: \XF::$time
			);

			// start on a 24 hour increment point
			$this->data['position'] = $this->data['position'] - $this->data['position'] % 86400;
		}
		elseif ($this->data['position'] > \XF::$time)
		{
			return $this->complete();
		}

		$end = $this->data['position'] + $this->data['batch'] * 86400;

		/** @var \DBTech\eCommerce\Repository\IncomeStats $statsRepo */
		$statsRepo = $this->app->repository('DBTech\eCommerce:IncomeStats');
		$statsRepo->build($this->data['position'], $end);

		$this->data['position'] = $end;

		return $this->resume();
	}
	
	/**
	 * @return string
	 */
	public function getStatusMessage(): string
	{
		$actionPhrase = \XF::phrase('rebuilding');
		$typePhrase = \XF::phrase('daily_statistics');
		return sprintf('%s... %s (%s)', $actionPhrase, $typePhrase, \XF::language()->date($this->data['position'], 'absolute'));
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
		return true;
	}
}