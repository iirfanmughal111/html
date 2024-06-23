<?php

namespace DBTech\eCommerce\Entity;

use XF\Mvc\Entity\Structure;
use XF\Mvc\Entity\Entity;

/**
 * COLUMNS
 * @property int $product_id
 * @property int $commission_id
 * @property string $commission_type
 * @property float $commission_value
 *
 * GETTERS
 * @property float $commission
 *
 * RELATIONS
 * @property \DBTech\eCommerce\Entity\Product $Product
 * @property \DBTech\eCommerce\Entity\Commission $Commission
 */
class ProductCommissionValue extends Entity
{
	/**
	 * @param PurchaseLog $purchase
	 *
	 * @return float
	 */
	public function getCommission(PurchaseLog $purchase)
	{
		if ($this->commission_type == 'percent')
		{
			$commission = $purchase->log_details['taxable_price'] * ($this->commission_value / 100);
		}
		else
		{
			$commission = $this->commission_value;
		}
		
		switch ($purchase->log_type)
		{
			case 'refunded':
			case 'reversal':
				$commission *= -1;
				break;
		}
		
		return $commission;
	}
	
	/**
	 * @param \XF\Mvc\Entity\Structure $structure
	 *
	 * @return \XF\Mvc\Entity\Structure
	 */
	public static function getStructure(Structure $structure): Structure
	{
		$structure->table = 'xf_dbtech_ecommerce_product_commission_value';
		$structure->shortName = 'DBTech\eCommerce:ProductCommissionValue';
		$structure->primaryKey = ['product_id', 'commission_id'];
		$structure->columns = [
			'product_id' => ['type' => self::UINT, 'required' => true],
			'commission_id' => ['type' => self::UINT, 'required' => true],
			'commission_type' => ['type' => self::STR, 'default' => 'percent',
							  'allowedValues' => ['percent', 'value']
			],
			'commission_value' => ['type' => self::FLOAT, 'required' => true, 'min' => 0],
		];
		$structure->getters = [
			'commission' => true
		];
		$structure->relations = [
			'Product' => [
				'entity' => 'DBTech\eCommerce:Product',
				'type' => self::TO_ONE,
				'conditions' => 'product_id',
				'primary' => true
			],
			'Commission' => [
				'entity' => 'DBTech\eCommerce:Commission',
				'type' => self::TO_ONE,
				'conditions' => 'commission_id',
				'primary' => true
			],
		];

		return $structure;
	}
}