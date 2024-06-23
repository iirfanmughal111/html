<?php

namespace DBTech\eCommerce\Import\Data;

use XF\Import\Data\AbstractEmulatedData;

/**
 * Class ProductFieldValue
 *
 * @package DBTech\eCommerce\Import\Data
 */
class ProductFieldValue extends AbstractEmulatedData
{
	/**
	 * @return string
	 */
	public function getImportType(): string
	{
		return 'product_field_value';
	}
	
	/**
	 * @return string
	 */
	protected function getEntityShortName(): string
	{
		return 'DBTech\eCommerce:ProductFieldValue';
	}
}