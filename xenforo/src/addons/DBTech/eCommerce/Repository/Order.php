<?php

namespace DBTech\eCommerce\Repository;

use XF\Mvc\Entity\Repository;

/**
 * Class Order
 * @package DBTech\eCommerce\Repository
 */
class Order extends Repository
{
	/**
	 * @param array $limits
	 *
	 * @return \DBTech\eCommerce\Finder\Order
	 * @throws \InvalidArgumentException
	 */
	public function findOrdersForAccountList(array $limits = []): \DBTech\eCommerce\Finder\Order
	{
		$limits = array_replace([
			'visibility' => true
		], $limits);
		
		/** @var \DBTech\eCommerce\Finder\Order $orderFinder */
		$orderFinder = $this->finder('DBTech\eCommerce:Order');
		
		$orderFinder
			->where('user_id', \XF::visitor()->user_id)
			->with('full')
			->useDefaultOrder();
		
		if ($limits['visibility'])
		{
			$orderFinder->applyGlobalVisibilityChecks();
		}
		
		return $orderFinder;
	}
	
	/**
	 * @param int|null $userId
	 *
	 * @return \XF\Mvc\Entity\Finder
	 */
	public function findCurrentOrderForUser(?int $userId = null): \XF\Mvc\Entity\Finder
	{
		$userId = $userId === null ? \XF::visitor()->user_id : $userId;
		
		return $this->finder('DBTech\eCommerce:Order')
			->with('Address')
			->with('Address.Country')
			->where('order_state', 'pending')
			->where('user_id', $userId);
	}
	
	/**
	 * @param string|null $ip
	 *
	 * @return \XF\Mvc\Entity\Finder
	 */
	public function findCurrentOrderForGuest(?string $ip = null): \XF\Mvc\Entity\Finder
	{
		$ip = $ip === null ? $this->app()->request()->getIp() : $ip;
		
		return $this->finder('DBTech\eCommerce:Order')
			->where('order_state', 'pending')
			->where('user_id', 0)
			->where('ip_address', \XF\Util\Ip::convertIpStringToBinary($ip));
	}
	
	/**
	 * @param int|null $userId
	 *
	 * @return \XF\Mvc\Entity\Finder
	 */
	public function findOrdersAwaitingPayment(?int $userId = null): \XF\Mvc\Entity\Finder
	{
		$userId = $userId === null ? \XF::visitor()->user_id : $userId;
		
		return $this->finder('DBTech\eCommerce:Order')
			->with('Address')
			->with('Address.Country')
			->where('order_state', 'awaiting_payment')
			->where('user_id', $userId);
	}
	
	/**
	 * @param int $orderId
	 *
	 * @return \XF\Mvc\Entity\Finder
	 */
	public function findOrderItemsByOrder(int $orderId): \XF\Mvc\Entity\Finder
	{
		return $this->finder('DBTech\eCommerce:OrderItem')
			->with($this->getOrderItemWith())
			->where('order_id', $orderId);
	}
	
	/**
	 * @param int $userId
	 *
	 * @return \XF\Mvc\Entity\Finder
	 */
	public function findOrderItemsByUser(int $userId): \XF\Mvc\Entity\Finder
	{
		return $this->finder('DBTech\eCommerce:OrderItem')
			->with($this->getOrderItemWith())
			->where('order_state', 'pending')
			->where('user_id', $userId);
	}

	/**
	 * @param int $userId
	 *
	 * @return float
	 * @throws \Exception
	 */
	public function getSubTotalForUser(int $userId): float
	{
		$orderItems = $this->finder('DBTech\eCommerce:OrderItem')
			->with($this->getOrderItemWith())
			->where('order_state', 'pending')
			->where('user_id', $userId)
			->fetch();
		 
		$subTotal = 0.00;
		 
		/** @var \DBTech\eCommerce\Entity\OrderItem $orderItem */
		foreach ($orderItems as $orderItem)
		{
			$subTotal += $orderItem->getPrice();
		}
		
		return $subTotal;
	}
	
	/**
	 * @return array
	 */
	public function getOrderItemWith(): array
	{
		$visitor = \XF::visitor();
		
		return [
			'Product',
			'Product.Sale',
			'Product.Permissions|' . $visitor->permission_combination_id,
			'Product.Category',
			'Product.Category.Permissions|' . $visitor->permission_combination_id,
			'Cost',
			'License',
			'Coupon',
			'Coupon.Permissions|' . $visitor->permission_combination_id
		];
	}
	
	/**
	 * @param int|null $cutOff
	 */
	public function sendPendingOrderReminders(?int $cutOff = null)
	{
		$option = $this->options()->dbtechEcommerceOrderReminder;
		
		if (!$option['send_reminder'])
		{
			return;
		}
		
		if ($cutOff === null)
		{
			$cutOff = strtotime('-' . $option['inactive_length_amount'] . ' ' . $option['inactive_length_unit'], \XF::$time);
		}
		
		$orders = $this->finder('DBTech\eCommerce:Order')
			->where('order_date', '<', $cutOff)
			->where('order_state', 'pending')
			->where('sent_reminder', 0)
			->where('user_id', '!=', 0)
			->where('coupon_id', 0)
			->fetch(1000);
		
		/** @var \DBTech\eCommerce\Entity\Order $order */
		foreach ($orders AS $order)
		{
			$addedCoupon = false;
			
			if ($option['create_coupon'] && !$order->coupon_id)
			{
				/** @var \DBTech\eCommerce\Service\Coupon\Create $creator */
				$creator = $this->app()->service('DBTech\eCommerce:Coupon\Create');
				
				$creator->getCoupon()->bulkSet([
					'coupon_code'         => 'AUTOORDER' . $order->order_id,
					'coupon_type'         => 'percent',
					'coupon_percent'      => $option['coupon_percent'],
					'coupon_value'        => 0,
					'discount_excluded'   => false,
					'allow_auto_discount' => true,
					'remaining_uses'      => 1,
					'minimum_products'    => 0,
					'maximum_products'    => 0,
					'minimum_cart_value'  => 0,
					'maximum_cart_value'  => 0,
					'start_date'          => \XF::$time
				]);
				
				$creator->setTitle('AUTOORDER' . $order->order_id);
				$creator->setDuration($option['length_amount'], $option['length_unit']);
				
				$discounts = [];
				
				/** @var \DBTech\eCommerce\Entity\OrderItem $orderItem */
				foreach ($order->Items as $orderItem)
				{
					$discounts[] = [
						'product_id'    => $orderItem->product_id,
						'product_value' => 0,
					];
				}
				$creator->setProductDiscounts($discounts);
				
				if ($creator->validate())
				{
					/** @var \DBTech\eCommerce\Entity\Coupon $coupon */
					$coupon = $creator->save();
					
					// We need to manually trigger this
					$this->app()->jobManager()->runUnique('permissionRebuild', 2);
					
					$order->fastUpdate('coupon_id', $coupon->coupon_id);
					
					/** @var \DBTech\eCommerce\Entity\OrderItem $orderItem */
					foreach ($order->Items as $orderItem)
					{
						$orderItem->fastUpdate('coupon_id', $coupon->coupon_id);
					}
					
					$addedCoupon = true;
				}
			}
			
			$this->app()->jobManager()->enqueueUnique(
				'dbtEcomOrderReminder' . $order->order_id,
				'DBTech\eCommerce:OrderEmail',
				[
					'criteria' => [
						'no_empty_email' => true,
						'user_id' => $order->user_id,
						'Option'	=> [
							'dbtech_ecommerce_order_email_reminder' => true
						]
					],
					'order_id'  => $order->order_id,
					'has_coupon' => $addedCoupon
				],
				false
			);
		}
	}
	
	/**
	 * @param int|null $cutOff
	 */
	public function deleteOldPendingOrders(?int $cutOff = null)
	{
		$option = $this->options()->dbtechEcommerceOrderCleanUp;
		
		if (!$option['do_cleanup'])
		{
			return;
		}
		
		if ($cutOff === null)
		{
			$cutOff = strtotime('-' . $option['inactive_length_amount'] . ' ' . $option['inactive_length_unit'], \XF::$time);
		}
		
		$orders = $this->finder('DBTech\eCommerce:Order')
			->where('order_date', '<', $cutOff)
			->where('order_state', 'pending')
			->fetch(1000);
		foreach ($orders AS $order)
		{
			$order->delete();
		}
	}
	
	/**
	 * @param \XF\Entity\User $user
	 * @param $ip
	 *
	 * @throws \XF\PrintableException
	 */
	public function updateOrderUser(\XF\Entity\User $user, $ip)
	{
		/** @var \DBTech\eCommerce\Entity\Order $order */
		$order = $this->findCurrentOrderForGuest($ip)->fetchOne();
		if ($order)
		{
			$this->app()->response()->setCookie('dbtechEcommerceCartItems', false);
			
			/** @var \DBTech\eCommerce\Entity\Order $existingOrder */
			if ($existingOrder = $this->findCurrentOrderForUser($user->user_id)->fetchOne())
			{
				// User has existing order
				$this->db()->update(
					'xf_dbtech_ecommerce_order_item',
					['order_id' => $existingOrder->order_id],
					'order_id = ?',
					$order->order_id
				);
				
				$order->delete(false);
				
				// User has existing order
				$this->db()->update(
					'xf_dbtech_ecommerce_order_item',
					['user_id' => $user->user_id],
					'order_id = ?',
					$existingOrder->order_id
				);
				
				$user->rebuildDbtechEcommerceCartItems($existingOrder->order_id);
			}
			else
			{
				// We can do a simple update
				$order->fastUpdate('user_id', $user->user_id);
				
				// User has existing order
				$this->db()->update(
					'xf_dbtech_ecommerce_order_item',
					['user_id' => $user->user_id],
					'order_id = ?',
					$order->order_id
				);
				
				$user->rebuildDbtechEcommerceCartItems($order->order_id);
			}
		}
	}
	/**
	 * @param \DBTech\eCommerce\Entity\Order $order
	 * @param string $action
	 * @param string $reason
	 * @param array $extra
	 * @param \XF\Entity\User|null $forceUser
	 *
	 * @return bool
	 */
	public function sendModeratorActionAlert(
		\DBTech\eCommerce\Entity\Order $order,
		string $action,
		string $reason = '',
		array $extra = [],
		?\XF\Entity\User $forceUser = null
	): bool {
		if (!$forceUser)
		{
			if (!$order->user_id || !$order->User)
			{
				return false;
			}
			
			$forceUser = $order->User;
		}
		
		$extra = array_merge([
			'link' => $this->app()->router('public')->buildLink('nopath:dbtech-ecommerce/account/order', $order),
			'reason' => $reason,
			'depends_on_addon_id' => 'DBTech/eCommerce',
		], $extra);
		
		/** @var \XF\Repository\UserAlert $alertRepo */
		$alertRepo = $this->repository('XF:UserAlert');
		$alertRepo->alert(
			$forceUser,
			0,
			'',
			'user',
			$order->user_id,
			"dbt_ecom_order_{$action}",
			$extra
		);
		
		return true;
	}
}