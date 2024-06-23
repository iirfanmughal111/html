<?php

namespace DBTech\eCommerce\Admin\Controller;

use XF\Admin\Controller\AbstractController;
use XF\Mvc\ParameterBag;

/**
 * Class IncomeStats
 *
 * @package DBTech\eCommerce\Admin\Controller
 */
class IncomeStats extends AbstractController
{
	/**
	 * @param $action
	 * @param ParameterBag $params
	 *
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function preDispatchController($action, ParameterBag $params)
	{
		$this->assertAdminPermission('dbtechEcomLogs');
	}

	/**
	 * @return \XF\Mvc\Reply\View
	 * @throws \XF\Db\Exception
	 */
	public function actionIndex(): \XF\Mvc\Reply\AbstractReply
	{
		$grouping = $this->filter('grouping', 'str');
		if (!$grouping || !isset($this->app['stats.groupings'][$grouping]))
		{
			$grouping = 'daily';
		}
		
		/** @var \XF\Stats\Grouper\AbstractGrouper $grouper */
		$grouper = $this->app->create('stats.grouper', $grouping);
		
		$displayTypes = $this->filter('display_types', 'array-uint');
		if (!$displayTypes)
		{
			$displayTypes = [-1];
		}
		
		// by default, we only have colors for 15 lines
		$displayTypes = array_slice($displayTypes, 0, 15);
		
		if (!$start = $this->filter('start', 'datetime'))
		{
			$start = $grouper->getDefaultStartDate();
		}
		
		if (!$end = $this->filter('end', 'datetime'))
		{
			$end = \XF::$time;
		}
		
		$incomeStatsRepo = $this->getIncomeStatsRepo();
		
		/** @var \DBTech\eCommerce\Service\IncomeStats\Grapher $grapher */
		$grapher = $this->service('DBTech\eCommerce:IncomeStats\Grapher', $start, $end, $displayTypes);
		$data = $grapher->getGroupedData($grouper);
		
		$total = 0.00;
		foreach ($data as $stat)
		{
			$total += array_sum($stat['values']);
		}
		
		$viewParams = [
			'grouping' => $grouping,
			'displayTypes' => $displayTypes,
			'displayTypesPhrased' => $incomeStatsRepo->getStatsTypePhrases($displayTypes),
			'data' => $data,
			'total' => $total,
			
			'start' => $start,
			'end' => $end,
			'endDisplay' => ($end >= \XF::$time ? 0 : $end),
			
			'datePresets' => \XF::language()->getDatePresets(),
		];
		return $this->view('DBTech\eCommerce:IncomeStats\Stats', 'dbtech_ecommerce_income_stats', $viewParams);
	}
	
	/**
	 * @return \DBTech\eCommerce\Repository\IncomeStats
	 */
	protected function getIncomeStatsRepo(): \DBTech\eCommerce\Repository\IncomeStats
	{
		return $this->repository('DBTech\eCommerce:IncomeStats');
	}
}