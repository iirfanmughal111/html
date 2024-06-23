<?php

namespace DBTech\eCommerce\Install;

/**
 * @property \XF\AddOn\AddOn addOn
 * @property \XF\App app
 *
 * @method \XF\Db\AbstractAdapter db()
 * @method \XF\Db\SchemaManager schemaManager()
 * @method \XF\Db\Schema\Column addOrChangeColumn($table, $name, $type = null, $length = null)
 */
trait Upgrade2009970Trait
{
	/**
	 *
	 */
	public function upgrade2000031Step1()
	{
		$this->applyTables();
	}

	/**
	 *
	 */
	public function upgrade2000031Step2()
	{
		$this->query("
			UPDATE xf_dbtech_ecommerce_product
			SET product_type = IF(product_type = 'digital', 'dbtech_ecommerce_digital', 'dbtech_ecommerce_physical')
		");

		$this->query("
			UPDATE xf_dbtech_ecommerce_product_cost
			SET product_type = IF(product_type = 'digital', 'dbtech_ecommerce_digital', 'dbtech_ecommerce_physical')
		");
	}

	/**
	 *
	 */
	public function upgrade2000370Step1()
	{
		$this->applyTables();
	}

	/**
	 *
	 */
	public function upgrade2010270Step1()
	{
		$this->applyTables();
	}

	/**
	 * @param array $stepParams
	 *
	 * @return array|bool
	 * @throws \XF\Db\Exception
	 */
	public function upgrade2010270Step2(array $stepParams)
	{
		$position = empty($stepParams[0]) ? 0 : $stepParams[0];
		$perPage = 250;

		$db = $this->db();

		if (!isset($stepParams['max']))
		{
			$stepParams['max'] = $db->fetchOne("
				SELECT MAX(order_id)
				FROM xf_dbtech_ecommerce_order
				WHERE `order_state` = 'completed'
					AND `completed_date` = 0
					AND `purchase_request_key` <> ''
			");
		}

		$orderIds = $db->fetchAllColumn($db->limit(
			"
				SELECT DISTINCT order_id
				FROM xf_dbtech_ecommerce_order
				WHERE order_id > ?
					AND `order_state` = 'completed'
					AND `completed_date` = 0
					AND `purchase_request_key` <> ''
				ORDER BY order_id
			",
			$perPage
		), $position);
		if (!$orderIds)
		{
			return true;
		}

		$db->beginTransaction();

		$queryResults = $db->query('
			SELECT *
			FROM xf_dbtech_ecommerce_order
			WHERE order_id IN (' . $db->quote($orderIds) . ')
			ORDER BY order_id
		');
		while ($result = $queryResults->fetch())
		{
			$logDate = $db->fetchOne("
				SELECT `log_date` 
				FROM `xf_payment_provider_log` 
				WHERE `purchase_request_key` = ? 
			        AND `log_type` = 'payment' 
			        AND `log_message` = 'Payment received, order processed.'
			", [$result['purchase_request_key']], 'log_date');

			if ($logDate)
			{
				$db->update('xf_dbtech_ecommerce_order', [
					'completed_date' => $logDate
				], 'order_id = ?', [$result['order_id']]);
			}
		}

		$db->commit();

		$next = end($orderIds);

		return [
			$next,
			"{$next} / {$stepParams['max']}",
			$stepParams
		];
	}

	/**
	 * @param array $stepParams
	 *
	 * @return array|bool
	 * @throws \XF\Db\Exception
	 */
	public function upgrade2010270Step3(array $stepParams)
	{
		$position = empty($stepParams[0]) ? 0 : $stepParams[0];
		$perPage = 250;

		$db = $this->db();

		if (!isset($stepParams['max']))
		{
			$stepParams['max'] = $db->fetchOne("
				SELECT MAX(order_id)
				FROM xf_dbtech_ecommerce_order
				WHERE `order_state` = 'reversed'
					AND `reversed_date` = 0
					AND `purchase_request_key` <> ''
			");
		}

		$orderIds = $db->fetchAllColumn($db->limit(
			"
				SELECT DISTINCT order_id
				FROM xf_dbtech_ecommerce_order
				WHERE order_id > ?
					AND `order_state` = 'reversed'
					AND `reversed_date` = 0
					AND `purchase_request_key` <> ''
				ORDER BY order_id
			",
			$perPage
		), $position);
		if (!$orderIds)
		{
			return true;
		}

		$db->beginTransaction();

		$queryResults = $db->query('
			SELECT *
			FROM xf_dbtech_ecommerce_order
			WHERE order_id IN (' . $db->quote($orderIds) . ')
			ORDER BY order_id
		');
		while ($result = $queryResults->fetch())
		{
			$logDate = $db->fetchOne("
				SELECT `log_date` 
				FROM `xf_payment_provider_log` 
				WHERE `purchase_request_key` = ? 
			        AND `log_type` = 'payment' 
			        AND `log_message` = 'Payment refunded/reversed, order reversed.'
			", [$result['purchase_request_key']], 'log_date');

			if ($logDate)
			{
				$db->update('xf_dbtech_ecommerce_order', [
					'reversed_date' => $logDate
				], 'order_id = ?', [$result['order_id']]);
			}
		}

		$db->commit();

		$next = end($orderIds);

		return [
			$next,
			"{$next} / {$stepParams['max']}",
			$stepParams
		];
	}

	/**
	 * @param $previousVersion
	 * @param array $stateChanges
	 */
	protected function postUpgrade2000370($previousVersion, array &$stateChanges)
	{
		$this->app->jobManager()->enqueueUnique(
			'dbtechEcommerceRebuildProducts',
			'DBTech\eCommerce:Product',
			[],
			false
		);
	}
}