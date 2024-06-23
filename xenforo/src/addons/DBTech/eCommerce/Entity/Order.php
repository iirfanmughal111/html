<?php

namespace DBTech\eCommerce\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property int|null $order_id
 * @property int $user_id
 * @property string $ip_address
 * @property int $order_date
 * @property int $completed_date
 * @property int $reversed_date
 * @property string|null $purchase_request_key
 * @property string $order_state
 * @property int $address_id
 * @property int $shipping_address_id
 * @property int $store_credit_amount
 * @property int $coupon_id
 * @property float $sub_total_
 * @property float $sale_discounts_
 * @property float $coupon_discounts_
 * @property float $automatic_discounts_
 * @property float $shipping_cost_
 * @property float $sales_tax_
 * @property float $taxable_order_total_
 * @property string $currency
 * @property float $cost_amount
 * @property bool $has_invoice
 * @property bool $sent_reminder
 * @property array $extra_data
 *
 * GETTERS
 * @property float $sub_total
 * @property float $sale_discounts
 * @property float $coupon_discounts
 * @property float $automatic_discounts
 * @property float $shipping_cost
 * @property float $discount_total
 * @property float|mixed $sales_tax
 * @property float $taxable_order_total
 * @property float|int $order_total
 * @property \XF\Entity\User $User
 * @property \XF\Mvc\Entity\ArrayCollection $Items
 * @property \XF\Mvc\Entity\ArrayCollection $Discounts
 *
 * RELATIONS
 * @property \XF\Entity\User $User_
 * @property \DBTech\eCommerce\Entity\Address $Address
 * @property \DBTech\eCommerce\Entity\Address $ShippingAddress
 * @property \DBTech\eCommerce\Entity\Country $BusinessCountry
 * @property \DBTech\eCommerce\Entity\Coupon $Coupon
 * @property \XF\Mvc\Entity\AbstractCollection|\DBTech\eCommerce\Entity\License[] $Licenses
 * @property \XF\Entity\PurchaseRequest $PurchaseRequest
 * @property \XF\Entity\PaymentProviderLog $SuccessfulPayment
 */
class Order extends Entity
{
	/**
	 * @return \XF\Phrase
	 */
	public function getOrderStateText(): \XF\Phrase
	{
		switch ($this->order_state)
		{
			case 'awaiting_payment':
				$phrase = \XF::phrase('dbtech_ecommerce_awaiting_payment');
				break;

			case 'reversed':
				$phrase = \XF::phrase('dbtech_ecommerce_reversed_refunded');
				break;

			case 'completed':
				$phrase = \XF::phrase('dbtech_ecommerce_completed');
				break;

			case 'shipped':
				$phrase = \XF::phrase('dbtech_ecommerce_shipped');
				break;

			case 'pending':
				$phrase = \XF::phrase('dbtech_ecommerce_pending');
				break;

			default:
				$phrase = \XF::phrase('dbtech_ecommerce_unknown_order_state');
				break;
		}

		return $phrase;
	}
	
	/**
	 * @return bool
	 */
	public function hasVatInfo(): bool
	{
		$salesTaxSettings = $this->app()->options()->dbtechEcommerceSalesTax;
		
		if (
			!$salesTaxSettings['enabled']
			|| !$salesTaxSettings['enableVat']
			|| !$this->Address
			|| !$this->Address->Country
			|| $this->Address->Country->sales_tax_rate == 0.000
		) {
			return false;
		}
		
		return true;
	}
	
	/**
	 * @return bool
	 */
	public function isCompleted(): bool
	{
		return ($this->order_state == 'completed' || $this->order_state == 'shipped');
	}
	
	/**
	 * @return bool
	 */
	public function isPending(): bool
	{
		return $this->order_state == 'pending';
	}
	
	/**
	 * @return bool
	 */
	public function canPurchase(): bool
	{
		return (
			\XF::visitor()->user_id == $this->user_id
			&& \XF::visitor()->user_state === 'valid'
			&& (
				$this->order_state == 'pending'
				|| $this->order_state == 'awaiting_payment'
			)
		);
	}

	/**
	 * @return bool
	 * @throws \Exception
	 * @throws \Exception
	 */
	public function hasDigitalDownload(): bool
	{
		/** @var OrderItem $item */
		foreach ($this->Items as $item)
		{
			if ($item->Product->hasDownloadFunctionality())
			{
				return true;
			}
		}
		
		return false;
	}

	/**
	 * @return bool
	 * @throws \Exception
	 * @throws \Exception
	 */
	public function hasPhysicalProduct(): bool
	{
		/** @var OrderItem $item */
		foreach ($this->Items as $item)
		{
			if ($item->Product && $item->Product->hasShippingFunctionality())
			{
				return true;
			}
		}
		
		return false;
	}

	/**
	 * @return bool
	 * @throws \Exception
	 */
	public function isAddressRequired(): bool
	{
		if ($this->hasPhysicalProduct())
		{
			return true;
		}

		$options = \XF::options();
		if ($options->dbtechEcommerceAddress['required'])
		{
			if ($options->dbtechEcommerceAddress['onlyPaid'] && $this->order_total == 0.00)
			{
				// Address is required, but only for paid orders, and order total is 0
				return false;
			}

			// Address is required, and order is either not paid OR address always required
			return true;
		}

		if ($options->dbtechEcommerceSalesTax['enabled'] && $this->order_total > 0.00)
		{
			// Sales tax is required and order is paid
			return true;
		}

		// No physical product exists in the order, and address is not required
		return false;
	}

	/**
	 * @return bool
	 * @throws \Exception
	 */
	public function isAccountRequired(): bool
	{
		return \XF::options()->dbtechEcommerceRequireAccount || $this->hasDigitalDownload();
	}
	
	/**
	 * @return bool
	 */
	public function canDownloadInvoice(): bool
	{
		return (\XF::options()->dbtechEcommerceInvoiceActive
			&& $this->has_invoice
			&& $this->address_id
			&& $this->isCompleted()
		);
	}
	
	/**
	 * @return bool
	 */
	public function canSendModeratorActionAlert(): bool
	{
		$visitor = \XF::visitor();
		
		return (
			$visitor->user_id
			&& $visitor->user_id != $this->user_id
		);
	}
	
	/**
	 * @return \XF\Entity\User
	 */
	public function getUser(): \XF\Entity\User
	{
		return $this->getRelation('User') ?: $this->repository('XF:User')->getGuestUser();
	}
	
	/**
	 * @return \XF\Mvc\Entity\ArrayCollection
	 * @throws \Exception
	 */
	public function getItems(): \XF\Mvc\Entity\ArrayCollection
	{
		return \XF::asVisitor($this->User, function (): \XF\Mvc\Entity\ArrayCollection
		{
			/** @var \DBTech\eCommerce\Repository\Order $orderRepo */
			$orderRepo = $this->getOrderRepo();
			
			/** @var \XF\Mvc\Entity\FinderCollection $orderItems */
			$orderItems = $orderRepo->findOrderItemsByOrder($this->order_id)->fetch();
			return $orderItems->filterViewable();
		});
	}
	
	/**
	 * @param \XF\Mvc\Entity\ArrayCollection|null $orderItems
	 */
	public function setItems(?\XF\Mvc\Entity\ArrayCollection $orderItems = null)
	{
		$this->_getterCache['Items'] = $orderItems;
	}
	
	/**
	 * @return array
	 */
	public function getProductList(): array
	{
		$products = [];
		
		/** @var OrderItem $orderItem */
		foreach ($this->Items as $orderItem)
		{
			$products[] = $orderItem->Product->title;
		}
		
		return $products;
	}
	
	/**
	 * @return \XF\Mvc\Entity\ArrayCollection
	 */
	public function getDiscounts(): \XF\Mvc\Entity\ArrayCollection
	{
		/** @var \DBTech\eCommerce\Repository\Discount $discountRepo */
		$discountRepo = $this->getDiscountRepo();
		
		/** @var \XF\Mvc\Entity\FinderCollection $discounts */
		$discounts = $discountRepo->findDiscountsForCheck()->fetch();
		return $discounts->filterViewable();
	}
	
	/**
	 * @param \XF\Mvc\Entity\ArrayCollection|null $discounts
	 */
	public function setDiscounts(?\XF\Mvc\Entity\ArrayCollection $discounts = null)
	{
		$this->_getterCache['Discounts'] = $discounts;
	}

	/**
	 * @param \DBTech\eCommerce\Entity\Product $product
	 * @param \DBTech\eCommerce\Entity\ProductCost|null $cost
	 * @param \DBTech\eCommerce\Entity\License|null $license
	 * @param \DBTech\eCommerce\Entity\License|null $parentLicense
	 * @param int $quantity
	 *
	 * @return \DBTech\eCommerce\Entity\OrderItem
	 * @throws \InvalidArgumentException
	 * @throws \Exception
	 */
	public function getNewOrderItem(
		Product $product,
		?ProductCost $cost = null,
		?License $license = null,
		?License $parentLicense = null,
		int $quantity = 1
	): OrderItem {
		/** @var OrderItem $orderItem */
		$orderItem = $this->_em->create('DBTech\eCommerce:OrderItem');
		$orderItem->order_id = $this->order_id;
		$orderItem->user_id = $this->user_id;
		$orderItem->item_type = 'new';
		$orderItem->quantity = $quantity;
		
		$orderItem->product_id = $product->product_id;
		$orderItem->hydrateRelation('Product', $product);
		
		if ($cost)
		{
			$orderItem->product_cost_id = $cost->product_cost_id;
			$orderItem->hydrateRelation('Cost', $cost);
		}
		
		if ($license)
		{
			$orderItem->license_id = $license->license_id;
			$orderItem->item_type = 'renew';
			$orderItem->hydrateRelation('License', $license);
		}
		
		if ($parentLicense)
		{
			$orderItem->parent_license_id = $parentLicense->license_id;
			$orderItem->hydrateRelation('ParentLicense', $parentLicense);
		}
		
		if ($product->hasShippingFunctionality() && $this->ShippingAddress)
		{
			/** @var ShippingCombination[]|\XF\Mvc\Entity\ArrayCollection $shippingCombinations */
			$shippingCombinations = $this->ShippingAddress->ApplicableShippingMethods;
			
			$applicableShippingMethods = $shippingCombinations->filter(
				function (ShippingCombination $combination) use ($product): ?ShippingCombination
				{
					if (!$combination->isApplicableToProduct($product))
					{
						return null;
					}
				
					return $combination;
				}
			);
			
			if (count($applicableShippingMethods) == 1)
			{
				$orderItem->shipping_method_id = $applicableShippingMethods->first()->shipping_method_id;
			}
		}
		
		return $orderItem;
	}

	/**
	 * @return float
	 * @throws \Exception
	 */
	public function getSubTotal(): float
	{
		if ($this->isPending())
		{
			$cost = 0.00;
			
			/** @var OrderItem $orderItem */
			foreach ($this->Items as $orderItem)
			{
				$cost += $orderItem->getBasePrice();
			}
			
			return sprintf("%.2f", $cost);
		}

		return $this->sub_total_;
	}

	/**
	 * @return float
	 * @throws \Exception
	 */
	public function getSaleDiscounts(): float
	{
		if ($this->isPending())
		{
			$discount = 0.00;
			
			/** @var OrderItem $orderItem */
			foreach ($this->Items as $orderItem)
			{
				$discount += $orderItem->getSaleDiscount();
			}
			
			return sprintf("%.2f", $discount);
		}
		
		return $this->sale_discounts_;
	}
	
	/**
	 * @return float
	 * @throws \LogicException
	 * @throws \Exception
	 */
	public function getCouponDiscounts(): float
	{
		if ($this->isPending())
		{
			$discount = 0.00;
			
			/** @var OrderItem $orderItem */
			foreach ($this->Items as $orderItem)
			{
				$discount += $orderItem->getCouponDiscount();
			}
			
			return sprintf("%.2f", $discount);
		}
		
		return $this->coupon_discounts_;
	}

	/**
	 * @return float
	 * @throws \Exception
	 */
	public function getAutomaticDiscounts(): float
	{
		if ($this->isPending())
		{
			$discountableTotal = 0.00;
			
			/** @var OrderItem $orderItem */
			foreach ($this->Items as $orderItem)
			{
				if ($orderItem->Product->is_discountable)
				{
					$discountableTotal += $orderItem->getBasePrice();
				}
			}
			
			/** @var Discount[] $discounts */
			$discounts = $this->Discounts;
			
			$discount = 0.00;
			foreach ($discounts as $possibleDiscount)
			{
				if ($discountableTotal >= $possibleDiscount->discount_threshold)
				{
					$discount = $possibleDiscount->discount_percent;
					break;
				}
			}
			
			return sprintf("%.2f", ($discountableTotal * ($discount / 100)));
		}
		
		return $this->automatic_discounts_;
	}
	
	/**
	 * @return float
	 * @throws \Exception
	 */
	public function getShippingCost(): float
	{
		if ($this->isPending())
		{
			$shippingCost = 0.00;
			
			/** @var OrderItem $orderItem */
			foreach ($this->Items as $orderItem)
			{
				$shippingCost += $orderItem->getShippingCost();
			}
			
			return sprintf("%.2f", $shippingCost);
		}
		
		return $this->shipping_cost_;
	}
	
	/**
	 * @return float|mixed
	 * @throws \Exception
	 */
	public function getSalesTax(): float
	{
		if ($this->isPending())
		{
			if (!$this->app()->options()->dbtechEcommerceSalesTax['enabled'])
			{
				return 0.00;
			}
			
			$salesTax = 0.00;
			
			/** @var OrderItem $orderItem */
			foreach ($this->Items as $orderItem)
			{
				$salesTax += $orderItem->getSalesTax();
			}
			
			return sprintf("%.2f", $salesTax);
		}
		
		return $this->sales_tax_;
	}

	/**
	 * @param string $type
	 *
	 * @return float
	 * @throws \Exception
	 */
	public function getSalesTaxRate(string $type): float
	{
		$salesTaxSettings = $this->app()->options()->dbtechEcommerceSalesTax;

		if (!$salesTaxSettings['enabled'] || !in_array($type, ['digital', 'physical']))
		{
			return 0.00;
		}

		if ($salesTaxSettings['enableVat']
			&& $this->Address
			&& $this->Address->sales_tax_id
			&& $this->Address->address_state == 'verified'
			&& $this->Address->country_code != $this->app()->options()->dbtechEcommerceAddressCountry
		) {
			// A valid VAT ID was found for this order, and it does not belong to the seller's country
			return 0.00;
		}

		if ($salesTaxSettings[$type] == 'buyer')
		{
			// Calculate tax based on buyer's country
			if (!$this->Address
				|| !$this->Address->Country
				|| $this->Address->Country->sales_tax_rate == 0.000
			) {
				return 0.00;
			}

			return $this->Address->Country->getSalesTaxRate();
		}
		else
		{
			if (!$this->BusinessCountry || $this->BusinessCountry->sales_tax_rate == 0.000)
			{
				return 0.00;
			}

			return $this->BusinessCountry->getSalesTaxRate();
		}
	}
	
	/**
	 * @return float
	 */
	public function getDiscountTotal()
	{
		return ($this->coupon_discounts + $this->automatic_discounts + $this->store_credit_amount);
	}
	
	/**
	 * @param bool $includeCredit
	 *
	 * @return float
	 * @throws \Exception
	 */
	public function getTaxableOrderTotal(bool $includeCredit = false)
	{
		if ($this->isPending())
		{
			$orderTotal = 0.00;
			
			/** @var OrderItem $orderItem */
			foreach ($this->Items as $orderItem)
			{
				$orderTotal += $orderItem->getTaxablePrice();
			}
			
			$orderTotal -= $this->getAutomaticDiscounts();
			
			if ($includeCredit)
			{
				$orderTotal -= $this->store_credit_amount;
			}
			
			return sprintf("%.2f", $orderTotal);
		}
		
		if ($includeCredit)
		{
			return ($this->taxable_order_total_ - $this->store_credit_amount);
		}
		else
		{
			return $this->taxable_order_total_;
		}
	}
	
	/**
	 * @param bool $includeCredit
	 *
	 * @return float|int
	 * @throws \LogicException
	 * @throws \Exception
	 */
	public function getOrderTotal(bool $includeCredit = true)
	{
		$orderTotal = $this->getTaxableOrderTotal();
		$orderTotal += $this->getSalesTax();
		
		if ($includeCredit)
		{
			$orderTotal -= $this->store_credit_amount;
		}
		
		return max(0.00, sprintf("%.2f", $orderTotal));
	}
	
	/**
	 * @return bool
	 * @throws \Exception
	 */
	public function validateCoupon(): bool
	{
		if (!$this->Coupon)
		{
			return true;
		}
		
		// Shorthand
		$coupon = $this->Coupon;
		$numItems = count($this->Items);
		$subTotal = $this->getSubTotal();
		
		if (
			!$coupon->canUse()
			|| $numItems < $coupon->minimum_products
			|| ($coupon->maximum_products && $numItems > $coupon->maximum_products)
			|| $subTotal < $coupon->minimum_cart_value
			|| ($coupon->maximum_cart_value > 0.00 && $subTotal > $coupon->maximum_cart_value)
		) {
			$this->removeCoupon();
			
			return false;
		}
		
		/** @var OrderItem $orderItem */
		foreach ($this->Items as $orderItem)
		{
			if (
				$this->Coupon->isApplicable($orderItem->Product)
				&& $orderItem->coupon_id != $this->Coupon->coupon_id
			) {
				$orderItem->fastUpdate('coupon_id', $this->Coupon->coupon_id);
			}
		}
		
		return true;
	}
	
	/**
	 * @param Coupon $coupon
	 *
	 * @return bool
	 * @throws \LogicException
	 * @throws \Exception
	 */
	public function applyCoupon(Coupon $coupon): bool
	{
		// Shorthand
		$numItems = count($this->Items);
		$subTotal = $this->getSubTotal();
		
		if (
			$coupon->canUse()
			&& $numItems >= $coupon->minimum_products
			&& (!$coupon->maximum_products || $numItems <= $coupon->maximum_products)
			&& $subTotal >= $coupon->minimum_cart_value
			&& ($coupon->maximum_cart_value == 0.00 || $subTotal <= $coupon->maximum_cart_value)
		) {
			$this->fastUpdate('coupon_id', $coupon->coupon_id);
			
			/** @var OrderItem $orderItem */
			foreach ($this->Items as $orderItem)
			{
				if ($coupon->isApplicable($orderItem->Product))
				{
					$orderItem->fastUpdate('coupon_id', $coupon->coupon_id);
				}
				else
				{
					$orderItem->removeCoupon();
				}
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
		
		/** @var OrderItem $orderItem */
		foreach ($this->Items as $orderItem)
		{
			if ($orderItem->coupon_id)
			{
				$orderItem->removeCoupon();
			}
		}
		
		return true;
	}
	
	/**
	 * @param $ip
	 *
	 * @return bool
	 */
	protected function verifyIpAddress(&$ip): bool
	{
		$ip = \XF\Util\Ip::convertIpStringToBinary($ip);
		if ($ip === false)
		{
			// this will fail later
			$ip = '';
		}
		
		return true;
	}
	
	/**
	 *
	 */
	protected function _preSave()
	{
		if (
			$this->isUpdate()
			&& $this->isChanged('order_state')
			&& !in_array($this->order_state, ['completed', 'reversed'])
		) {
			$this->order_date = \XF::$time;
		}

		$completeChange = $this->isStateChanged('order_state', 'completed');
		if ($completeChange == 'enter')
		{
			$this->completed_date = \XF::$time;
		}

		$reverseChange = $this->isStateChanged('order_state', 'reversed');
		if ($reverseChange == 'enter')
		{
			$this->reversed_date = \XF::$time;
		}
	}

	/**
	 * @throws \XF\Db\Exception
	 */
	protected function _postSave()
	{
		$purchaseChange = $this->isStateChanged('order_state', 'pending');
		$paymentChange = $this->isStateChanged('order_state', 'awaiting_payment');
		if ($purchaseChange == 'leave')
		{
			if ($this->user_id)
			{
				$this->db()
					->query('
					UPDATE xf_user
					SET dbtech_ecommerce_cart_items = 0
					WHERE user_id = ?
				', $this->user_id)
				;
			}
			else
			{
				$this->app()->response()->setCookie('dbtechEcommerceCartItems', 0, 86400 * 365);
			}
		}

		if ($paymentChange == 'enter')
		{
			if ($this->store_credit_amount)
			{
				$this->db()->query('
					UPDATE xf_user
					SET dbtech_ecommerce_store_credit = GREATEST(0, CAST(dbtech_ecommerce_store_credit AS SIGNED) - ?)
					WHERE user_id = ?
				', [
					$this->store_credit_amount,
					$this->user_id
				]);
			}
		}
		
		if ($this->isUpdate() && $this->isChanged(['order_state', 'address_id']))
		{
			$this->_deleteInvoice();
			$this->_deleteShippingLabel();
		}
	}
	
	/**
	 * @throws \XF\Db\Exception
	 * @throws \XF\PrintableException
	 */
	protected function _postDelete()
	{
		if ($this->order_state == 'pending')
		{
			$this->db()->query('
				UPDATE xf_user
				SET dbtech_ecommerce_cart_items = 0
				WHERE user_id = ?
			', $this->user_id);
		}
		elseif ($this->order_state == 'awaiting_payment')
		{
			if ($this->store_credit_amount)
			{
				$this->db()->query('
					UPDATE xf_user
					SET dbtech_ecommerce_store_credit = dbtech_ecommerce_store_credit + ?
					WHERE user_id = ?
				', [
					$this->store_credit_amount,
					$this->user_id
				]);
			}
		}
		
		if ($this->Address && $this->Address->orderRemoved($this))
		{
			$this->Address->save(true, false);
		}
		
		if ($this->address_id != $this->shipping_address_id)
		{
			if ($this->ShippingAddress && $this->ShippingAddress->orderRemoved($this))
			{
				$this->ShippingAddress->save(true, false);
			}
		}
		
		$this->app()->jobManager()->cancelUniqueJob('dbtEcomOrderReminder' . $this->order_id);
		
		$this->_deleteInvoice();
		$this->_deleteShippingLabel();
	}
	
	/**
	 *
	 */
	protected function _deleteInvoice()
	{
		$fileName = sprintf('INV%d.pdf', $this->order_id);
		\XF\Util\File::deleteFromAbstractedPath('internal-data://dbtechEcommerce/invoices/'. $fileName);
	}
	
	/**
	 *
	 */
	protected function _deleteShippingLabel()
	{
		$fileName = sprintf('ORD%d.pdf', $this->order_id);
		\XF\Util\File::deleteFromAbstractedPath('internal-data://dbtechEcommerce/invoices/'. $fileName);
	}
	
	/**
	 * @param \XF\Mvc\Entity\Structure $structure
	 *
	 * @return \XF\Mvc\Entity\Structure
	 */
	public static function getStructure(Structure $structure): Structure
	{
		$structure->table = 'xf_dbtech_ecommerce_order';
		$structure->shortName = 'DBTech\eCommerce:Order';
		$structure->primaryKey = 'order_id';
		$structure->columns = [
			'order_id' => ['type' => self::UINT, 'autoIncrement' => true, 'nullable' => true],
			'user_id' =>  ['type' => self::UINT, 'default' => 0],
			'ip_address' => ['type' => self::BINARY, 'maxLength' => 16, 'required' => true],
			'order_date' => ['type' => self::UINT, 'default' => \XF::$time],
			'completed_date' => ['type' => self::UINT, 'default' => 0],
			'reversed_date' => ['type' => self::UINT, 'default' => 0],
			'purchase_request_key' => ['type' => self::STR, 'maxLength' => 32, 'nullable' => true],
			'order_state' => ['type' => self::STR, 'default' => 'pending',
								'allowedValues' => [
									'pending', 'awaiting_payment', 'completed', 'shipped', 'reversed'
								]
			],
			'address_id' =>  ['type' => self::UINT, 'default' => 0],
			'shipping_address_id' =>  ['type' => self::UINT, 'default' => 0],
			'store_credit_amount' =>  ['type' => self::UINT, 'default' => 0],
			'coupon_id' =>  ['type' => self::UINT, 'default' => 0],
			'sub_total' => ['type' => self::FLOAT, 'min' => 0, 'default' => 0.00],
			'sale_discounts' => ['type' => self::FLOAT, 'min' => 0, 'default' => 0.00],
			'coupon_discounts' => ['type' => self::FLOAT, 'min' => 0, 'default' => 0.00],
			'automatic_discounts' => ['type' => self::FLOAT, 'min' => 0, 'default' => 0.00],
			'shipping_cost' => ['type' => self::FLOAT, 'min' => 0, 'default' => 0.00],
			'sales_tax' => ['type' => self::FLOAT, 'min' => 0, 'default' => 0.00],
			'taxable_order_total' => ['type' => self::FLOAT, 'min' => 0, 'default' => 0.00],
			'currency' => ['type' => self::STR, 'maxLength' => 3,
								'default' => \XF::options()->dbtechEcommerceCurrency
			],
			'cost_amount' => ['type' => self::FLOAT, 'min' => 0, 'default' => 0],
			'has_invoice' =>  ['type' => self::BOOL, 'default' => 1],
			'sent_reminder' =>  ['type' => self::BOOL, 'default' => 0],
			'extra_data' => ['type' => self::JSON_ARRAY, 'default' => []],
		];
		$structure->behaviors = [];
		$structure->getters = [
			'sub_total' => true,
			'sale_discounts' => true,
			'coupon_discounts' => true,
			'automatic_discounts' => true,
			'shipping_cost' => true,
			'discount_total' => true,
			'sales_tax' => true,
			'taxable_order_total' => true,
			'order_total' => true,
			'User' => true,
			'Items' => true,
			'Discounts' => true
		];
		$structure->relations = [
			'User' => [
				'entity' => 'XF:User',
				'type' => self::TO_ONE,
				'conditions' => 'user_id',
				'primary' => true
			],
			'Address' => [
				'entity' => 'DBTech\eCommerce:Address',
				'type' => self::TO_ONE,
				'conditions' => 'address_id',
				'primary' => true
			],
			'ShippingAddress' => [
				'entity' => 'DBTech\eCommerce:Address',
				'type' => self::TO_ONE,
				'conditions' => 'shipping_address_id',
				'primary' => true
			],
			'BusinessCountry' => [
				'entity' => 'DBTech\eCommerce:Country',
				'type' => self::TO_ONE,
				'conditions' => [
					['country_code', '=', \XF::options()->dbtechEcommerceAddressCountry]
				],
				'primary' => true
			],
			'Coupon' => [
				'entity' => 'DBTech\eCommerce:Coupon',
				'type' => self::TO_ONE,
				'conditions' => 'coupon_id',
				'primary' => true
			],
			'Licenses' => [
				'entity' => 'DBTech\eCommerce:License',
				'type' => self::TO_MANY,
				'conditions' => 'order_id',
				'cascadeDelete' => true
			],
			'PurchaseRequest' => [
				'entity' => 'XF:PurchaseRequest',
				'type' => self::TO_ONE,
				'conditions' => [
					['request_key', '=', '$purchase_request_key']
				]
			],
			'SuccessfulPayment' => [
				'entity' => 'XF:PaymentProviderLog',
				'type' => self::TO_ONE,
				'conditions' => [
					['purchase_request_key', '=', '$purchase_request_key'],
					['log_type', '=', 'payment'],
					['log_message', '=', 'Payment received, order processed.']
				]
			]
		];
		
		$structure->withAliases = [
			'full' => [
				'User',
				function (array $withParams): array
				{
					if (!empty($withParams['address']))
					{
						return ['Address'];
					}

					return [];
				}
			]
		];
		
		$structure->options = [];
		$structure->defaultWith = [
			'Coupon'
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
	
	/**
	 * @return \DBTech\eCommerce\Repository\Discount|\XF\Mvc\Entity\Repository
	 */
	protected function getDiscountRepo()
	{
		return $this->repository('DBTech\eCommerce:Discount');
	}
}