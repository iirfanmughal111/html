<?php

namespace DBTech\eCommerce\Service\Order;

use DBTech\eCommerce\Entity\OrderItem;

/**
 * Class Complete
 *
 * @package DBTech\eCommerce\Service\Order
 */
class Complete extends \XF\Service\AbstractService
{
	/** @var \XF\Entity\User */
	protected $user;
	
	/** @var string */
	protected $purchaseRequestKey;
	
	/** @var string */
	protected $transactionId;

	/** @var \DBTech\eCommerce\Entity\Order */
	protected $order;
	
	/** @var bool */
	protected $ignoreUnpurchasable = false;
	
	/** @var array */
	protected $extraData = [];
	
	
	/**
	 * Complete constructor.
	 *
	 * @param \XF\App $app
	 * @param \DBTech\eCommerce\Entity\Order $order
	 * @param \XF\Entity\User $user
	 */
	public function __construct(\XF\App $app, \DBTech\eCommerce\Entity\Order $order, \XF\Entity\User $user)
	{
		parent::__construct($app);

		$this->user = $user;
		$this->setOrder($order);
	}
	
	/**
	 * @return \XF\Entity\User
	 */
	public function getUser(): \XF\Entity\User
	{
		return $this->user;
	}

	/**
	 * @param string $purchaseRequestKey
	 *
	 * @return $this
	 */
	public function setPurchaseRequestKey(string $purchaseRequestKey): Complete
	{
		$this->purchaseRequestKey = $purchaseRequestKey;

		return $this;
	}

	/**
	 * @param string $transactionId
	 *
	 * @return $this
	 */
	public function setTransactionId(string $transactionId): Complete
	{
		$this->transactionId = $transactionId;

		return $this;
	}

	/**
	 * @param \DBTech\eCommerce\Entity\Order $order
	 *
	 * @return $this
	 */
	protected function setOrder(\DBTech\eCommerce\Entity\Order $order): Complete
	{
		$this->order = $order;

		return $this;
	}
	
	/**
	 * @return \DBTech\eCommerce\Entity\Order
	 */
	public function getOrder(): \DBTech\eCommerce\Entity\Order
	{
		return $this->order;
	}

	/**
	 * @param bool $ignoreUnpurchasable
	 *
	 * @return $this
	 */
	public function ignoreUnpurchasable(bool $ignoreUnpurchasable): Complete
	{
		$this->ignoreUnpurchasable = $ignoreUnpurchasable;

		return $this;
	}

	/**
	 * @param array $extraData
	 *
	 * @return $this
	 */
	public function setExtraData(array $extraData): Complete
	{
		$this->extraData = $extraData;

		return $this;
	}
	
	/**
	 * @param null $errors
	 *
	 * @return bool
	 * @throws \LogicException
	 * @throws \Exception
	 * @throws \XF\PrintableException
	 */
	public function initiate(&$errors = null): bool
	{
		$order = $this->order;
		$user = $this->user;
		
		if (!$this->ignoreUnpurchasable && !$order->canPurchase())
		{
			$errors = \XF::phrase('this_item_cannot_be_purchased_at_moment');
			return false;
		}
		
		if (
			$order->order_state == 'awaiting_payment'
			|| $order->order_state == 'completed'
			|| $order->order_state == 'shipped'
			|| $order->order_state == 'reversed'
		) {
			// This code has already ran
			return true;
		}
		
		if ($order->Address && $order->Address->orderAdded($order))
		{
			$order->Address->save();
		}
		
		if ($order->address_id != $order->shipping_address_id)
		{
			if ($order->ShippingAddress && $order->ShippingAddress->orderAdded($order))
			{
				$order->ShippingAddress->save();
			}
		}
		
		/** @var \DBTech\eCommerce\Entity\OrderItem $orderItem */
		foreach ($order->Items as $orderItem)
		{
			/** @var \XF\CustomField\Set $fieldSet */
			$fieldSet = $orderItem->product_fields;
			$fieldDefinition = $fieldSet->getDefinitionSet()
				->filterOnly($orderItem->Product->field_cache);
			
			/** @var \XF\CustomField\Definition $definition */
			foreach ($fieldDefinition as $fieldId => $definition)
			{
				$value = $fieldSet->{$definition->field_id};
				if ($definition->isRequired() && !$definition->hasValue($value))
				{
					$errors = \XF::phrase('please_enter_value_for_all_required_fields');
					return false;
				}
				
				if (!$definition->isValid($value, $errors, $value))
				{
					return false;
				}
			}
		}
		
		$db = $this->db();
		$db->beginTransaction();
		
		$options = \XF::options();
		
		$extraData = [
			'sub_total' => $order->getSubTotal(),
			'sale_total' => $order->getSaleDiscounts(),
			'coupon_discounts' => $order->getCouponDiscounts(),
			'automatic_discounts' => $order->getAutomaticDiscounts(),
			'shipping_cost' => $order->getShippingCost(),
			'sales_tax' => $order->getSalesTax(),
			'taxable_order_total' => $order->getTaxableOrderTotal(),
			'cost_currency' => $options->dbtechEcommerceCurrency,
		];
		
		$order->cost_amount = $order->getOrderTotal();
		$order->sub_total = $extraData['sub_total'];
		$order->sale_discounts = $extraData['sale_total'];
		$order->coupon_discounts = $extraData['coupon_discounts'];
		$order->automatic_discounts = $extraData['automatic_discounts'];
		$order->shipping_cost = $extraData['shipping_cost'];
		$order->sales_tax = $extraData['sales_tax'];
		$order->taxable_order_total = $extraData['taxable_order_total'];
		$order->currency = $extraData['cost_currency'];
		
		$order->extra_data = $extraData;
		
		/** @var \DBTech\eCommerce\Entity\OrderItem $orderItem */
		foreach ($order->Items as $orderItem)
		{
			$orderItemExtraData = array_merge($orderItem->extra_data, [
				'base_price' => $orderItem->getBasePrice(),
				'sale_discount' => $orderItem->getSaleDiscount(),
				'coupon_discount' => $orderItem->getCouponDiscount(),
				'sales_tax' => $orderItem->getSalesTax(),
				'discounted_price' => $orderItem->getDiscountedPrice(),
				'taxable_price' => $orderItem->getTaxablePrice(),
				'price' => $orderItem->getPrice(),
			]);
			
			$orderItem->base_price = $orderItemExtraData['base_price'];
			$orderItem->sale_discount = $orderItemExtraData['sale_discount'];
			$orderItem->coupon_discount = $orderItemExtraData['coupon_discount'];
//			$orderItem->shipping_cost = $orderItem->getShippingCost(); // This calculates total cost, not individual item cost
			$orderItem->sales_tax = $orderItemExtraData['sales_tax'];
			$orderItem->taxable_price = $orderItemExtraData['taxable_price'];
			$orderItem->price = $orderItemExtraData['price'];
			$orderItem->currency = $order->currency;
			
			$orderItem->extra_data = $orderItemExtraData;
			
			if ($orderItem->Product->hasLicenseFunctionality() && !$orderItem->License)
			{
				$success = \XF::asVisitor($user, function () use ($order, $orderItem, $errors): bool
				{
					/** @var \DBTech\eCommerce\Service\License\Create $creator */
					$creator = \XF::app()->service('DBTech\eCommerce:License\Create', $orderItem->Product);
					$creator->getLicense()->bulkSet([
						'license_state' => 'awaiting_payment',
						'parent_license_id' => $orderItem->parent_license_id
					]);
					$creator->setExpiryDateFromCost($orderItem->Cost);

					return $creator->validate($errors);
				});

				if (!$success)
				{
					$db->rollback();
					return false;
				}
			}
			
			$orderItem->save(true, false);
		}
		
		$order->order_state = 'awaiting_payment';
		if (!$order->save(true, false))
		{
			$db->rollback();
			return false;
		}
		
		$db->commit();
		
		return true;
	}
	
	/**
	 * @return bool
	 * @throws \LogicException
	 * @throws \Exception
	 * @throws \XF\PrintableException
	 */
	public function complete(): bool
	{
		$order = $this->order;
		$user = $this->user;
		
		if (!$this->ignoreUnpurchasable && !$order->canPurchase())
		{
			return false;
		}
		
		if ($order->isCompleted())
		{
			// This code has already ran
			return true;
		}
		
		if ($this->purchaseRequestKey)
		{
			$requestKey = $this->purchaseRequestKey;
			if (strlen($requestKey) > 32)
			{
				$requestKey = substr($requestKey, 0, 29) . '...';
			}
			
			$order->purchase_request_key = $requestKey;
		}
		
		$db = $this->db();
		$db->beginTransaction();
		
		/** @var \DBTech\eCommerce\Repository\ProductWatch $productWatchRepo */
		$productWatchRepo = $this->repository('DBTech\eCommerce:ProductWatch');
		
		/** @var \XF\Repository\Ip $ipRepo */
		$ipRepo = $this->repository('XF:Ip');
		
		/** @var \DBTech\eCommerce\Entity\OrderItem $orderItem */
		foreach ($order->Items as $orderItem)
		{
			$purchaseLogStatus = $orderItem->item_type;
			
			if ($orderItem->Product->hasLicenseFunctionality())
			{
				if ($orderItem->License)
				{
					/** @var \DBTech\eCommerce\Entity\License $license */
					$license = $orderItem->License;
					
					if ($license->license_state != 'deleted')
					{
						$license->expiry_date = $orderItem->Cost->getNewExpiryDate($license);
					}
					
					$license->license_state = $license->Product->getNewContentState($license);
					if ($order->purchase_request_key)
					{
						$license->purchase_request_key = $order->purchase_request_key;
					}
					$license->save(true, false);

					$this->app->fire('dbtech_ecommerce_license_renew', [$license, $order, $orderItem]);
				}
				else
				{
					/** @var \DBTech\eCommerce\Entity\License $license */
					$license = \XF::asVisitor($user, function () use ($order, $orderItem)
					{
						/** @var \DBTech\eCommerce\Service\License\Create $creator */
						$creator = \XF::app()->service('DBTech\eCommerce:License\Create', $orderItem->Product);
						$creator->getLicense()->bulkSet([
							'order_id' => $order->order_id,
							'purchase_request_key' => $order->purchase_request_key ?: '',
							'parent_license_id' => $orderItem->parent_license_id
						]);
						$creator->setExpiryDateFromCost($orderItem->Cost);
						
						if ($creator->validate())
						{
							$license = $creator->save();
							
							$creator->sendNotifications();
							
							return $license;
						}
						
						return null;
					});
					
					if ($license)
					{
						$orderItem->fastUpdate('license_id', $license->license_id);
						
						$orderItem->hydrateRelation('License', $license);

						$this->app->fire('dbtech_ecommerce_license_purchase', [$license, $order, $orderItem]);
					}
				}
			}
			elseif ($orderItem->Product->hasStockFunctionality())
			{
				// Reduce stock by one as we purchased this
				$orderItem->Cost->reduceStock($orderItem->quantity);
			}
			
			/** @var \DBTech\eCommerce\Entity\Product $product */
			$product = $orderItem->Product;

			$product->purchaseAdded();
			$product->save();

			$handler = $product->getHandler();
			if ($handler)
			{
				/** @noinspection PhpExpressionResultUnusedInspection */
				$handler->orderComplete($product, $orderItem);
			}
			
			/** @var \XF\CustomField\Set $fieldSet */
			$fieldSet = $orderItem->product_fields;
			$fieldDefinition = $fieldSet->getDefinitionSet()
				->filterOnly($product->field_cache);
			
			$productFieldsShown = array_keys($fieldDefinition->getFieldDefinitions());
			
			if (
				$product->thread_node_id
				&& $product->ThreadForum
				&& $productFieldsShown
			) {
				$creator = $this->setupThreadCreation($orderItem, $product->ThreadForum);
				if ($creator->validate())
				{
					/** @var \XF\Entity\Thread $thread */
					$thread = $creator->save();
					
					if ($product->hasLicenseFunctionality() && $orderItem->License)
					{
						$orderItem->License->fastUpdate('discussion_thread_id', $thread->thread_id);
					}
					
					$creator->sendNotifications();
					
					$this->afterThreadCreated($thread);

					$orderItem->fastUpdate('discussion_thread_id', $thread->thread_id);
					$orderItem->hydrateRelation('Discussion', $thread);
				}
			}
			
			if ($order->order_state === 'awaiting_payment' && $orderItem->coupon_id)
			{
				/** @var \DBTech\eCommerce\Entity\CouponLog $couponLog */
				$couponLog = $this->em()->create('DBTech\eCommerce:CouponLog');
				$couponLog->order_id = $order->order_id;
				$couponLog->order_item_id = $orderItem->order_item_id;
				$couponLog->product_id = $orderItem->product_id;
				$couponLog->coupon_id = $orderItem->coupon_id;
				$couponLog->coupon_discounts = $orderItem->coupon_discount;
				$couponLog->currency = $order->currency;
				$couponLog->user_id = $user->user_id;
				$couponLog->log_details = array_merge($orderItem->extra_data, $this->extraData);
				$couponLog->save(true, false);
				
				$ipEnt = $ipRepo->logIp($user->user_id, $order->ip_address, 'dbtech_ecommerce_coupon', $couponLog->coupon_log_id, 'purchase');
				if ($ipEnt)
				{
					$couponLog->fastUpdate('ip_id', $ipEnt->ip_id);
				}
			}
			
			/** @var \DBTech\eCommerce\Entity\PurchaseLog $purchaseLog */
			$purchaseLog = $this->em()->create('DBTech\eCommerce:PurchaseLog');
			$purchaseLog->order_id = $order->order_id;
			$purchaseLog->order_item_id = $orderItem->order_item_id;
			$purchaseLog->product_id = $orderItem->product_id;
			$purchaseLog->license_id = $orderItem->license_id;
			$purchaseLog->quantity = $orderItem->quantity;
			$purchaseLog->user_id = $user->user_id;
			$purchaseLog->cost_amount = $orderItem->price;
			$purchaseLog->currency = $order->currency;
			$purchaseLog->log_type = $purchaseLogStatus;
			$purchaseLog->log_details = array_merge($orderItem->extra_data, $this->extraData);
			$purchaseLog->save(true, false);
			
			$ipEnt = $ipRepo->logIp($user->user_id, $order->ip_address, 'dbtech_ecommerce_purchase', $purchaseLog->purchase_log_id, 'purchase');
			if ($ipEnt)
			{
				$purchaseLog->fastUpdate('ip_id', $ipEnt->ip_id);
			}
			
			if ($user->user_id)
			{
				if ($product->extra_group_ids)
				{
					/** @var \XF\Service\User\UserGroupChange $userGroupChange */
					$userGroupChange = $this->service('XF:User\UserGroupChange');
					$userGroupChange->addUserGroupChange(
						$user->user_id,
						'dbtechEcommerce-' . $order->order_id . '-' . $orderItem->product_id,
						$product->extra_group_ids
					);
				}
				
				if ($user->user_id != $product->user_id)
				{
					$productWatchRepo->autoWatchProduct($product, $user);
				}
			}
		}
		
		$childOrderItems = $order->Items->filter(function (OrderItem $orderItem) use ($order): ?OrderItem
		{
			if (!$orderItem->parent_order_item_id)
			{
				return null;
			}
			
			if (!$orderItem->license_id || !$orderItem->License)
			{
				return null;
			}
			
			if (!$order->Items->offsetExists($orderItem->parent_order_item_id))
			{
				return null;
			}
			
			/** @var OrderItem $parentItem */
			$parentItem = $order->Items[$orderItem->parent_order_item_id];
			if (!$parentItem->license_id)
			{
				return null;
			}
			
			return $orderItem;
		});
		foreach ($childOrderItems as $orderItem)
		{
			/** @var OrderItem $parentItem */
			$parentItem = $order->Items[$orderItem->parent_order_item_id];
			
			$orderItem->License->fastUpdate('parent_license_id', $parentItem->license_id);
			$orderItem->License->hydrateRelation('Parent', $parentItem->License);
		}
		
		if ($order->order_state === 'awaiting_payment')
		{
			if ($order->store_credit_amount)
			{
				/** @var \DBTech\eCommerce\Entity\StoreCreditLog $storeCreditLog */
				$storeCreditLog = $this->em()->create('DBTech\eCommerce:StoreCreditLog');
				$storeCreditLog->order_id = $order->order_id;
				$storeCreditLog->store_credit_amount = ($order->store_credit_amount * -1);
				$storeCreditLog->user_id = $user->user_id;
				$storeCreditLog->log_details = array_merge($order->extra_data, $this->extraData);
				$storeCreditLog->save(true, false);
				
				$ipEnt = $ipRepo->logIp($user->user_id, $order->ip_address, 'dbtech_ecommerce_credit', $storeCreditLog->store_credit_log_id, 'purchase');
				if ($ipEnt)
				{
					$storeCreditLog->fastUpdate('ip_id', $ipEnt->ip_id);
				}
			}
			
			if ($order->coupon_id && $order->Coupon->remaining_uses > 0)
			{
				$this->db()->query('
					UPDATE xf_dbtech_ecommerce_coupon
					SET remaining_uses = IF(remaining_uses > 0, remaining_uses - 1, 0)
					WHERE coupon_id = ?
				', $order->coupon_id);
			}
		}
		
		$order->order_state = 'completed';
		if (!$order->save(true, false))
		{
			$db->rollback();
			return false;
		}
		
		/** @var \XF\Repository\UserAlert $alertRepo */
		$alertRepo = $this->repository('XF:UserAlert');
		$alertRepo->fastDeleteAlertsFromUser($user->user_id, 'dbtech_ecommerce_license', $user->user_id, 'expiry');
		
		$this->app->jobManager()->cancelUniqueJob('dbtEcomOrderReminder' . $order->order_id);
		
		$this->afterComplete();

		$db->commit();

		return true;
	}
	
	/**
	 *
	 */
	protected function afterComplete()
	{
		$order = $this->order;

		if ($order->user_id)
		{
			$productIds = [];

			/** @var \DBTech\eCommerce\Entity\OrderItem $orderItem */
			foreach ($order->Items as $orderItem)
			{
				if ($orderItem->Product->welcome_email)
				{
					$productIds[] = $orderItem->Product->product_id;
				}
			}

			if ($productIds)
			{
				foreach ($productIds as $productId)
				{
					$productIds = \array_unique($productIds);
					$this->app->jobManager()->enqueue(
						'DBTech\eCommerce:ProductWelcomeEmail',
						[
							'userIds' => [$order->user_id],
							'product_id' => $productId
						]
					);
				}
			}
		}
	}
	
	/**
	 * @param \DBTech\eCommerce\Entity\OrderItem $orderItem
	 * @param \XF\Entity\Forum $forum
	 *
	 * @return \XF\Service\Thread\Creator
	 * @throws \Exception
	 */
	protected function setupThreadCreation(OrderItem $orderItem, \XF\Entity\Forum $forum): \XF\Service\Thread\Creator
	{
		$threadTitle = $this->getThreadTitle($orderItem);
		$threadMessage = $this->getThreadMessage($orderItem);
		
		return \XF::asVisitor(
			$orderItem->User,
			function () use ($forum, $threadTitle, $threadMessage, $orderItem): \XF\Service\Thread\Creator
			{
				/** @var \XF\Service\Thread\Creator $creator */
				$creator = $this->service('XF:Thread\Creator', $forum);
				$creator->setIsAutomated();
			
				$creator->setContent($threadTitle, $threadMessage, false);
				$creator->setPrefix($orderItem->Product->thread_prefix_id);
			
				return $creator;
			}
		);
	}
	
	/**
	 * @param \DBTech\eCommerce\Entity\OrderItem $orderItem
	 *
	 * @return mixed|null|string|string[]
	 */
	protected function getThreadTitle(OrderItem $orderItem)
	{
		$product = $orderItem->Product;
		$phraseParams = [
			'title' => $product->full_title,
			'product_title' => $product->title,
			'tag_line' => $product->tagline,
			'username' => $product->User ? $product->User->username : $product->username,
			'product_link' => $this->app->router('public')->buildLink('canonical:dbtech-ecommerce', $product),
		];
		
		$phrase = \XF::phrase('dbtech_ecommerce_purchase_thread_title_create', $phraseParams);
		
		return $phrase->render('raw');
	}
	
	/**
	 * @param \DBTech\eCommerce\Entity\OrderItem $orderItem
	 *
	 * @return mixed|null|string|string[]
	 */
	protected function getThreadMessage(OrderItem $orderItem)
	{
		$product = $orderItem->Product;
		
		$phraseParams = [
			'title' => new \XF\PreEscaped($orderItem->full_title),
			'product_title' => new \XF\PreEscaped($orderItem->title),
			'fields' => '',
			'username' => new \XF\PreEscaped($orderItem->User->username),
			'product_link' => $this->app->router('public')->buildLink('canonical:dbtech-ecommerce', $product),
		];
		
		/** @var \XF\CustomField\Set $fieldSet */
		$fieldSet = $orderItem->product_fields;
		$fieldDefinition = $fieldSet->getDefinitionSet()
			->filterOnly($orderItem->Product->field_cache);
		
		/** @var \XF\CustomField\Definition $definition */
		foreach ($fieldDefinition as $fieldId => $definition)
		{
			$value = $fieldSet->{$definition->field_id};
			if ($definition->hasValue($value))
			{
				switch ($definition->match_type)
				{
					case 'date':
						$phraseParams['fields'] .= \DateTime::createFromFormat('Y-m-d', $value, \XF::language()->getTimeZone());
						break;
					
					case 'color':
						$phraseParams['fields'] .= $value;
						break;
					
					default:
						$value = $definition->getFormattedValue($value);
						if ($definition->type_group == 'text')
						{
							$value = htmlspecialchars_decode($value);
						}
						$phraseParams['fields'] .= $value;
						break;
				}
			}
			elseif ($definition->isRequired())
			{
				$phraseParams['fields'] .= $definition->getFormattedValue(\XF::phrase('n_a')->render('raw'));
			}
			
			$phraseParams['fields'] .= "\n";
		}
		
		$phrase = \XF::phrase('dbtech_ecommerce_purchase_thread_body_create', $phraseParams);
		
		return $phrase->render('raw');
	}
	
	/**
	 * @param \XF\Entity\Thread $thread
	 *
	 * @throws \Exception
	 */
	protected function afterThreadCreated(\XF\Entity\Thread $thread)
	{
		\XF::asVisitor($this->user, function () use ($thread)
		{
			/** @var \XF\Repository\Thread $threadRepo */
			$threadRepo = $this->repository('XF:Thread');
			$threadRepo->markThreadReadByVisitor($thread);
		});
		
		/** @var \XF\Repository\ThreadWatch $threadWatchRepo */
		$threadWatchRepo = $this->repository('XF:ThreadWatch');
		$threadWatchRepo->autoWatchThread($thread, $this->user, true);
	}
}