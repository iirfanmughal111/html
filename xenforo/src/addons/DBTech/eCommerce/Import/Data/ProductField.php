<?php

namespace DBTech\eCommerce\Import\Data;

use XF\Import\Data\AbstractField;

/**
 * Class ProductField
 *
 * @package DBTech\eCommerce\Import\Data
 */
class ProductField extends AbstractField
{
	/**
	 * @return string
	 */
	public function getImportType(): string
	{
		return 'product_field';
	}
	
	/**
	 * @return string
	 */
	protected function getEntityShortName(): string
	{
		return 'DBTech\eCommerce:ProductField';
	}
	
	/**
	 * @param $oldId
	 *
	 * @return null|void
	 */
	protected function preSave($oldId)
	{
		if ($this->title === null)
		{
			throw new \LogicException("Must call setTitle with a non-null value to save a product field");
		}
	}
}