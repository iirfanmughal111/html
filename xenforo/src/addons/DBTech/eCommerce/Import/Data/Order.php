<?php

namespace DBTech\eCommerce\Import\Data;

use XF\Import\Data\AbstractEmulatedData;

/**
 * Class Order
 *
 * @package DBTech\eCommerce\Import\Data
 */
class Order extends AbstractEmulatedData
{
	/**
	 * @return string
	 */
	public function getImportType(): string
	{
		return 'order';
	}
	
	/**
	 * @return string
	 */
	protected function getEntityShortName(): string
	{
		return 'DBTech\eCommerce:Order';
	}
}