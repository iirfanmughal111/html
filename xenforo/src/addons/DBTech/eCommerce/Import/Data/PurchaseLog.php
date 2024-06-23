<?php

namespace DBTech\eCommerce\Import\Data;

use XF\Import\Data\AbstractEmulatedData;

/**
 * Class PurchaseLog
 *
 * @package DBTech\eCommerce\Import\Data
 */
class PurchaseLog extends AbstractEmulatedData
{
	/**
	 * @var string|null
	 */
	protected $loggedIp;
	
	/**
	 * @return string
	 */
	public function getImportType(): string
	{
		return 'purchase_log';
	}
	
	/**
	 * @return string
	 */
	protected function getEntityShortName(): string
	{
		return 'DBTech\eCommerce:PurchaseLog';
	}
	
	/**
	 * @param $loggedIp
	 */
	public function setLoggedIp($loggedIp)
	{
		$this->loggedIp = $loggedIp;
	}
	
	/**
	 * @param $oldId
	 * @param $newId
	 */
	protected function postSave($oldId, $newId)
	{
		$this->logIp($this->loggedIp, $this->log_date, [
			'action' => 'purchase',
		]);
	}
}