<?php

namespace DBTech\eCommerce\Import\Data;

use XF\Import\Data\AbstractEmulatedData;

/**
 * Class Distributor
 *
 * @package DBTech\eCommerce\Import\Data
 */
class Distributor extends AbstractEmulatedData
{
	/**
	 * @return string
	 */
	public function getImportType(): string
	{
		return 'distributor';
	}
	
	/**
	 * @return string
	 */
	protected function getEntityShortName(): string
	{
		return 'DBTech\eCommerce:Distributor';
	}
}