<?php

namespace DBTech\eCommerce\Stats;

use XF\Stats\AbstractHandler;

/**
 * Class Product
 *
 * @package DBTech\eCommerce\Stats
 */
class Product extends AbstractHandler
{
	/**
	 * @return array
	 */
	public function getStatsTypes(): array
	{
		return [
			'dbt_ecom_product' => \XF::phrase('dbtech_ecommerce_products_added'),
			'dbt_ecom_download' => \XF::phrase('dbtech_ecommerce_updates_released'),
			'dbt_ecom_product_reaction' => \XF::phrase('dbtech_ecommerce_product_reactions'),
			'dbt_ecom_product_rating' => \XF::phrase('dbtech_ecommerce_product_ratings'),
			'dbt_ecom_total_downloads' => \XF::phrase('dbtech_ecommerce_total_product_downloads'),
			'dbt_ecom_purchase' => \XF::phrase('dbtech_ecommerce_purchases'),
			'dbt_ecom_income' => \XF::phrase('dbtech_ecommerce_income'),
		];
	}

	/**
	 * @param $start
	 * @param $end
	 *
	 * @return array
	 */
	public function getData($start, $end): array
	{
		$db = $this->db();

		$products = $db->fetchPairs(
			$this->getBasicDataQuery('xf_dbtech_ecommerce_product', 'creation_date', 'product_state = ?'),
			[$start, $end, 'visible']
		);

		$downloads = $db->fetchPairs(
			$this->getBasicDataQuery('xf_dbtech_ecommerce_download', 'release_date', 'download_state = ?'),
			[$start, $end, 'visible']
		);

		$totalDownloads = $db->fetchPairs(
			$this->getBasicDataQuery('xf_dbtech_ecommerce_download_log', 'log_date'),
			[$start, $end]
		);

		$purchases = $db->fetchPairs(
			$this->getBasicDataQuery('xf_dbtech_ecommerce_purchase_log', 'log_date'),
			[$start, $end]
		);

		$income = $db->fetchPairs(
			$this->getBasicDataQuery('xf_dbtech_ecommerce_purchase_log', 'log_date', '', 'IF(SUM(cost_amount) * 100 > 0, SUM(cost_amount) * 100, 0)'),
			[$start, $end]
		);
		
		$productReactions = $db->fetchPairs(
			$this->getBasicDataQuery('xf_reaction_content', 'reaction_date', 'content_type = ? AND is_counted = ?'),
			[$start, $end, 'dbtech_ecommerce_product', 1]
		);
		
		
		$productRatings = $db->fetchPairs(
			$this->getBasicDataQuery('xf_dbtech_ecommerce_product_rating', 'rating_date', 'rating_state = ?'),
			[$start, $end, 'visible']
		);

		return [
			'dbt_ecom_product' => $products,
			'dbt_ecom_download' => $downloads,
			'dbt_ecom_total_downloads' => $totalDownloads,
			'dbt_ecom_purchase' => $purchases,
			'dbt_ecom_income' => $income,
			'dbt_ecom_product_reaction' => $productReactions,
			'dbt_ecom_product_rating' => $productRatings
		];
	}

	/**
	 * @param string $statsType
	 * @param number $counter
	 *
	 * @return float|number
	 */
	public function adjustStatValue($statsType, $counter): float
	{
		if ($statsType == 'dbt_ecom_income')
		{
			return round($counter / 100, 2); // currency
		}
		return parent::adjustStatValue($statsType, $counter);
	}
}