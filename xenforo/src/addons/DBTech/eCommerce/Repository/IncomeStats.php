<?php

namespace DBTech\eCommerce\Repository;

use XF\Mvc\Entity\Repository;

/**
 * Class IncomeStats
 * @package DBTech\eCommerce\Repository
 */
class IncomeStats extends Repository
{
	/** @var array */
	protected $statsTypes = [];
	
	
	/**
	 * @param array|null $only
	 *
	 * @return array
	 */
	public function getStatsTypePhrases(array $only = null): array
	{
		$phrases = [
			-1 => \XF::phrase('dbtech_ecommerce_all_products')
		];
		
		$phrases = $phrases + $this->getStatsTypes();
		
		if (is_array($only))
		{
			$final = [];
			foreach ($only AS $k)
			{
				if (isset($phrases[$k]))
				{
					$final[$k] = $phrases[$k];
				}
			}
			
			$phrases = $final;
		}
		
		return $phrases;
	}
	
	/**
	 * @param int $start
	 * @param int $end
	 */
	public function build(int $start, int $end)
	{
		$db = $this->db();
		$db->beginTransaction();
		
		$records = $db->fetchPairs('
				SELECT
					log_date - log_date % 86400 AS unixDate,
					SUM(cost_amount)
				FROM xf_dbtech_ecommerce_purchase_log
				WHERE log_date BETWEEN ? AND ?
				GROUP BY unixDate
			', [$start, $end]);
		
		foreach ($records AS $date => $counter)
		{
			$db->insert('xf_dbtech_ecommerce_income_stats_daily', [
				'stats_date' => $date,
				'product_id' => -1,
				'counter' => $counter
			], false, "counter = $counter");
		}
		
		foreach ($this->getStatsTypes() AS $productId => $title)
		{
			$records = $db->fetchPairs('
				SELECT
					log_date - log_date % 86400 AS unixDate,
					SUM(cost_amount)
				FROM xf_dbtech_ecommerce_purchase_log
				WHERE log_date BETWEEN ? AND ?
					AND product_id = ?
				GROUP BY unixDate
			', [$start, $end, $productId]);
			
			foreach ($records AS $date => $counter)
			{
				$db->insert('xf_dbtech_ecommerce_income_stats_daily', [
					'stats_date' => $date,
					'product_id' => $productId,
					'counter' => $counter
				], false, "counter = $counter");
			}
		}
		
		$db->commit();
	}

	/**
	 * @return array
	 */
	public function getStatsTypes(): array
	{
		if (!$this->statsTypes)
		{
			$productRepo = $this->repository('DBTech\eCommerce:Product');
			foreach ($productRepo->getFlattenedProductTree() AS $treeEntry)
			{
				$this->statsTypes[$treeEntry['record']['product_id']] = str_repeat('--;', $treeEntry['depth']) . ' ' . $treeEntry['record']['title'];
			}
		}
		return $this->statsTypes;
	}
}