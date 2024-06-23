<?php

namespace DBTech\eCommerce\Import\Data;

use XF\Import\Data\AbstractEmulatedData;

/**
 * Class CommissionPayment
 *
 * @package DBTech\eCommerce\Import\Data
 */
class CommissionPayment extends AbstractEmulatedData
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
		return 'commission_payment';
	}
	
	/**
	 * @return string
	 */
	protected function getEntityShortName(): string
	{
		return 'DBTech\eCommerce:CommissionPayment';
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
		$this->logIp($this->loggedIp, $this->payment_date);
	}
}