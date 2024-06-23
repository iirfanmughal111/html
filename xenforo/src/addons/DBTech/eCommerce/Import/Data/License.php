<?php

namespace DBTech\eCommerce\Import\Data;

use XF\Import\Data\AbstractEmulatedData;

/**
 * Class License
 *
 * @package DBTech\eCommerce\Import\Data
 */
class License extends AbstractEmulatedData
{
	/**
	 * @return string
	 */
	public function getImportType(): string
	{
		return 'license';
	}
	
	/**
	 * @return string
	 */
	protected function getEntityShortName(): string
	{
		return 'DBTech\eCommerce:License';
	}
}