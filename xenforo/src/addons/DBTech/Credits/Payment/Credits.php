<?php

namespace DBTech\Credits\Payment;

use XF\Payment\AbstractProvider;
use XF\Payment\CallbackState;
use XF\Entity\PaymentProfile;
use XF\Entity\PurchaseRequest;
use XF\Mvc\Controller;
use XF\Purchasable\Purchase;

class Credits extends AbstractProvider
{
	/**
	 * @return string
	 */
	public function getTitle(): string
	{
		return 'DragonByte Credits';
	}

	/**
	 * @param \XF\Entity\PaymentProfile $profile
	 *
	 * @return string
	 */
	public function renderConfig(PaymentProfile $profile): string
	{
		$data = [
			'profile' => $profile,
			'currencies' => \XF::finder('DBTech\Credits:Currency')
				->fetch()
				->pluckNamed('title', 'currency_id')
		];
		return \XF::app()->templater()->renderTemplate('admin:payment_profile_' . $this->providerId, $data);
	}

	/**
	 * @param array $options
	 * @param array $errors
	 *
	 * @return bool
	 */
	public function verifyConfig(array &$options, &$errors = []): bool
	{
		$currency = \XF::em()->find('DBTech\Credits:Currency', $options['currency_id']);
		if (!$currency)
		{
			$errors[] = \XF::phrase('dbtech_credits_invalid_currency');
			return false;
		}

		if (empty($options['exchange_rate']))
		{
			$errors[] = \XF::phrase('dbtech_credits_must_specify_exchange_rate');
			return false;
		}

		return true;
	}

	/**
	 * @param \XF\Mvc\Controller $controller
	 * @param \XF\Entity\PurchaseRequest $purchaseRequest
	 * @param \XF\Purchasable\Purchase $purchase
	 *
	 * @return \XF\Mvc\Reply\Error|\XF\Mvc\Reply\View
	 */
	public function initiatePayment(Controller $controller, PurchaseRequest $purchaseRequest, Purchase $purchase)
	{
		$paymentProfile = $purchase->paymentProfile;
		$cost = $purchase->cost * $paymentProfile->options['exchange_rate'];
		$currency = $this->getCurrencyFromPaymentProfile($paymentProfile);

		/** @var \DBTech\Credits\Repository\EventTrigger $eventTriggerRepo */
		$eventTriggerRepo = $this->getEventTriggerRepo();

		try
		{
			$eventTriggerRepo->getHandler('payment')
				->testApply([
					'multiplier'      => $cost,
					'currency_id'     => $currency->currency_id,
					'purchaseRequest' => $purchaseRequest,
					'paymentProfile'  => $paymentProfile,
					'purchaser'       => $purchase->purchaser,
					'purchase'        => $purchase,
				], $purchase->purchaser)
			;
		}
		catch (\Exception $e)
		{
			return $controller->error($e->getMessage());
		}

		$viewParams = [
			'purchaseRequest' => $purchaseRequest,
			'paymentProfile'  => $paymentProfile,
			'purchaser'       => $purchase->purchaser,
			'purchase'        => $purchase,
			'currency'        => $currency,
			'cost'            => $cost
		];

		return $controller->view('DBTech\Credits:Payment', 'dbtech_credits_payment_initiate', $viewParams);
	}

	/**
	 * @param \XF\Mvc\Controller $controller
	 * @param \XF\Entity\PurchaseRequest $purchaseRequest
	 * @param \XF\Entity\PaymentProfile $paymentProfile
	 * @param \XF\Purchasable\Purchase $purchase
	 *
	 * @return \XF\Mvc\Reply\Error|\XF\Mvc\Reply\Redirect|null
	 */
	public function processPayment(Controller $controller, PurchaseRequest $purchaseRequest, PaymentProfile $paymentProfile, Purchase $purchase)
	{
		$refId = $purchaseRequest->purchase_request_id . '_' . md5(\XF::$time);
		$currency = $this->getCurrencyFromPaymentProfile($paymentProfile);

		/** @var \DBTech\Credits\Repository\EventTrigger $eventTriggerRepo */
		$eventTriggerRepo = $this->getEventTriggerRepo();

		try
		{
			$eventTriggerRepo->getHandler('payment')
				->apply($refId, [
					'multiplier'      => $purchase->cost * $paymentProfile->options['exchange_rate'],
					'currency_id'     => $currency->currency_id,
					'purchaseRequest' => $purchaseRequest,
					'paymentProfile'  => $paymentProfile,
					'purchaser'       => $purchase->purchaser,
					'purchase'        => $purchase,
					'content_id'      => $purchaseRequest->purchase_request_id,
					'content_type'    => 'payment',
				], $purchase->purchaser)
			;
		}
		catch (\Exception $e)
		{
			return $controller->error($e->getMessage());
		}

		$state = new CallbackState();
		$state->transactionId = $refId;
		$state->paymentResult = CallbackState::PAYMENT_RECEIVED;
		$state->purchaseRequest = $purchaseRequest;
		$state->paymentProfile = $paymentProfile;

		$this->completeTransaction($state);

		$this->log($state);

		return $controller->redirect($purchase->returnUrl, '');
	}

	/**
	 * @param \XF\Http\Request $request
	 *
	 * @return \XF\Payment\CallbackState
	 */
	public function setupCallback(\XF\Http\Request $request): CallbackState
	{
		return new CallbackState();
	}

	/**
	 * @param \XF\Payment\CallbackState $state
	 */
	public function getPaymentResult(CallbackState $state)
	{
		$state->paymentResult = CallbackState::PAYMENT_RECEIVED;
	}

	/**
	 * @param \XF\Payment\CallbackState $state
	 */
	public function prepareLogData(CallbackState $state)
	{
		$state->logDetails = [];
	}

	/**
	 * @param \XF\Entity\PaymentProfile $paymentProfile
	 * @param null $error
	 *
	 * @return \DBTech\Credits\Entity\Currency|\XF\Mvc\Entity\Entity|null
	 */
	protected function getCurrencyFromPaymentProfile(PaymentProfile $paymentProfile, &$error = null)
	{
		if (empty($paymentProfile->options['currency_id']))
		{
			$error = \XF::phrase('dbtech_credits_invalid_currency');
			return null;
		}

		$currency = \XF::em()->find('DBTech\Credits:Currency', $paymentProfile->options['currency_id']);
		if (!$currency)
		{
			$error = \XF::phrase('this_item_cannot_be_purchased_at_moment');
			return null;
		}

		return $currency;
	}

	/**
	 * @return \DBTech\Credits\Repository\EventTrigger|\XF\Mvc\Entity\Repository
	 */
	protected function getEventTriggerRepo()
	{
		return \XF::repository('DBTech\Credits:EventTrigger');
	}
}