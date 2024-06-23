<?php

namespace DBTech\eCommerce\Job;

use XF\Job\AbstractRebuildJob;

/**
 * Class LicenseExtend
 *
 * @package DBTech\eCommerce\Job
 */
class LicenseExtend extends AbstractRebuildJob
{
	/** @var array */
	protected $defaultData = [
		'productIds' => [],
		'length_amount' => 7,
		'length_unit' => 'days',
		'refresh_expired' => false,
	];
	
	/**
	 * @param $start
	 * @param $batch
	 *
	 * @return array
	 */
	protected function getNextIds($start, $batch): array
	{
		$db = $this->app->db();
		
		$where = '';
		if ($this->data['productIds'])
		{
			$where = 'AND product_id IN(' . $db->quote($this->data['productIds']) . ')';
		}
		
		return $db->fetchAllColumn($db->limit(
			'
				SELECT license_id
				FROM xf_dbtech_ecommerce_license
				WHERE license_id > ?
					' . $where . '
				ORDER BY license_id
			',
			$batch
		), $start);
	}
	
	/**
	 * @param $id
	 *
	 * @throws \LogicException
	 * @throws \Exception
	 */
	protected function rebuildById($id)
	{
		/** @var \DBTech\eCommerce\Entity\License $license */
		$license = $this->app->em()->find('DBTech\eCommerce:License', $id);
		if (!$license || $license->isLifetime() || !$license->isVisible())
		{
			return;
		}
		
		$license->fastUpdate(
			'expiry_date',
			strtotime(
				'+' . $this->data['length_amount'] . ' ' . $this->data['length_unit'],
				($license->expiry_date >= \XF::$time || !$this->data['refresh_expired'])
					? $license->expiry_date
					: \XF::$time
			)
		);
	}
	
	/**
	 * @param array $data
	 *
	 * @return array
	 * @throws \LogicException
	 */
	protected function setupData(array $data): array
	{
		$data['productIds'] = is_array($data['productIds']) ? $data['productIds'] : [];
		foreach ($data['productIds'] as $key => $productId)
		{
			if (!$productId)
			{
				unset($data['productIds'][$key]);
			}
		}
		
		$data['length_amount'] = $data['length_amount'] ?: 7;
		$data['length_unit'] = in_array($data['length_unit'], ['day', 'month', 'year']) ? $data['length_unit'] : 'day';
		
		return parent::setupData($data);
	}
	
	/**
	 * @return \XF\Phrase
	 */
	protected function getStatusType(): \XF\Phrase
	{
		return \XF::phrase('dbtech_ecommerce_ecommerce_licenses');
	}

	/**
	 * @return bool
	 */
	public function canTriggerByChoice(): bool
	{
		return false;
	}
}