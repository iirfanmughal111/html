<?php

namespace DBTech\eCommerce\Import\Data;

use XF\Import\Data\AbstractEmulatedData;

/**
 * Class PaymentProviderLog
 *
 * @package DBTech\eCommerce\Import\Data
 */
class PaymentProviderLog extends AbstractEmulatedData
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
		return 'payment_provider_log';
	}
	
	/**
	 * @return string
	 */
	protected function getEntityShortName(): string
	{
		return 'XF:PaymentProviderLog';
	}
}