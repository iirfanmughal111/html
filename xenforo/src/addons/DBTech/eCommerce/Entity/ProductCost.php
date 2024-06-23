<?php

namespace DBTech\eCommerce\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property int|null $product_cost_id
 * @property int $product_id
 * @property string $product_type
 * @property string $title
 * @property int $creation_date
 * @property float $cost_amount
 * @property string $renewal_type
 * @property float|null $renewal_amount
 * @property bool $highlighted
 * @property string $description
 * @property int $stock
 * @property float $weight
 * @property int $length_amount
 * @property string $length_unit
 *
 * GETTERS
 * @property \XF\Phrase $length
 * @property float|int|mixed|string|\XF\Phrase $price
 *
 * RELATIONS
 * @property \DBTech\eCommerce\Entity\Product $Product
 */
class ProductCost extends Entity
{
	/**
	 * @return bool
	 */
	public function isLifetime(): bool
	{
		return $this->length_amount == 0 OR $this->length_unit === '';
	}
	
	/**
	 * @return \XF\Phrase
	 */
	public function getLength(): \XF\Phrase
	{
		if ($this->isLifetime())
		{
			return \XF::phrase('dbtech_ecommerce_renewal_period_lifetime');
		}
		
		if ($this->length_amount == 1)
		{
			return \XF::phrase('dbtech_ecommerce_renewal_period_one_' . $this->length_unit);
		}
		
		return \XF::phrase('dbtech_ecommerce_renewal_period_x_' . $this->length_unit, [
			'length' => $this->length_amount
		]);
	}
	
	/**
	 * @param License|null $license
	 *
	 * @return int
	 */
	public function getNewExpiryDate(License $license = null): int
	{
		if ($this->isLifetime())
		{
			return 0;
		}
		
		return strtotime('+' . $this->length_amount . ' ' . $this->length_unit, (
			$license ?
				($license->expiry_date >= \XF::$time ? $license->expiry_date : \XF::$time) :
				\XF::$time
		));
	}
	
	/**
	 * @param License|null $license
	 * @param bool $forDisplay
	 *
	 * @return float|int|\XF\Phrase|null
	 */
	public function getDigitalRenewalPrice(License $license = null, $forDisplay = false)
	{
		$cost = $this->cost_amount;

		switch ($this->renewal_type)
		{
			case 'fixed':
				$cost = $this->renewal_amount;
				break;
				
			case 'percentage':
				$cost *= (1 - ($this->renewal_amount / 100));
				break;
			
			case 'global':
			default:
				$options = $this->app()->options();
				
				$cost *= (1 - (
					($license && $license->isActive()
						? $options->dbtechEcommerceRenewDiscount
						: $options->dbtechEcommerceExpiredRenewDiscount) / 100
				));
				break;
		}

		// Round to 2 decimals
		$cost = sprintf("%.2f", $cost);
		
		if ($forDisplay)
		{
			if ($cost == 0.00)
			{
				return \XF::phrase('dbtech_ecommerce_free');
			}
			
			/** @var \XF\Data\Currency $currencyData */
			$currencyData = \XF::app()->data('XF:Currency');
			return $currencyData->languageFormat($cost, $this->app()->options()->dbtechEcommerceCurrency);
		}
		
		return $cost;
	}

	/**
	 * @param License|null $license
	 * @param bool $includeSale
	 * @param bool $forDisplay
	 *
	 * @return float|int|mixed|string|\XF\Phrase
	 * @throws \Exception
	 * @throws \Exception
	 */
	public function getPrice(License $license = null, $includeSale = true, $forDisplay = false)
	{
		if ($this->cost_amount == 0.00)
		{
			return $forDisplay ? \XF::phrase('dbtech_ecommerce_free') : 0.00;
		}
		
		if ($this->Product->hasLicenseFunctionality() && $license)
		{
			$cost = $this->getDigitalRenewalPrice($license);
		}
		else
		{
			$cost = $this->cost_amount;
		}
		
		if ($cost && $includeSale && \XF::options()->dbtechEcommerceSales['enabled'] && $this->Product->Sale)
		{
			$cost = $this->Product->Sale->getDiscountedCost($cost);
		}
		
		if ($forDisplay)
		{
			/** @var \XF\Data\Currency $currencyData */
			$currencyData = \XF::app()->data('XF:Currency');
			return $currencyData->languageFormat($cost, $this->app()->options()->dbtechEcommerceCurrency);
		}
		
		return $cost;
	}

	/**
	 * @param int $quantity
	 *
	 * @throws \XF\Db\Exception
	 * @throws \Exception
	 */
	public function reduceStock(int $quantity = 1)
	{
		if ($this->Product->hasStockFunctionality())
		{
			$this->db()->query('
				UPDATE xf_dbtech_ecommerce_product_cost
				SET stock = GREATEST(0, CAST(stock AS SIGNED) - ?)
				WHERE product_cost_id = ?
			', [$quantity, $this->product_cost_id]);
			
			$this->setAsSaved('stock', max(0, $this->stock - $quantity));
		}
	}

	/**
	 * @param int $quantity
	 *
	 * @throws \Exception
	 */
	public function increaseStock(int $quantity = 1)
	{
		if ($this->Product->hasStockFunctionality())
		{
			$this->fastUpdate('stock', $this->stock + $quantity);
		}
	}
	
	/**
	 * @param $productId
	 * @return bool
	 */
	protected function verifyProduct(&$productId): bool
	{
		if ($this->isInsert())
		{
			// Allow empty product ID on insert
			return true;
		}

		if (!$productId)
		{
			$this->error(\XF::phrase('dbtech_ecommerce_please_enter_valid_product_id'), 'product_id');
			return false;
		}

		$product = $this->_em->find('DBTech\eCommerce:Product', $productId);
		if (!$product)
		{
			$this->error(\XF::phrase('dbtech_ecommerce_please_enter_valid_product_id'), 'product_id');
			return false;
		}

		return true;
	}
	
	/**
	 * @return bool
	 */
	protected function _preSave(): bool
	{
		if (!$this->product_id && $this->isUpdate())
		{
			$this->error(\XF::phrase('dbtech_ecommerce_please_enter_valid_product_id'), 'product_id');
			return false;
		}
		
		return true;
	}
	
	/**
	 * @return bool
	 */
	protected function _preDelete(): bool
	{
		$orderItems = $this->finder('DBTech\eCommerce:OrderItem')
			->with('Order')
			->where('product_cost_id', $this->product_cost_id)
			->where('Order.order_state', 'pending');
		if ($orderItems->total())
		{
			$this->error(\XF::phrase('dbtech_ecommerce_cannot_delete_costs_pending_order'));
			return false;
		}
		
		return true;
	}
	
	/**
	 * @param \XF\Mvc\Entity\Structure $structure
	 *
	 * @return \XF\Mvc\Entity\Structure
	 */
	public static function getStructure(Structure $structure): Structure
	{
		$structure->table = 'xf_dbtech_ecommerce_product_cost';
		$structure->shortName = 'DBTech\eCommerce:ProductCost';
		$structure->primaryKey = 'product_cost_id';
		$structure->columns = [
			'product_cost_id' => ['type' => self::UINT, 'autoIncrement' => true, 'nullable' => true],
			'product_id' => ['type' => self::UINT, 'required' => true, 'default' => 0,
				'verify' => 'verifyProduct'
			],
			'product_type' => ['type' => self::STR, 'default' => 'dbtech_ecommerce_digital'],
			'title' => ['type' => self::STR, 'maxLength' => 100],
			'creation_date' => ['type' => self::UINT, 'default' => \XF::$time],
			'cost_amount' => ['type' => self::FLOAT, 'required' => true, 'min' => 0],
			'renewal_type' => ['type' => self::STR, 'required' => true, 'default' => 'global',
			   'allowedValues' => ['global', 'fixed', 'percentage']
			],
			'renewal_amount' => ['type' => self::FLOAT, 'nullable' => true, 'min' => 0, 'default' => null],
			'highlighted' => ['type' => self::BOOL, 'default' => false],
			'description' => ['type' => self::STR, 'maxLength' => 255],
			'stock' => ['type' => self::UINT, 'default' => 0, 'min' => 0],
			'weight' => ['type' => self::FLOAT, 'default' => 0.00, 'min' => 0],
			'length_amount' => ['type' => self::UINT, 'max' => 365],
			'length_unit' => ['type' => self::STR, 'default' => '',
				'allowedValues' => ['day', 'month', 'year', '']
			]
		];
		$structure->behaviors = [];
		$structure->getters = [
			'length' => true,
			'price' => true
		];
		$structure->relations = [
			'Product' => [
				'entity' => 'DBTech\eCommerce:Product',
				'type' => self::TO_ONE,
				'conditions' => 'product_id',
				'primary' => true,
				'with' => [
					'Sale'
				]
			]
		];
		$structure->options = [];
		$structure->defaultWith = [
			'Product',
			'Product.Sale'
		];

		return $structure;
	}
}