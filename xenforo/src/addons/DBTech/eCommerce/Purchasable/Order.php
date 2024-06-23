<?php /** @noinspection PhpMissingReturnTypeInspection */

namespace DBTech\eCommerce\Purchasable;

use DBTech\eCommerce\Exception\CountryCodeMismatchException;
use XF\Purchasable\Purchase;
use XF\Purchasable\AbstractPurchasable;
use XF\Payment\CallbackState;

/**
 * Class Order
 *
 * @package DBTech\eCommerce\Purchasable
 */
class Order extends AbstractPurchasable
{
	/**
	 * @return mixed|\XF\Phrase
	 */
	public function getTitle(): \XF\Phrase
	{
		return \XF::phrase('dbtech_ecommerce_ecommerce_order');
	}

	/**
	 * @param \XF\Http\Request $request
	 * @param \XF\Entity\User $purchaser
	 * @param null $error
	 *
	 * @return bool|mixed|\XF\Purchasable\Purchase
	 * @throws \XF\PrintableException
	 * @throws \Exception
	 */
	public function getPurchaseFromRequest(\XF\Http\Request $request, \XF\Entity\User $purchaser, &$error = null)
	{
		$profileId = $request->filter('payment_profile_id', 'uint');
		
		/** @var \XF\Entity\PaymentProfile $paymentProfile */
		$paymentProfile = \XF::em()->find('XF:PaymentProfile', $profileId);
		if (!$paymentProfile || !$paymentProfile->active)
		{
			$error = \XF::phrase('please_choose_valid_payment_profile_to_continue_with_your_purchase');
			return false;
		}

		$orderId = $request->filter('order_id', 'uint');
		
		/** @var \DBTech\eCommerce\Entity\Order $order */
		$order = \XF::em()->find('DBTech\eCommerce:Order', $orderId);
		if (
			!$order
			|| !in_array($order->order_state, ['pending', 'awaiting_payment'])
			|| !$order->canPurchase()
		) {
			$error = \XF::phrase('this_item_cannot_be_purchased_at_moment');
			return false;
		}

		if (!in_array($profileId, \XF::app()->options()->dbtechEcommercePaymentProfileIds))
		{
			$error = \XF::phrase('selected_payment_profile_is_not_valid_for_this_purchase');
			return false;
		}

		if ($order->isAddressRequired() && \XF::options()->dbtechEcommerceAddress['validate'])
		{
			/** @var \DBTech\eCommerce\Repository\Country $countryRepo */
			$countryRepo = \XF::repository('DBTech\eCommerce:Country');
			try
			{
				$countryRepo->validateBillingAddressCountry($order->Address->country_code);
			}
			catch (CountryCodeMismatchException $e)
			{
				$error = \XF::phrase('dbtech_ecommerce_billing_country_ip_mismatch_checkout');
				return false;
			}
			catch (\Exception $e)
			{
				$error = \XF::phrase('dbtech_ecommerce_country_validation_server_error_checkout');
				return false;
			}
		}
		
		if ($order->order_state != 'awaiting_payment')
		{
			/** @var \DBTech\eCommerce\Service\Order\Complete $orderService */
			$orderService = \XF::app()->service('DBTech\eCommerce:Order\Complete', $order, $purchaser);
			
			$successful = $orderService->initiate($error);
			if (!$successful)
			{
				// $error variable should already be populated here
				return false;
			}
		}

		return $this->getPurchaseObject($paymentProfile, $order, $purchaser);
	}
	
	/**
	 * @param array $extraData
	 *
	 * @return array|mixed
	 */
	public function getPurchasableFromExtraData(array $extraData): array
	{
		$output = [
			'link' => '',
			'title' => '',
			'purchasable' => null
		];
		
		/** @var \DBTech\eCommerce\Entity\Order $order */
		$order = \XF::em()->find('DBTech\eCommerce:Order', $extraData['order_id']);
		if ($order)
		{
			$output['link'] = \XF::app()->router('admin')->buildLink('dbtech-ecommerce/logs/orders', $order);
			$output['title'] = \XF::phrase('dbtech_ecommerce_ecommerce_order') . ' #' . $order->order_id;
			$output['purchasable'] = $order;
		}
		return $output;
	}
	
	/**
	 * @param array $extraData
	 * @param \XF\Entity\PaymentProfile $paymentProfile
	 * @param \XF\Entity\User $purchaser
	 * @param null $error
	 *
	 * @return bool|mixed|Purchase
	 * @throws \LogicException
	 * @throws \Exception
	 */
	public function getPurchaseFromExtraData(
		array $extraData,
		\XF\Entity\PaymentProfile $paymentProfile,
		\XF\Entity\User $purchaser,
		&$error = null
	) {
		$order = $this->getPurchasableFromExtraData($extraData);
		if (!$order['purchasable'] || !$order['purchasable']->canPurchase())
		{
			$error = \XF::phrase('this_item_cannot_be_purchased_at_moment');
			return false;
		}

		if (!in_array($paymentProfile->payment_profile_id, \XF::app()->options()->dbtechEcommercePaymentProfileIds))
		{
			$error = \XF::phrase('selected_payment_profile_is_not_valid_for_this_purchase');
			return false;
		}

		return $this->getPurchaseObject($paymentProfile, $order['purchasable'], $purchaser);
	}
	
	/**
	 * @param \XF\Entity\PaymentProfile $paymentProfile
	 * @param \DBTech\eCommerce\Entity\Order $purchasable
	 * @param \XF\Entity\User $purchaser
	 *
	 * @return mixed|Purchase
	 * @throws \LogicException
	 * @throws \Exception
	 */
	public function getPurchaseObject(
		\XF\Entity\PaymentProfile $paymentProfile,
		$purchasable,
		\XF\Entity\User $purchaser
	): Purchase {
		$settings = \XF::options()->dbtechEcommerceSalesTax;
		
		$purchase = new Purchase();

		$purchase->title = \XF::phrase('dbtech_ecommerce_ecommerce_order') . ' #' . $purchasable->order_id . ' (' . $purchaser->username . ')';
		$purchase->description = implode(', ', $purchasable->getProductList());
		$purchase->cost = (
			$purchasable->hasPhysicalProduct()
				? $purchasable->cost_amount
				: ($settings['includeTax'] ? $purchasable->cost_amount : $purchasable->getTaxableOrderTotal(true))
		);
		$purchase->currency = $purchasable->currency;
		$purchase->recurring = false;
		$purchase->purchaser = $purchaser;
		$purchase->paymentProfile = $paymentProfile;
		$purchase->purchasableTypeId = $this->purchasableTypeId;
		$purchase->purchasableId = $purchasable->order_id;
		$purchase->purchasableTitle = \XF::phrase('dbtech_ecommerce_ecommerce_order') . ' #' . $purchasable->order_id;
		
		$data = [
			'order_id' => $purchasable->order_id,
		];
		
		$taxAmount = $purchasable->getSalesTax();
		if (!$taxAmount || $settings['includeTax'])
		{
			// If the tax amount is zero or we've already baked the tax into the payment,
			// override PayPal's profile information to prevent double taxation.
			$data['tax_amount'] = 0.00;
		}
		
		$purchase->extraData = $data;

		$router = \XF::app()->router('public');

		if ($purchasable->hasPhysicalProduct())
		{
			$purchase->returnUrl = $router->buildLink('canonical:dbtech-ecommerce/account/order', $purchasable);
		}
		else
		{
			$purchase->returnUrl = $router->buildLink('canonical:dbtech-ecommerce/licenses');
		}
		$purchase->cancelUrl = $router->buildLink('canonical:dbtech-ecommerce/checkout');

		return $purchase;
	}
	
	/**
	 * @param CallbackState $state
	 *
	 * @throws \LogicException
	 * @throws \Exception
	 * @throws \XF\PrintableException
	 */
	public function completePurchase(CallbackState $state)
	{
		$purchaseRequest = $state->getPurchaseRequest();
		$orderId = $purchaseRequest->extra_data['order_id'];
		$hasOrderRecord = $purchaseRequest->extra_data['order_record'] ?? null;

		$paymentResult = $state->paymentResult;
		$purchaser = $state->getPurchaser() ?: \XF::repository('XF:User')->getGuestUser();

		/** @var \DBTech\eCommerce\Entity\Order $order */
		$order = \XF::em()->find(
			'DBTech\eCommerce:Order',
			$orderId
		);

		if (!$order)
		{
			return;
		}

		/** @var \DBTech\eCommerce\Service\Order\Complete $orderService */
		$orderService = \XF::app()->service('DBTech\eCommerce:Order\Complete', $order, $purchaser);

		if ($state->extraData && is_array($state->extraData))
		{
			$orderService->setExtraData($state->extraData);
		}

		$orderRecord = null;

		switch ($paymentResult)
		{
			case CallbackState::PAYMENT_RECEIVED:
				$orderService->setPurchaseRequestKey($state->requestKey);
				$orderService->setTransactionId($state->transactionId);
				$orderService->ignoreUnpurchasable(true);
				$orderRecord = $orderService->complete();

				$state->logType = 'payment';
				$state->logMessage = 'Payment received, order processed.';
				break;

			case CallbackState::PAYMENT_REINSTATED:
				if ($hasOrderRecord)
				{
					$orderService->ignoreUnpurchasable(true);
					$orderRecord = $orderService->complete();

					$state->logType = 'payment';
					$state->logMessage = 'Reversal cancelled, order reactivated.';
				}
				else
				{
					// We can't reinstate the licenses because there doesn't appear to be an existing record.
					$state->logType = 'info';
					$state->logMessage = 'OK, no action.';
				}
				break;
		}

		if ($orderRecord && $purchaseRequest)
		{
			$extraData = $purchaseRequest->extra_data;
			$extraData['order_record'] = true;
			$purchaseRequest->extra_data = $extraData;
			$purchaseRequest->save();
		}
	}
	
	/**
	 * @param CallbackState $state
	 * @param null $error
	 *
	 * @return bool
	 */
	public function validatePurchaser(CallbackState $state, &$error = null): bool
	{
		if (!$state->getPurchaser())
		{
			if ($state->getPurchaseRequest()->user_id)
			{
				$error = 'Could not find user with user_id ' . $state->getPurchaseRequest()->user_id . '.';
				return false;
			}
		}
		
		return true;
	}
	
	/**
	 * @param CallbackState $state
	 *
	 * @throws \InvalidArgumentException
	 * @throws \LogicException
	 * @throws \Exception
	 * @throws \XF\PrintableException
	 */
	public function reversePurchase(CallbackState $state)
	{
		$purchaseRequest = $state->getPurchaseRequest();
		$purchaser = $state->getPurchaser() ?: \XF::repository('XF:User')->getGuestUser();

		$order = \XF::em()->find('DBTech\eCommerce:Order', $purchaseRequest->extra_data['order_id']);
		if ($order)
		{
			/** @var \DBTech\eCommerce\Service\Order\Reverse $reverseService */
			$reverseService = \XF::app()->service('DBTech\eCommerce:Order\Reverse', $order, $purchaser);
			
			if ($state->extraData && is_array($state->extraData))
			{
				$reverseService->setExtraData($state->extraData);
			}
			
			$reverseService->setPurchaseRequestKey($state->requestKey);
			$reverseService->setTransactionId($state->transactionId);
			$reverseService->reverse();
		}

		$state->logType = 'cancel';
		$state->logMessage = 'Payment refunded/reversed, order reversed.';
	}
	
	/**
	 * @param $profileId
	 *
	 * @return array
	 */
	public function getPurchasablesByProfileId($profileId)
	{
		if (in_array($profileId, \XF::options()->dbtechEcommercePaymentProfileIds))
		{
			return [
				'dbtech_ecommerce' => [
					'title' => 'DragonByte eCommerce',
					'link' => \XF::app()->router('admin')->buildLink('options/groups', ['group_id' => 'dbtechEcommerce'])
				]
			];
		}
		
		return [];
	}
	
	/**
	 * @param CallbackState $state
	 *
	 * @throws \Exception
	 */
	public function sendPaymentReceipt(CallbackState $state)
	{
		switch ($state->paymentResult)
		{
			case CallbackState::PAYMENT_RECEIVED:
			{
				$purchaser = $state->getPurchaser() ?: \XF::repository('XF:User')->getGuestUser();
				$purchaseRequest = $state->getPurchaseRequest();
				if ($purchaseRequest)
				{
					$purchasable = $this->getPurchasableFromExtraData($purchaseRequest->extra_data);
					
					/** @var \DBTech\eCommerce\Entity\Order $order */
					$order = $purchasable['purchasable'];
					
					$params = [
						'purchaser' => $purchaser,
						'purchaseRequest' => $purchaseRequest,
						'purchasable' => $purchasable,
						'hasInvoice' => false
					];
					
					$options = \XF::options();
					
					$mail = \XF::app()
						->mailer()
						->newMail()
					;
					
					if ($purchaser->user_id)
					{
						$mail->setToUser($purchaser);
					}
					else
					{
						$mail->setTo($order->ShippingAddress->email, $order->ShippingAddress->business_title);
					}
					
					if (
						$options->dbtechEcommerceInvoiceActive
						&& $options->dbtechEcommerceAutomaticInvoices
						&& $order
					) {
						try
						{
							/** @var \DBTech\eCommerce\Service\Order\Invoice $invoicer */
							$invoicer = \XF::app()->service('DBTech\eCommerce:Order\Invoice', $order, $purchaser);
							$invoicer->generate();

							$tempFile = \XF\Util\File::copyAbstractedPathToTempFile($invoicer->getInvoiceAbstractPath());
							$attachment = \Swift_Attachment::fromPath($tempFile);
							$attachment->setFilename($invoicer->getInvoiceFileName());

							$mail->getMessageObject()->attach($attachment);
							$params['hasInvoice'] = true;
						}
						catch (\Exception $e)
						{
							\XF::logException($e, false, '[eCommerce] Error generating invoice for email: ');
						}
					}
					
					$mail->setTemplate('payment_received_receipt_' . $this->purchasableTypeId, $params);
					$mail->send();
					
					/** @var \DBTech\eCommerce\Entity\OrderItem $orderItem */
					foreach ($order->Items as $orderItem)
					{
						$orderItem->Product
							->getHandler()
							->sendPaymentReceipt($purchaser, $purchaseRequest, $orderItem, $params)
						;
					}
				}
			}
		}
	}
}