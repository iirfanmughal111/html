<?php

namespace DBTech\eCommerce\Admin\Controller;

use XF\Admin\Controller\AbstractController;
use XF\Mvc\ParameterBag;

/**
 * Class Order
 * @package DBTech\eCommerce\Admin\Controller
 */
class Order extends AbstractController
{
	/**
	 * @param $action
	 * @param ParameterBag $params
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function preDispatchController($action, ParameterBag $params)
	{
		$this->assertAdminPermission('dbtechEcomOrder');
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
		$order = $this->assertOrderExists($params->order_id, [
			'User',
		], 'requested_log_entry_not_found');
		
		if ($this->isPost())
		{
			/** @var \DBTech\eCommerce\Service\Order\Reverse $reverseService */
			$reverseService = \XF::app()->service('DBTech\eCommerce:Order\Reverse', $order, \XF::visitor());
			$reverseService->reverse();
			
			return $this->redirect($this->buildLink('dbtech-ecommerce/logs/orders'));
		}
		
		$viewParams = [
			'order' => $order,
		];
		return $this->view('DBTech\eCommerce:Order\Cancel', 'dbtech_ecommerce_order_cancel', $viewParams);
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
	public function actionDelete(ParameterBag $params)
	{
		$order = $this->assertOrderExists($params->order_id, [
			'User',
		], 'requested_log_entry_not_found');
		
		if ($this->isPost())
		{
			/** @var \DBTech\eCommerce\Service\Order\Reverse $reverseService */
			$reverseService = \XF::app()->service('DBTech\eCommerce:Order\Reverse', $order, \XF::visitor());
			$reverseService->delete();
			
			return $this->redirect($this->buildLink('dbtech-ecommerce/logs/orders'));
		}
		
		$viewParams = [
			'order' => $order,
		];
		return $this->view('DBTech\eCommerce:Order\Delete', 'dbtech_ecommerce_order_delete', $viewParams);
	}
	
	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\Error|\XF\Mvc\Reply\Redirect|\XF\Mvc\Reply\View
	 * @throws \LogicException
	 * @throws \InvalidArgumentException
	 * @throws \Exception
	 * @throws \XF\Mvc\Reply\Exception
	 * @throws \XF\PrintableException
	 */
	public function actionComplete(ParameterBag $params)
	{
		$order = $this->assertOrderExists($params->order_id, [
			'User',
		], 'requested_log_entry_not_found');
		
		if ($order->isCompleted())
		{
			return $this->error(\XF::phrase('dbtech_ecommerce_order_already_completed'));
		}
		
		if ($this->isPost())
		{
			/** @var \DBTech\eCommerce\Service\Order\Complete $orderService */
			$orderService = \XF::app()->service('DBTech\eCommerce:Order\Complete', $order, $order->User);
			$orderService->ignoreUnpurchasable(true);
			
			$successful = $orderService->initiate($errors);
			if (!$successful)
			{
				throw $this->exception($this->error($errors));
			}
			
			$orderService->complete();
			
			// Don't allow downloading invoices for this order since we didn't actually pay
			$order->fastUpdate('has_invoice', 0);
			
			return $this->redirect($this->buildLink('dbtech-ecommerce/logs/orders'));
		}
		
		$viewParams = [
			'order' => $order,
		];
		return $this->view(
			'DBTech\eCommerce:Order\Complete',
			$order->order_state == 'reversed' ?
				'dbtech_ecommerce_order_restore' :
				'dbtech_ecommerce_order_complete',
			$viewParams
		);
	}
	
	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\Error|\XF\Mvc\Reply\Redirect|\XF\Mvc\Reply\View
	 * @throws \LogicException
	 * @throws \InvalidArgumentException
	 * @throws \Exception
	 * @throws \XF\Mvc\Reply\Exception
	 * @throws \XF\PrintableException
	 */
	public function actionShip(ParameterBag $params)
	{
		$order = $this->assertOrderExists($params->order_id, [
			'User',
		], 'requested_log_entry_not_found');
		
		if (!$order->isCompleted())
		{
			return $this->error(\XF::phrase('dbtech_ecommerce_order_is_not_yet_complete'));
		}
		
		if ($order->order_state == 'shipped')
		{
			return $this->error(\XF::phrase('dbtech_ecommerce_order_has_already_shipped'));
		}
		
		if ($this->isPost())
		{
			/** @var \DBTech\eCommerce\Service\Order\Ship $shipService */
			$shipService = \XF::app()->service('DBTech\eCommerce:Order\Ship', $order, \XF::visitor());
			
			if ($this->filter('customer_alert', 'bool') && $order->canSendModeratorActionAlert())
			{
				$shipService->setSendAlert(true, $this->filter('customer_alert_reason', 'str'));
			}
			
			$shipService->ship();
			
			return $this->redirect($this->buildLink('dbtech-ecommerce/logs/orders'));
		}
		
		$viewParams = [
			'order' => $order,
		];
		return $this->view('DBTech\eCommerce:Order\Ship', 'dbtech_ecommerce_order_ship', $viewParams);
	}
	
	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\Error|\XF\Mvc\Reply\Redirect|\XF\Mvc\Reply\View
	 * @throws \LogicException
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionApplyCoupon(ParameterBag $params)
	{
		$order = $this->assertOrderExists($params->order_id, [
			'User',
		], 'requested_log_entry_not_found');
		
		if ($order->coupon_id)
		{
			return $this->error(\XF::phrase('dbtech_ecommerce_coupon_already_added'));
		}
		
		if ($this->isPost())
		{
			$discount = $this->filter('coupon_percent', 'float');
			
			/** @var \DBTech\eCommerce\Service\Coupon\Create $creator */
			$creator = $this->service('DBTech\eCommerce:Coupon\Create');
			
			$creator->getCoupon()->bulkSet([
				'coupon_code' => 'AUTOORDER' . $order->order_id,
				'coupon_type' => 'percent',
				'coupon_percent' => 0,
				'coupon_value' => 0,
				'discount_excluded' => false,
				'allow_auto_discount' => true,
				'remaining_uses' => 1,
				'minimum_products' => 0,
				'maximum_products' => 0,
				'minimum_cart_value' => 0,
				'maximum_cart_value' => 0,
				'start_date' => \XF::$time
			]);
			
			$discounts = [];
			
			/** @var \DBTech\eCommerce\Entity\OrderItem $orderItem */
			foreach ($order->Items as $orderItem)
			{
				if (!$orderItem->product_id)
				{
					continue;
				}
				
				$discounts[] = [
					'product_id' => $orderItem->product_id,
					'product_value' => $discount,
				];
			}
			$creator->setProductDiscounts($discounts);
			
			$creator->setTitle('AUTOORDER' . $order->order_id);
			
			$dateInput = $this->filter([
				'length_amount' => 'uint',
				'length_unit' => 'str',
			]);
			$creator->setDuration($dateInput['length_amount'], $dateInput['length_unit']);
			
			if (!$creator->validate($errors))
			{
				return $this->error($errors);
			}
			
			/** @var \DBTech\eCommerce\Entity\Coupon $coupon */
			$coupon = $creator->save();
			
			$order->fastUpdate('coupon_id', $coupon->coupon_id);
			
			/** @var \DBTech\eCommerce\Entity\OrderItem $orderItem */
			foreach ($order->Items as $orderItem)
			{
				$orderItem->fastUpdate('coupon_id', $coupon->coupon_id);
			}
			
			// Create coupon
			return $this->redirect($this->buildLink('dbtech-ecommerce/logs/orders'));
		}
		
		$viewParams = [
			'order' => $order,
		];
		return $this->view('DBTech\eCommerce:Order\ApplyCoupon', 'dbtech_ecommerce_order_coupon', $viewParams);
	}
	
	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\View|\XF\Mvc\Reply\Redirect
	 * @throws \LogicException
	 * @throws \InvalidArgumentException
	 * @throws \Exception
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionInvoice(ParameterBag $params)
	{
		$order = $this->assertOrderExists($params->order_id, [
			'User',
		], 'requested_log_entry_not_found');
		
		/** @var \DBTech\eCommerce\Service\Order\Invoice $invoicer */
		$invoicer = \XF::app()->service('DBTech\eCommerce:Order\Invoice', $order, $order->User);
		$invoicer->generate(true);
		
		$this->setResponseType('raw');
		
		$viewParams = [
			'order' => $order,
			'filename' => $invoicer->getInvoiceFileName(),
			'abstractPath' => $invoicer->getInvoiceAbstractPath()
		];
		return $this->view('DBTech\eCommerce:Order\Invoice\View', '', $viewParams);
	}
	
	
	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\View|\XF\Mvc\Reply\Redirect
	 * @throws \LogicException
	 * @throws \InvalidArgumentException
	 * @throws \Exception
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionShippingLabel(ParameterBag $params)
	{
		$order = $this->assertOrderExists($params->order_id, [
			'User',
		], 'requested_log_entry_not_found');
		
		/** @var \DBTech\eCommerce\Service\Order\ShippingLabel $labeler */
		$labeler = \XF::app()->service('DBTech\eCommerce:Order\ShippingLabel', $order, $order->User);
		$labeler->generate(true);
		
		$this->setResponseType('raw');
		
		$viewParams = [
			'order' => $order,
			'filename' => $labeler->getShippingLabelFileName(),
			'abstractPath' => $labeler->getShippingLabelAbstractPath()
		];
		return $this->view('DBTech\eCommerce:Order\ShippingLabel\View', '', $viewParams);
	}
	
	/**
	 * @param int|null $id
	 * @param array|string|null $with
	 * @param null|string $phraseKey
	 *
	 * @return \DBTech\eCommerce\Entity\Order
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function assertOrderExists(?int $id, $with = null, ?string $phraseKey = null): \DBTech\eCommerce\Entity\Order
	{
		return $this->assertRecordExists('DBTech\eCommerce:Order', $id, $with, $phraseKey);
	}
}