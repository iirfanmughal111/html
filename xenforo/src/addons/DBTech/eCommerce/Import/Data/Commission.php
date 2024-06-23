<?php

namespace DBTech\eCommerce\Import\Data;

use XF\Import\Data\AbstractEmulatedData;

/**
 * Class Commission
 *
 * @package DBTech\eCommerce\Import\Data
 */
class Commission extends AbstractEmulatedData
{
	/**
	 * @var array
	 */
	protected $productCommissions = [];
	
	
	/**
	 * @return string
	 */
	public function getImportType(): string
	{
		return 'commission';
	}
	
	/**
	 * @return string
	 */
	protected function getEntityShortName(): string
	{
		return 'DBTech\eCommerce:Commission';
	}
	
	/**
	 * @param array $info
	 */
	public function addProduct(array $info)
	{
		$this->productCommissions[] = $info;
	}
	
	/**
	 * @param $oldId
	 * @param $newId
	 */
	protected function postSave($oldId, $newId)
	{
		if ($this->productCommissions)
		{
			/** @var \DBTech\eCommerce\Repository\ProductCommission $repo */
			$repo = $this->repository('DBTech\eCommerce:ProductCommission');
			$repo->updateContentAssociations($newId, $this->productCommissions);
		}
	}
}