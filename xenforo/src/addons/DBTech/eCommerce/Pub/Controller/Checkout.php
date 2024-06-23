<?php

namespace DBTech\eCommerce\Pub\Controller;

use DBTech\eCommerce\Entity\ShippingCombination;
use XF\Mvc\ParameterBag;
use XF\Mvc\RouteMatch;

/**
 * Class Checkout
 *
 * @package DBTech\eCommerce\Pub\Controller
 */
class Checkout extends AbstractController
{
	/**
	 * @param $action
	 * @param ParameterBag $params
	 *
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function preDispatchController($action, ParameterBag $params)
	{
		/** @var \DBTech\eCommerce\XF\Entity\User $visitor */
		$visitor = \XF::visitor();
		
		if (!$visitor->canViewDbtechEcommerceProducts($error))
		{
			throw $this->exception($this->noPermission($error));
		}
		
		if (!$visitor->canPurchaseDbtechEcommerceProducts($error))
		{
			throw $this->exception($this->noPermission($error));
		}
	}
	
	
	/**
	 * @return \XF\Mvc\Reply\Redirect|\XF\Mvc\Reply\Reroute|\XF\Mvc\Reply\View
	 * @throws \XF\Mvc\Reply\Exception
	 * @throws \Exception
	 */
	public function actionIndex()
	{
		$this->assertCanonicalUrl($this->buildLink('dbtech-ecommerce/checkout'));
		
		try
		{
			$order = $this->assertOrderExists();
		}
		catch (\XF\Mvc\Reply\Exception $e)
		{
			$pendingOrders = $this->getOrderRepo()->findOrdersAwaitingPayment();
			$totalPendingOrders = $pendingOrders->total();
			if ($totalPendingOrders == 1)
			{
				return $this->rerouteController(__CLASS__, 'retry', [
					'order_id' => $pendingOrders->fetchOne()->order_id,
				]);
			}
			elseif ($totalPendingOrders > 1)
			{
				return $this->redirect($this->buildLink('dbtech-ecommerce/account', null, [
					'state' => 'awaiting_payment'
				]), \XF::phrase('dbtech_ecommerce_redirecting'));
			}
			else
			{
				throw $e;
			}
		}
		
		if (!$order->Items->count())
		{
			$order->delete();
			
			return $this->redirect(
				$this->buildLink('dbtech-ecommerce'),
				\XF::phrase('dbtech_ecommerce_redirecting')
			);
		}
		
		/** @var \DBTech\eCommerce\XF\Entity\User $visitor */
		$visitor = \XF::visitor();
		
		if (!$visitor->user_id && $order->isAccountRequired())
		{
			return $this->rerouteController(__CLASS__, 'register');
		}

		
		if (!$order->address_id && $order->isAddressRequired())
		{
			return $this->rerouteController(__CLASS__, 'address');
		}
		
		if ($order->Coupon)
		{
			// Run this here so that if the coupon is no longer valid, it's removed straight away in the UI
			$order->validateCoupon();
		}
		
		$viewParams = [
			'order' => $order,
		];
		return $this->view('DBTech\eCommerce:Checkout\Index', 'dbtech_ecommerce_checkout', $viewParams);
	}
	
	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\Redirect|\XF\Mvc\Reply\View
	 * @throws \XF\Mvc\Reply\Exception
	 * @throws \XF\PrintableException
	 */
	public function actionRemoveItem(ParameterBag $params)
	{
		$order = $this->assertOrderExists();
		$item = $this->assertOrderItemExists($params->order_item_id);
		
		if ($item->order_id != $order->order_id)
		{
			// Just to be safe
			throw $this->exception($this->noPermission());
		}
		
		if ($this->isPost())
		{
			$item->delete();
			
			if (!$order->Items->count())
			{
				$order->delete();
				
				return $this->redirect(
					$this->buildLink('dbtech-ecommerce'),
					\XF::phrase('dbtech_ecommerce_redirecting')
				);
			}
			
			return $this->redirect($this->getDynamicRedirect($this->buildLink('dbtech-ecommerce/checkout')));
		}
		else
		{
			$viewParams = [
				'order' => $order,
				'item' => $item,
			];
			return $this->view('DBTech\eCommerce:Checkout\RemoveItem', 'dbtech_ecommerce_checkout_remove_item', $viewParams);
		}
	}
	
	/**
	 * @return \XF\Mvc\Reply\AbstractReply
	 * @throws \LogicException
	 * @throws \Exception
	 * @throws \XF\Mvc\Reply\Exception
	 * @throws \XF\PrintableException
	 */
	public function actionUpdate(): \XF\Mvc\Reply\AbstractReply
	{
		$this->assertPostOnly();
		
		/** @var \DBTech\eCommerce\XF\Entity\User $visitor */
		$visitor = \XF::visitor();
		
		$order = $this->assertOrderExists();
		
		if ($this->filter('delete', 'bool'))
		{
			foreach ($this->filter('order_item_ids', 'array-uint') as $orderItemId)
			{
				if (isset($order->Items[$orderItemId]))
				{
					$order->Items[$orderItemId]->delete();
					unset($order->Items[$orderItemId]);
				}
			}
			
			if (!count($order->Items))
			{
				$order->delete();
				return $this->redirect($this->buildLink('dbtech-ecommerce'), \XF::phrase('dbtech_ecommerce_cart_updated'));
			}
		}
		elseif ($this->filter('update_quantity', 'bool'))
		{
			foreach ($this->filter('quantity', 'array-uint') as $orderItemId => $quantity)
			{
				if (!$order->Items->offsetExists($orderItemId))
				{
					continue;
				}

				/** @var \DBTech\eCommerce\Entity\OrderItem $orderItem */
				$orderItem = $order->Items->offsetGet($orderItemId);

				if (!$quantity)
				{
					$orderItem->delete();
					$order->Items->offsetUnset($orderItemId);
				}
				else
				{
					if (!$orderItem->Product->hasQuantityFunctionality())
					{
						$orderItem->quantity = 1;
					}
					else
					{
						$orderItem->quantity = $quantity;

						if ($orderItem->Product->hasStockFunctionality())
						{
							if ($orderItem->Cost->stock <= 0)
							{
								return $this->error(
									\XF::phrase('dbtech_ecommerce_selected_variation_out_of_stock')
								);
							}
							elseif ($orderItem->Cost->stock < $orderItem->quantity)
							{
								return $this->error(\XF::phrase(
									'dbtech_ecommerce_selected_variation_only_has_x_in_stock',
									[
										'available' => \XF::language()->numberFormat($orderItem->Cost->stock)
									]
								));
							}
						}
					}

					$orderItem->saveIfChanged();
				}
			}

			if (!count($order->Items))
			{
				$order->delete();
				return $this->redirect($this->buildLink('dbtech-ecommerce'), \XF::phrase('dbtech_ecommerce_cart_updated'));
			}
		}

		if ($this->options()->dbtechEcommerceCoupons['enabled'])
		{
			$couponCode = $this->filter('coupon_code', 'str');
			if ($couponCode)
			{
				/** @var \DBTech\eCommerce\Entity\Coupon $coupon */
				/** @noinspection PhpUndefinedMethodInspection */
				$coupon = $this->finder('DBTech\eCommerce:Coupon')
					->where('coupon_code', $couponCode)
					->isValid()
					->order('expiry_date', 'DESC')
					->fetchOne()
				;
				
				if ($coupon)
				{
					if (!$order->applyCoupon($coupon))
					{
						throw $this->errorException(\XF::phrase('dbtech_ecommerce_coupon_could_not_be_applied'));
					}
				}
				else
				{
					throw $this->errorException(\XF::phrase('dbtech_ecommerce_could_not_find_coupon'));
				}
			}
			elseif ($order->coupon_id)
			{
				$order->removeCoupon();
			}
		}
		elseif ($order->coupon_id)
		{
			$order->removeCoupon();
		}
		
		$vatId = $this->filter('sales_tax_id', 'str');
		if ($order->hasVatInfo()
			&& !$order->Address->sales_tax_id
			&& $vatId
		) {
			$addressRepo = $this->getAddressRepo();
			$valid = $addressRepo->validateVatId($vatId, $order->Address, $error);
			if ($error)
			{
				throw $this->errorException($error);
			}
			
			if ($valid)
			{
				$order->Address->sales_tax_id = $vatId;
				$order->Address->save();
			}
		}
		
		if ($visitor->user_id)
		{
			$storeCredit = $this->filter('store_credit', 'uint');
			$order->store_credit_amount = min($visitor->dbtech_ecommerce_store_credit, $storeCredit, ceil($order->getOrderTotal(false)));
		}
		
		$productFields = $this->filter('product_fields', 'array');
		
		/** @var \DBTech\eCommerce\Entity\OrderItem $orderItem */
		foreach ($order->Items as $orderItem)
		{
			/** @var \XF\CustomField\Set $fieldSet */
			$fieldSet = $orderItem->product_fields;
			$fieldDefinition = $fieldSet->getDefinitionSet()
				->filterEditable($fieldSet, 'user')
				->filterOnly($orderItem->Product->field_cache);
			
			$productFieldsShown = array_keys($fieldDefinition->getFieldDefinitions());
			
			if ($productFieldsShown)
			{
				$fieldSet->bulkSet(
					(isset($productFields[$orderItem->order_item_id]) && is_array($productFields[$orderItem->order_item_id]))
						? $productFields[$orderItem->order_item_id]
						: [],
					$productFieldsShown
				);
				
				$orderItem->save();
			}
		}
		
		$order->save();
		
		return $this->redirect($this->buildLink('dbtech-ecommerce/checkout'), \XF::phrase('dbtech_ecommerce_cart_updated'));
	}

	/**
	 * @return \XF\Mvc\Reply\Redirect|\XF\Mvc\Reply\View
	 * @throws \XF\Mvc\Reply\Exception
	 * @throws \Exception
	 */
	public function actionRegister()
	{
		$order = $this->assertOrderExists();
		
		/** @var \DBTech\eCommerce\XF\Entity\User $visitor */
		$visitor = \XF::visitor();
		
		if ($visitor->user_id || !$order->isAccountRequired())
		{
			return $this->redirect($this->buildLink('dbtech-ecommerce/checkout'));
		}
		
//		$this->assertRegistrationActive();
		
		$fields = [];
		if ($login = $this->filter('login', 'str'))
		{
			if ($this->app->validator('Email')->isValid($login))
			{
				$fields['email'] = $login;
			}
			else
			{
				$fields['username'] = $login;
			}
		}
		
		/** @var \XF\Service\User\RegisterForm $regForm */
		$regForm = $this->service('XF:User\RegisterForm');
		$regForm->saveStateToSession($this->session());
		
		$viewParams = [
			'order' => $order,
			
			'fields' => $fields,
			'regForm' => $regForm,
			'providers' => $this->repository('XF:ConnectedAccount')->getUsableProviders(true)
		];
		return $this->view('DBTech\eCommerce:Checkout\Register', 'dbtech_ecommerce_checkout_register', $viewParams);
	}
	
	/**
	 * @return \DBTech\eCommerce\Service\Address\Create
	 */
	protected function setupAddressCreate(): \DBTech\eCommerce\Service\Address\Create
	{
		/** @var \DBTech\eCommerce\Service\Address\Create $creator */
		$creator = $this->service('DBTech\eCommerce:Address\Create');
		
		$bulkInput = $this->filter([
			'title' => 'str',
			'business_title' => 'str',
			'business_co' => 'str',
			'address1' => 'str',
			'address2' => 'str',
			'address3' => 'str',
			'address4' => 'str',
			'country_code' => 'str',
			'sales_tax_id' => 'str',
			'is_default' => 'bool',
			'email' => 'str',
		]);
		$creator->getAddress()->bulkSet($bulkInput);
		
		return $creator;
	}
	
	/**
	 * @return \XF\Mvc\Reply\Error|\XF\Mvc\Reply\Redirect|\XF\Mvc\Reply\Reroute|\XF\Mvc\Reply\View
	 * @throws \LogicException
	 * @throws \Exception
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionAddress()
	{
		$order = $this->assertOrderExists();
		
		/** @var \DBTech\eCommerce\XF\Entity\User $visitor */
		$visitor = \XF::visitor();
		
		if (!$visitor->user_id && $order->isAccountRequired())
		{
			return $this->rerouteController(__CLASS__, 'register');
		}
		
		if ($this->isPost())
		{
			$addressId = $this->filter('address_id', 'uint');
			if ($addressId)
			{
				$address = $this->assertAddressExists($addressId);
			}
			else
			{
				/** @var \DBTech\eCommerce\Service\Address\Create $creator */
				$creator = $this->setupAddressCreate();
				
				if (!$creator->validate($errors))
				{
					throw $this->errorException($errors);
				}
				
				/** @var \DBTech\eCommerce\Entity\Address $address */
				$address = $creator->save();
			}
			
			$order->address_id = $address->address_id;
			
			if ($order->hasPhysicalProduct())
			{
				$addressId = $this->filter('shipping_address_id', 'uint');
				if ($addressId)
				{
					$address = $this->assertAddressExists($addressId);
				}
				
				if (!count($address->ApplicableShippingMethods))
				{
					return $this->error(\XF::phrase('dbtech_ecommerce_one_or_more_items_does_not_ship_to_this_country'));
				}
				
				/** @var ShippingCombination[]|\XF\Mvc\Entity\ArrayCollection $shippingCombinations */
				$shippingCombinations = $address->ApplicableShippingMethods;
				
				/** @var \DBTech\eCommerce\Entity\OrderItem $item */
				foreach ($order->Items as $item)
				{
					$applicableShippingMethods = $shippingCombinations->filter(function (ShippingCombination $combination) use ($item): ?ShippingCombination
					{
						if (!$combination->isApplicableToProduct($item->Product))
						{
							return null;
						}
						
						return $combination;
					});
					
					if ($item->Product->hasShippingFunctionality())
					{
						if ($item->shipping_method_id
							&& !$applicableShippingMethods->offsetExists($item->shipping_method_id)
						) {
							// Shipping method is no longer applicable as the country has changed
							$item->shipping_method_id = 0;
							$item->clearCache('ShippingMethod');
						}
						
						if (!$item->shipping_method_id
							&& count($applicableShippingMethods) == 1
						) {
							// Set a default shipping method
							
							/** @var \DBTech\eCommerce\Entity\ShippingMethod $shippingMethod */
							$shippingMethod = $applicableShippingMethods->first();
							
							$item->shipping_method_id = $shippingMethod->shipping_method_id;
							$item->hydrateRelation('ShippingMethod', $shippingMethod);
						}
						
						$item->saveIfChanged();
					}
				}
			}
			
			// Overwrote $address instead of making a new variable name because
			// that way we can keep shipping address in sync even if shipping
			// address is not needed
			$order->shipping_address_id = $address->address_id;
			
			$order->saveIfChanged();
			
			return $this->redirect($this->buildLink('dbtech-ecommerce/checkout'));
		}

		if ($visitor->user_id)
		{
			$addresses = $this->getAddressRepo()
				->findAddressesForList()
				->fetch();

			/** @var \DBTech\eCommerce\Entity\Address $defaultAddress */
			$defaultAddress = $addresses->first();

			if ($defaultAddress
				&& $defaultAddress->is_default
			) {
				// List is sorted by default, so we can assume the first would be the default

				$order->address_id = $defaultAddress->address_id;
				$order->hydrateRelation('Address', $defaultAddress);
			}

			$addresses = $addresses->pluckNamed('title', 'address_id');
		}
		else
		{
			$addresses = $this->getAddressRepo()
				->findAddressesForList()
				->where('address_id', [$order->address_id, $order->shipping_address_id])
				->fetch()
				->pluckNamed('title', 'address_id')
			;
		}
		
		$viewParams = [
			'order' => $order,
			'addresses' => $addresses,
		];
		return $this->view('DBTech\eCommerce:Checkout\Address', 'dbtech_ecommerce_checkout_address', $viewParams);
	}
	
	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\Error|\XF\Mvc\Reply\Redirect|\XF\Mvc\Reply\View
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionUpdateDuration(ParameterBag $params)
	{
		$item = $this->assertOrderItemExists($params->order_item_id);
		
		if ($this->isPost())
		{
			$productCostId = $this->filter('pricing_tier', 'uint');
			if (!$item->Product->Costs->offsetExists($productCostId))
			{
				return $this->error(\XF::phrase('dbtech_ecommerce_update_duration_not_applicable'));
			}
			
			$item->fastUpdate('product_cost_id', $productCostId);
			
			return $this->redirect($this->buildLink('dbtech-ecommerce/checkout'), \XF::phrase('dbtech_ecommerce_update_duration_changed'));
		}
		
		$viewParams = [
			'item' => $item,
			'product' => $item->Product,
			'license' => $item->license_id ? $item->License : null
		];
		return $this->view('DBTech\eCommerce:Checkout\UpdateDuration', 'dbtech_ecommerce_checkout_update_duration', $viewParams);
	}
	
	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\Error|\XF\Mvc\Reply\Redirect|\XF\Mvc\Reply\View
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionShippingMethod(ParameterBag $params)
	{
		$item = $this->assertOrderItemExists($params->order_item_id);
		
		if (!$item->Order->ShippingAddress)
		{
			return $this->error(\XF::phrase('dbtech_ecommerce_please_choose_shipping_address'));
		}
		
		if (!count($item->Order->ShippingAddress->ApplicableShippingMethods))
		{
			return $this->error(\XF::phrase('dbtech_ecommerce_this_item_does_not_ship_to_your_country'));
		}
		
		if ($this->isPost())
		{
			$shippingMethodId = $this->filter('shipping_method_id', 'uint');
			$shippingMethod = $this->assertShippingMethodExists($shippingMethodId);
			
			if (!$shippingMethod->isApplicable($item->Order->ShippingAddress))
			{
				return $this->error(\XF::phrase('dbtech_ecommerce_shipping_method_not_applicable'));
			}
			
			$item->fastUpdate('shipping_method_id', $shippingMethodId);
			
			return $this->redirect($this->buildLink('dbtech-ecommerce/checkout'), \XF::phrase('dbtech_ecommerce_shipping_method_updated'));
		}
		
		$viewParams = [
			'item' => $item
		];
		return $this->view('DBTech\eCommerce:Checkout\ShippingMethod', 'dbtech_ecommerce_checkout_shipping_method', $viewParams);
	}
	
	/**
	 * @return \XF\Mvc\Reply\Error|\XF\Mvc\Reply\Redirect|\XF\Mvc\Reply\Reroute|\XF\Mvc\Reply\View
	 * @throws \Exception
	 * @throws \XF\Mvc\Reply\Exception
	 * @throws \XF\PrintableException
	 */
	public function actionComplete()
	{
		try
		{
			$order = $this->assertOrderExists();
		}
		catch (\XF\Mvc\Reply\Exception $e)
		{
			if (!\XF::visitor()->user_id && !$this->options()->dbtechEcommerceRequireAccount)
			{
				// Avoid a race condition where we're asked to complete someone else's payment
				throw $e;
			}
			
			$pendingOrders = $this->getOrderRepo()->findOrdersAwaitingPayment();
			$totalPendingOrders = $pendingOrders->total();
			if ($totalPendingOrders == 1)
			{
				return $this->rerouteController(__CLASS__, 'retry', [
					'order_id' => $pendingOrders->fetchOne()->order_id,
				]);
			}
			elseif ($totalPendingOrders > 1)
			{
				return $this->redirect($this->buildLink('dbtech-ecommerce/account', null, [
					'state' => 'awaiting_payment'
				]), \XF::phrase('dbtech_ecommerce_redirecting'));
			}
			else
			{
				throw $e;
			}
		}
		
		if (!$this->filter('confirm', 'bool'))
		{
			return $this->error(\XF::phrase('dbtech_ecommerce_cannot_proceed_without_accepting_terms'));
		}
		
		if (
			$order->hasDigitalDownload()
			&& $this->options()->dbtechEcommerceSeparateRefundPolicy
			&& !$this->filter('confirm_digital_refund', 'bool')
		) {
			return $this->error(\XF::phrase('dbtech_ecommerce_cannot_proceed_without_accepting_refund_policy'));
		}
		
		/** @var \DBTech\eCommerce\ControllerPlugin\Terms $terms */
		$terms = $this->plugin('DBTech\eCommerce:Terms');
		$terms->setTermsAccepted();
		
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
					throw $this->errorException(\XF::phrase('please_enter_value_for_all_required_fields'));
				}
				
				if (!$definition->isValid($value, $error, $value))
				{
					throw $this->errorException($error);
				}
			}
			
			if ($orderItem->Product->hasShippingFunctionality() && !$orderItem->validateShippingMethod())
			{
				throw $this->errorException(\XF::phrase('dbtech_ecommerce_please_choose_shipping_method_for_all_physical_products'));
			}
		}
		
		if ($order->getOrderTotal() > 0.00)
		{
			/** @var \XF\Repository\Payment $paymentRepo */
			$paymentRepo = \XF::repository('XF:Payment');
			$profiles = $profiles = $paymentRepo->getPaymentProfileOptionsData();
			
			if (count(\XF::options()->dbtechEcommercePaymentProfileIds) == 1)
			{
				$routeMatch = new RouteMatch();
				$routeMatch->setController('XF:Purchase');
				$routeMatch->setAction('Index');
				$routeMatch->setParam('purchasable_type_id', 'dbtech_ecommerce_order');
				$routeMatch->setResponseType('json');

				$this->request()->set('order_id', $order->order_id);
				$this->request()->set('payment_profile_id', reset(\XF::options()->dbtechEcommercePaymentProfileIds));

				return $this->reroute($routeMatch);
			}
			$viewParams = [
				'order' => $order,
				'profiles' => $profiles
			];
			return $this->view('DBTech\eCommerce:Checkout\Complete', 'dbtech_ecommerce_checkout_complete', $viewParams);
		}

		/** @var \DBTech\eCommerce\Service\Order\Complete $orderService */
		$orderService = \XF::app()->service('DBTech\eCommerce:Order\Complete', $order, $order->User);
		
		$successful = $orderService->initiate($errors);
		if (!$successful)
		{
			throw $this->errorException($errors);
		}
		
		$orderService->complete();
		
		return $this->redirect($this->buildLink('dbtech-ecommerce/account'), \XF::phrase('dbtech_ecommerce_order_completed'));
	}
	
	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\Reroute|\XF\Mvc\Reply\View
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionRetry(ParameterBag $params)
	{
		$order = $this->assertValidOrderAwaitingPayment($params->order_id);
		
		if ($this->isPost())
		{
			/** @var \XF\Repository\Payment $paymentRepo */
			$paymentRepo = \XF::repository('XF:Payment');
			$profiles = $paymentRepo->findPaymentProfilesForList()
				->pluckFrom(function ($e)
				{
					return ($e->display_title ?: $e->title);
				})
				->fetch();
			
			if (count(\XF::options()->dbtechEcommercePaymentProfileIds) == 1)
			{
				$routeMatch = new RouteMatch();
				$routeMatch->setController('XF:Purchase');
				$routeMatch->setAction('Index');
				$routeMatch->setParam('purchasable_type_id', 'dbtech_ecommerce_order');
				$routeMatch->setResponseType('json');

				$this->request()->set('order_id', $order->order_id);
				$this->request()->set('payment_profile_id', reset(\XF::options()->dbtechEcommercePaymentProfileIds));

				return $this->reroute($routeMatch);
			}
			
			$viewParams = [
				'order' => $order,
				'profiles' => $profiles
			];
			return $this->view(
				'DBTech\eCommerce:Checkout\Retry\Payment',
				'dbtech_ecommerce_checkout_retry_payment',
				$viewParams
			);
		}
		
		$viewParams = [
			'order' => $order
		];
		return $this->view('DBTech\eCommerce:Checkout\Retry', 'dbtech_ecommerce_checkout_retry', $viewParams);
	}
	
	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\View|\XF\Mvc\Reply\Redirect
	 * @throws \LogicException
	 * @throws \InvalidArgumentException
	 * @throws \Exception
	 * @throws \XF\Mvc\Reply\Exception
	 * @throws \XF\PrintableException
	 */
	public function actionCancel(ParameterBag $params)
	{
		$order = $this->assertValidOrderAwaitingPayment($params->order_id);
		
		if ($this->isPost())
		{
			/** @var \DBTech\eCommerce\Service\Order\Reverse $reverseService */
			$reverseService = \XF::app()->service('DBTech\eCommerce:Order\Reverse', $order, \XF::visitor());
			$reverseService->delete();
			
			return $this->redirect($this->buildLink('dbtech-ecommerce/account'));
		}
		
		$viewParams = [
			'order' => $order,
		];
		return $this->view('DBTech\eCommerce:Checkout\Cancel', 'dbtech_ecommerce_checkout_cancel', $viewParams);
	}
	
	/**
	 * @return \XF\Mvc\Reply\View
	 */
	public function actionCartPopup(): \XF\Mvc\Reply\AbstractReply
	{
		/** @var \DBTech\eCommerce\XF\Entity\User $visitor */
		$visitor = \XF::visitor();
		
		$order = null;
		
		try
		{
			$order = $this->assertOrderExists();
		}
		catch (\XF\Mvc\Reply\Exception $e)
		{
			if (!$visitor->user_id && $visitor->getDbtechEcommerceCartItems())
			{
				$this->app()->response()->setCookie('dbtechEcommerceCartItems', 0, 86400 * 365);
			}
		}
		
		$orderItems = [];
		if ($order)
		{
			$orderItems = $order->Items;
			
			if (count($orderItems) != $visitor->getDbtechEcommerceCartItems())
			{
				$visitor->rebuildDbtechEcommerceCartItems($order->order_id);
			}
		}
		
		$viewParams = [
			'order' => $order,
			'orderItems' => $orderItems
		];
		return $this->view('DBTech\eCommerce:Checkout\CartPopup', 'dbtech_ecommerce_checkout_cart_popup', $viewParams);
	}

	/**
	 * @param array $activities
	 *
	 * @return bool|\XF\Phrase
	 */
	public static function getActivityDetails(array $activities)
	{
		return \XF::phrase('dbtech_ecommerce_viewing_shopping_cart');
	}
	
	/**
	 * @return \DBTech\eCommerce\Entity\Order
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function assertOrderExists(): \DBTech\eCommerce\Entity\Order
	{
		/** @var \DBTech\eCommerce\Repository\Order $orderRepo */
		$orderRepo = $this->getOrderRepo();
		
		if (\XF::visitor()->user_id)
		{
			/** @var \DBTech\eCommerce\Entity\Order $order */
			$order = $orderRepo->findCurrentOrderForUser()->fetchOne();
		}
		else
		{
			/** @var \DBTech\eCommerce\Entity\Order $order */
			$order = $orderRepo->findCurrentOrderForGuest()->fetchOne();
		}
		
		if (!$order)
		{
			throw $this->errorException(\XF::phrase('dbtech_ecommerce_your_cart_is_empty'));
		}
		
		return $order;
	}

	/**
	 * @param int $id
	 * @param null $with
	 * @param string|null $phraseKey
	 *
	 * @return \DBTech\eCommerce\Entity\OrderItem
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function assertOrderItemExists(?int $id, $with = null, ?string $phraseKey = null): \DBTech\eCommerce\Entity\OrderItem
	{
		/** @var \DBTech\eCommerce\Entity\OrderItem $orderItem */
		$orderItem = $this->assertRecordExists('DBTech\eCommerce:OrderItem', $id, $with, $phraseKey);
		
		if ($orderItem->user_id != \XF::visitor()->user_id)
		{
			if (!$phraseKey)
			{
				$phraseKey = 'requested_page_not_found';
			}
			
			throw $this->exception(
				$this->notFound(\XF::phrase($phraseKey))
			);
		}
		
		return $orderItem;
	}

	/**
	 * @param int|null $id
	 * @param null $with
	 * @param string|null $phraseKey
	 *
	 * @return \DBTech\eCommerce\Entity\ShippingMethod
	 * @throws \XF\Mvc\Reply\Exception
	 * @noinspection PhpUnnecessaryLocalVariableInspection
	 */
	protected function assertShippingMethodExists(?int $id, $with = null, ?string $phraseKey = null): \DBTech\eCommerce\Entity\ShippingMethod
	{
		/** @var \DBTech\eCommerce\Entity\ShippingMethod $shippingMethod */
		$shippingMethod = $this->assertRecordExists('DBTech\eCommerce:ShippingMethod', $id, $with, $phraseKey);
		
		return $shippingMethod;
	}

	/**
	 * @param int|null $id
	 * @param null $with
	 * @param string|null $phraseKey
	 *
	 * @return \DBTech\eCommerce\Entity\Address
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function assertAddressExists(?int $id, $with = null, ?string $phraseKey = null): \DBTech\eCommerce\Entity\Address
	{
		/** @var \DBTech\eCommerce\Entity\Address $address */
		$address = $this->assertRecordExists('DBTech\eCommerce:Address', $id, $with, $phraseKey);
		
		if ($address->user_id != \XF::visitor()->user_id)
		{
			if (!$phraseKey)
			{
				$phraseKey = 'requested_page_not_found';
			}
			
			throw $this->exception(
				$this->notFound(\XF::phrase($phraseKey))
			);
		}
		
		return $address;
	}

	/**
	 * @param int|null $id
	 * @param null $with
	 * @param string|null $phraseKey
	 *
	 * @return \DBTech\eCommerce\Entity\Order
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function assertValidOrderAwaitingPayment(?int $id, $with = null, ?string $phraseKey = null): \DBTech\eCommerce\Entity\Order
	{
		/** @var \DBTech\eCommerce\Entity\Order $order */
		$order = $this->assertRecordExists('DBTech\eCommerce:Order', $id, $with, $phraseKey);
		
		if ($order->order_state != 'awaiting_payment' || $order->user_id != \XF::visitor()->user_id)
		{
			if (!$phraseKey)
			{
				$phraseKey = 'requested_page_not_found';
			}
			
			throw $this->exception(
				$this->notFound(\XF::phrase($phraseKey))
			);
		}
		
		return $order;
	}
	
	/**
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function assertRegistrationActive()
	{
		if (!$this->options()->registrationSetup['enabled'])
		{
			throw $this->exception(
				$this->error(\XF::phrase('new_registrations_currently_not_being_accepted'))
			);
		}
		
		// prevent discouraged IP addresses from registering
		if ($this->options()->preventDiscouragedRegistration && $this->isDiscouraged())
		{
			throw $this->exception(
				$this->error(\XF::phrase('new_registrations_currently_not_being_accepted'))
			);
		}
	}
	
	/**
	 * @return \DBTech\eCommerce\Repository\Order|\XF\Mvc\Entity\Repository
	 */
	protected function getOrderRepo()
	{
		return $this->repository('DBTech\eCommerce:Order');
	}
	
	/**
	 * @return \DBTech\eCommerce\Repository\Address|\XF\Mvc\Entity\Repository
	 */
	protected function getAddressRepo()
	{
		return $this->repository('DBTech\eCommerce:Address');
	}
}