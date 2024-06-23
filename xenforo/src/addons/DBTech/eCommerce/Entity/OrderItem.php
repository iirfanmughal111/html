<?php

namespace DBTech\eCommerce\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property int|null $order_item_id
 * @property int $order_id
 * @property int $user_id
 * @property int $product_id
 * @property int $product_cost_id
 * @property int $parent_order_item_id
 * @property int $license_id
 * @property int $parent_license_id
 * @property int $coupon_id
 * @property int $shipping_method_id
 * @property string $item_type
 * @property array $product_fields_
 * @property int $quantity
 * @property float $base_price_
 * @property float $sale_discount_
 * @property float $coupon_discount_
 * @property float $shipping_cost_
 * @property float $taxable_price_
 * @property float $sales_tax_
 * @property float $price_
 * @property string $currency
 * @property array $extra_data
 * @property int $discussion_thread_id
 *
 * GETTERS
 * @property string $title
 * @property string $full_title
 * @property float|int|mixed|string|\XF\Phrase|null $base_price
 * @property float $sales_tax
 * @property float|string $sale_discount
 * @property float $discounted_price
 * @property float $taxable_price
 * @property float $price
 * @property float $coupon_discount
 * @property float $shipping_cost
 * @property \XF\CustomField\Set $product_fields
 * @property \XF\Entity\User $User
 *
 * RELATIONS
 * @property \DBTech\eCommerce\Entity\Order $Order
 * @property \XF\Entity\User $User_
 * @property \DBTech\eCommerce\Entity\Product $Product
 * @property \XF\Mvc\Entity\AbstractCollection|\DBTech\eCommerce\Entity\ProductFieldValue[] $ProductFields
 * @property \DBTech\eCommerce\Entity\ProductCost $Cost
 * @property \DBTech\eCommerce\Entity\License $License
 * @property \DBTech\eCommerce\Entity\License $ParentLicense
 * @property \DBTech\eCommerce\Entity\Coupon $Coupon
 * @property \DBTech\eCommerce\Entity\ShippingMethod $ShippingMethod
 * @property \XF\Entity\Thread $Discussion
 */
class OrderItem extends Entity
{
	/**
	 * @return string
	 */
	public function getTitle(): string
	{
		return $this->Product->title;
	}

	/**
	 * @return string
	 * @throws \Exception
	 */
	public function getFullTitle(): string
	{
		if ($this->Product->hasLicenseFunctionality())
		{
			if (!$this->Product->Parent)
			{
				return $this->getTitle();
			}

			$template = \XF::options()->dbtechEcommerceAddonProductTitle;
			return str_replace(['{title}', '{parent}'], [$this->getTitle(), $this->Product->Parent->title], $template);
		}
		else
		{
			$template = \XF::options()->dbtechEcommercePhysicalProductTitle;
			return str_replace(['{title}', '{variation}'], [$this->getTitle(), $this->Cost->title], $template);
		}
	}

	public function getItemTypePhrase(): \XF\Phrase
	{
		return \XF::phrase('dbtech_ecommerce_item_type.'. $this->item_type)
				  ->fallback(\XF::phrase('dbtech_ecommerce_unknown_purchase_type'));
	}

	/**
	 * @throws \LogicException
	 * @throws \XF\PrintableException
	 * @throws \Exception
	 */
	public function canView(): bool
	{
		if ($this->Order->order_state == 'pending')
		{
			$valid = \XF::asVisitor($this->User, function (): bool
			{
				return (
					$this->Product
					&& $this->Product->canView()
					&& $this->Product->canPurchase($this->License)
				);
			});
			
			if (!$valid)
			{
				$this->delete();
				return false;
			}
		}
		
		return true;
	}
	
	/**
	 * @return bool
	 * @throws \LogicException
	 * @throws \Exception
	 */
	public function validateCoupon(): bool
	{
		if ($this->Coupon)
		{
			if (!$this->Coupon->isApplicable($this->Product))
			{
				$this->removeCoupon();
				
				return false;
			}
			
			return true;
		}
		
		return false;
	}
	
	/**
	 * @return bool
	 * @throws \LogicException
	 * @throws \Exception
	 */
	public function removeCoupon(): bool
	{
		$this->fastUpdate('coupon_id', 0);
		unset($this->_relations['Coupon']);
		
		return true;
	}
	
	/**
	 * @return bool
	 * @throws \LogicException
	 * @throws \Exception
	 */
	public function validateShippingMethod(): bool
	{
		if ($this->ShippingMethod)
		{
			if (
				!$this->Order->ShippingAddress
				|| !$this->ShippingMethod->isApplicable($this->Order->ShippingAddress)
				|| !$this->ShippingMethod->isApplicableToProduct($this->Product)
			) {
				$this->removeShippingMethod();
				
				return false;
			}
			
			return true;
		}
		
		return false;
	}
	
	/**
	 * @return bool
	 * @throws \LogicException
	 * @throws \Exception
	 */
	public function removeShippingMethod(): bool
	{
		$this->fastUpdate('shipping_method_id', 0);
		unset($this->_relations['ShippingMethod']);
		
		return true;
	}

	/**
	 * @return float|int|mixed|string|\XF\Phrase|null
	 * @throws \Exception
	 */
	public function getBasePrice()
	{
		if ($this->Order->isPending())
		{
			$cost = 0.00;
			if ($this->Cost)
			{
				$cost = $this->Cost->getPrice($this->License, false, false) * $this->quantity;
			}
			
			return $cost;
		}
		
		return $this->base_price_;
	}

	/**
	 * @return float|string
	 * @throws \Exception
	 */
	public function getSaleDiscount()
	{
		if ($this->Order->isPending())
		{
			$discount = 0.00;
			if ($this->Cost)
			{
				$discount += (
					$this->getBasePrice()
					- ($this->Cost->getPrice($this->License, true, false) * $this->quantity)
				);
			}
			
			return sprintf("%.2f", $discount);
		}
		
		return $this->sale_discount_;
	}
	
	/**
	 * @return float
	 * @throws \LogicException
	 * @throws \Exception
	 */
	public function getCouponDiscount(): float
	{
		if ($this->Order->isPending())
		{
			if ($this->Coupon && $this->Order->validateCoupon() && $this->validateCoupon())
			{
				return sprintf("%.2f", (
					$this->getBasePrice()
					- $this->Coupon->getDiscountedCost($this->Product, $this->getBasePrice())
				));
			}
			
			return 0.00;
		}
		
		return $this->coupon_discount_;
	}
	
	/**
	 * @return float
	 * @throws \LogicException
	 * @throws \Exception
	 */
	public function getShippingCost(): float
	{
		if ($this->Order->isPending())
		{
			if ($this->ShippingMethod && $this->validateShippingMethod())
			{
				return $this->ShippingMethod->calculateShippingCost($this->Order);
			}
			
			return 0.00;
		}
		
		return $this->extra_data['shipping_cost'];
	}
	
	/**
	 * @return float
	 * @throws \Exception
	 */
	public function getSalesTax(): float
	{
		if ($this->Order->isPending())
		{
			$salesTaxRate = $this->Order->getSalesTaxRate(
				$this->Product->hasShippingFunctionality() ? 'physical' : 'digital'
			);

			if (!$salesTaxRate)
			{
				return 0.00;
			}
			
			return sprintf("%.2f", $this->getTaxablePrice() * ($salesTaxRate / 100));
		}
		
		return $this->sales_tax_;
	}
	
	/**
	 * @return float
	 * @throws \LogicException
	 * @throws \Exception
	 */
	public function getDiscountedPrice(): float
	{
		if ($this->Order->isPending())
		{
			$cost = 0.00;
			if ($this->Cost)
			{
				$cost = $this->Cost->getPrice($this->License, true, false) * $this->quantity;
			}
			
			$cost -= $this->getCouponDiscount();
			
			return $cost;
		}
		
		return $this->extra_data['discounted_price'];
	}
	
	/**
	 * @return float
	 * @throws \LogicException
	 * @throws \Exception
	 */
	public function getTaxablePrice(): float
	{
		if ($this->Order->isPending())
		{
			$cost = 0.00;
			if ($this->Cost)
			{
				$cost = $this->Cost->getPrice($this->License, true, false) * $this->quantity;
			}
			
			$cost -= $this->getCouponDiscount();
			$cost += $this->getShippingCost();
			
			return $cost;
		}
		
		return $this->taxable_price_;
	}
	
	/**
	 * @return float
	 * @throws \LogicException
	 * @throws \Exception
	 */
	public function getPrice(): float
	{
		if ($this->Order->isPending())
		{
			$price = $this->getTaxablePrice();
			$price += $this->getSalesTax();
			
			return max(0.00, sprintf("%.2f", $price));
		}
		
		return $this->price_;
	}

	/**
	 * @return \XF\CustomField\Set
	 * @throws \Exception
	 */
	public function getProductFields(): \XF\CustomField\Set
	{
		$class = 'XF\CustomField\Set';
		$class = $this->app()->extendClass($class);

		/** @var \XF\CustomField\DefinitionSet $fieldDefinitions */
		$fieldDefinitions = $this->app()->container('customFields.dbtechEcommerceOrders');

		return new $class($fieldDefinitions, $this, 'product_fields');
	}
	
	/**
	 * @return \XF\Entity\User
	 */
	public function getUser(): \XF\Entity\User
	{
		return $this->getRelation('User') ?: $this->repository('XF:User')->getGuestUser();
	}
	
	/**
	 * @return bool
	 */
	protected function _preSave(): bool
	{
		if ($this->product_cost_id && $this->Cost->product_id != $this->product_id)
		{
			$this->error(\XF::phrase('dbtech_ecommerce_selected_pricing_tier_invalid'));
			return false;
		}
		
		return true;
	}

	/**
	 * @throws \XF\Db\Exception
	 */
	protected function _postSave()
	{
		if ($this->isInsert())
		{
			if ($this->user_id)
			{
				$this->db()->query('
					UPDATE xf_user
					SET dbtech_ecommerce_cart_items = dbtech_ecommerce_cart_items + ?
					WHERE user_id = ?
				', [$this->quantity, $this->user_id]);
			}
			else
			{
				/** @var \DBTech\eCommerce\XF\Entity\User $visitor */
				$visitor = \XF::visitor();
				$visitor->rebuildDbtechEcommerceCartItems($this->order_id);
			}
		}
		elseif ($this->isChanged('quantity'))
		{
			/** @var \DBTech\eCommerce\XF\Entity\User $visitor */
			$visitor = \XF::visitor();
			$visitor->rebuildDbtechEcommerceCartItems($this->order_id);
		}
	}

	/**
	 * @throws \XF\Db\Exception
	 */
	protected function _postDelete()
	{
		if ($this->user_id)
		{
			$this->db()->query('
				UPDATE xf_user
				SET dbtech_ecommerce_cart_items = GREATEST(0, CAST(dbtech_ecommerce_cart_items AS SIGNED) - ?)
				WHERE user_id = ?
			', [$this->quantity, $this->user_id]);
		}
		else
		{
			/** @var \DBTech\eCommerce\XF\Entity\User $visitor */
			$visitor = \XF::visitor();
			$visitor->rebuildDbtechEcommerceCartItems($this->order_id);
		}
	}
	
	/**
	 * @param \XF\Mvc\Entity\Structure $structure
	 *
	 * @return \XF\Mvc\Entity\Structure
	 */
	public static function getStructure(Structure $structure): Structure
	{
		$structure->table = 'xf_dbtech_ecommerce_order_item';
		$structure->shortName = 'DBTech\eCommerce:OrderItem';
		$structure->primaryKey = 'order_item_id';
		$structure->columns = [
			'order_item_id'        => ['type' => self::UINT, 'autoIncrement' => true, 'nullable' => true],
			'order_id'             => ['type' => self::UINT, 'required' => true],
			'user_id'              => ['type' => self::UINT, 'default' => 0],
			'product_id'           => ['type' => self::UINT, 'required' => true],
			'product_cost_id'      => ['type' => self::UINT, 'required' => true],
			'parent_order_item_id' => ['type' => self::UINT, 'default' => 0],
			'license_id'           => ['type' => self::UINT, 'default' => 0],
			'parent_license_id'    => ['type' => self::UINT, 'default' => 0],
			'coupon_id'            => ['type' => self::UINT, 'default' => 0],
			'shipping_method_id'   => ['type' => self::UINT, 'default' => 0],
			'item_type'            => [
				'type'          => self::STR,
				'default'       => 'new',
				'allowedValues' => ['new', 'upgrade', 'renew']
			],
			'product_fields'       => ['type' => self::JSON_ARRAY, 'default' => []],
			'quantity'             => ['type' => self::UINT, 'default' => 1],
			'base_price'           => ['type' => self::FLOAT, 'min' => 0, 'default' => 0.00],
			'sale_discount'        => ['type' => self::FLOAT, 'min' => 0, 'default' => 0.00],
			'coupon_discount'      => ['type' => self::FLOAT, 'min' => 0, 'default' => 0.00],
			'shipping_cost'        => ['type' => self::FLOAT, 'min' => 0, 'default' => 0.00],
			'taxable_price'        => ['type' => self::FLOAT, 'min' => 0, 'default' => 0.00],
			'sales_tax'            => ['type' => self::FLOAT, 'min' => 0, 'default' => 0.00],
			'price'                => ['type' => self::FLOAT, 'min' => 0, 'default' => 0.00],
			'currency'             => [
				'type'      => self::STR,
				'maxLength' => 3,
				'default'   => \XF::options()->dbtechEcommerceCurrency
			],
			'extra_data'           => ['type' => self::JSON_ARRAY, 'default' => []],
			'discussion_thread_id' => ['type' => self::UINT, 'default' => 0]
		];
		$structure->behaviors = [
			'XF:CustomFieldsHolder' => [
				'column'           => 'product_fields',
				'valueTable'       => 'xf_dbtech_ecommerce_order_field_value',
				'checkForUpdates'  => ['product_id'],
				'getAllowedFields' => function (OrderItem $orderItem): array
				{
					return $orderItem->Product ? $orderItem->Product->field_cache : [];
				}
			]
		];
		$structure->getters = [
			'title'            => true,
			'full_title'       => true,
			'base_price'       => true,
			'sales_tax'        => true,
			'sale_discount'    => true,
			'discounted_price' => true,
			'taxable_price'    => true,
			'price'            => true,
			'coupon_discount'  => true,
			'shipping_cost'    => true,
			'product_fields'   => true,
			'User'             => true
		];
		$structure->relations = [
			'Order'          => [
				'entity'     => 'DBTech\eCommerce:Order',
				'type'       => self::TO_ONE,
				'conditions' => 'order_id',
				'primary'    => true
			],
			'User'           => [
				'entity'     => 'XF:User',
				'type'       => self::TO_ONE,
				'conditions' => 'user_id',
				'primary'    => true
			],
			'Product'        => [
				'entity'     => 'DBTech\eCommerce:Product',
				'type'       => self::TO_ONE,
				'conditions' => 'product_id',
				'primary'    => true
			],
			'ProductFields'  => [
				'entity'     => 'DBTech\eCommerce:ProductFieldValue',
				'type'       => self::TO_MANY,
				'conditions' => 'order_item_id',
				'key'        => 'field_id'
			],
			'Cost'           => [
				'entity'     => 'DBTech\eCommerce:ProductCost',
				'type'       => self::TO_ONE,
				'conditions' => 'product_cost_id',
				'primary'    => true
			],
			'License'        => [
				'entity'     => 'DBTech\eCommerce:License',
				'type'       => self::TO_ONE,
				'conditions' => 'license_id',
				'primary'    => true
			],
			'ParentLicense'  => [
				'entity'     => 'DBTech\eCommerce:License',
				'type'       => self::TO_ONE,
				'conditions' => [
					['license_id', '=', '$parent_license_id']
				],
				'primary'    => true
			],
			'Coupon'         => [
				'entity'     => 'DBTech\eCommerce:Coupon',
				'type'       => self::TO_ONE,
				'conditions' => 'coupon_id',
				'primary'    => true
			],
			'ShippingMethod' => [
				'entity'     => 'DBTech\eCommerce:ShippingMethod',
				'type'       => self::TO_ONE,
				'conditions' => 'shipping_method_id',
				'primary'    => true
			],
			'Discussion'     => [
				'entity'     => 'XF:Thread',
				'type'       => self::TO_ONE,
				'conditions' => [
					['thread_id', '=', '$discussion_thread_id']
				],
				'primary'    => true
			]
		];
		$structure->options = [];
		$structure->defaultWith = [
			'Order',
			'Coupon',
			'ShippingMethod'
		];

		return $structure;
	}

	/**
	 * @return \DBTech\eCommerce\Repository\Order|\XF\Mvc\Entity\Repository
	 */
	protected function getOrderRepo()
	{
		return $this->repository('DBTech\eCommerce:Order');
	}
}