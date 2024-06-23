<?php

namespace DBTech\eCommerce\Service\Order;

use XF\Service\User\UserGroupChange;

/**
 * Class Reverse
 *
 * @package DBTech\eCommerce\Service\Order
 */
class Reverse extends \XF\Service\AbstractService
{
	/** @var \XF\Entity\User */
	protected $user;
	
	/** @var string */
	protected $purchaseRequestKey;
	
	/** @var string */
	protected $transactionId;

	/** @var \DBTech\eCommerce\Entity\Order */
	protected $order;
	
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
	public function setPurchaseRequestKey(string $purchaseRequestKey): Reverse
	{
		$this->purchaseRequestKey = $purchaseRequestKey;

		return $this;
	}

	/**
	 * @param string $transactionId
	 *
	 * @return $this
	 */
	public function setTransactionId(string $transactionId): Reverse
	{
		$this->transactionId = $transactionId;

		return $this;
	}

	/**
	 * @param \DBTech\eCommerce\Entity\Order $order
	 *
	 * @return $this
	 */
	protected function setOrder(\DBTech\eCommerce\Entity\Order $order): Reverse
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
	 * @param array $extraData
	 *
	 * @return $this
	 */
	public function setExtraData(array $extraData): Reverse
	{
		$this->extraData = $extraData;

		return $this;
	}
	
	/**
	 * @return bool
	 * @throws \InvalidArgumentException
	 * @throws \LogicException
	 * @throws \Exception
	 * @throws \XF\PrintableException
	 */
	public function reverse(): bool
	{
		$user = $this->user;
		$order = $this->order;
		
		$db = $this->db();
		$db->beginTransaction();
		
		/** @var \XF\Repository\Ip $ipRepo */
		$ipRepo = $this->repository('XF:Ip');
		
		/** @var \DBTech\eCommerce\Entity\OrderItem $orderItem */
		foreach ($order->Items as $orderItem)
		{
			if ($orderItem->Product)
			{
				if ($orderItem->Product->hasLicenseFunctionality())
				{
					if ($orderItem->License)
					{
						/** @var \DBTech\eCommerce\Entity\License $license */
						$license = $orderItem->License;
						
						/** @var \DBTech\eCommerce\Service\License\Delete $deleter */
						$deleter = \XF::app()
							->service('DBTech\eCommerce:License\Delete', $license)
						;
						$deleter->setUser($user);
						$deleter->delete('soft', 'Order #' . $order->order_id . ' reversed.');

						$this->app->fire('dbtech_ecommerce_license_reverse', [$license, $order, $orderItem]);
					}
				}
				/*
				elseif ($orderItem->Product->hasStockFunctionality())
				{
					// Put stock back up - not sure if we want to do this. Option later?
					$orderItem->Cost->increaseStock($orderItem->quantity);
				}
				*/
			}

			/** @var \DBTech\eCommerce\Entity\Product $product */
			$product = $orderItem->Product;

			$product->purchaseRemoved();
			$product->save();

			$handler = $product->getHandler();
			if ($handler)
			{
				$handler->orderReversed($product, $orderItem);
			}
			
			/** @var \DBTech\eCommerce\Entity\PurchaseLog $purchaseLog */
			$purchaseLog = $this->em()->create('DBTech\eCommerce:PurchaseLog');
			$purchaseLog->order_id = $order->order_id;
			$purchaseLog->order_item_id = $orderItem->order_item_id;
			$purchaseLog->product_id = $orderItem->product_id;
			$purchaseLog->license_id = $orderItem->license_id;
			$purchaseLog->quantity = $orderItem->quantity;
			$purchaseLog->user_id = $user->user_id;
			$purchaseLog->cost_amount = ($orderItem->price * -1);
			$purchaseLog->currency = $order->currency;
			$purchaseLog->log_type = 'reversal';
			$purchaseLog->log_details = array_merge($orderItem->extra_data, $this->extraData);
			$purchaseLog->save(true, false);
			
			$ipEnt = $ipRepo->logIp($user->user_id, $order->ip_address, 'dbtech_ecommerce_purchase', $purchaseLog->purchase_log_id, 'purchase');
			if ($ipEnt)
			{
				$purchaseLog->fastUpdate('ip_id', $ipEnt->ip_id);
			}
			
			/** @var UserGroupChange $userGroupChange */
			$userGroupChange = $this->service('XF:User\UserGroupChange');
			$userGroupChange->removeUserGroupChange(
				$user->user_id,
				'dbtechEcommerce-' . $order->order_id . '-' . $orderItem->license_id
			);
		}
		
		$order->order_state = 'reversed';
		if (!$order->save(true, false))
		{
			$db->rollback();
			return false;
		}
		
		$this->afterReverse();
		
		$db->commit();
		
		return true;
	}
	
	/**
	 *
	 */
	protected function afterReverse()
	{
	}
	
	/**
	 * @return bool
	 * @throws \InvalidArgumentException
	 * @throws \LogicException
	 * @throws \Exception
	 * @throws \XF\PrintableException
	 */
	public function delete(): bool
	{
		$order = $this->order;
		
		$db = $this->db();
		$db->beginTransaction();
		
		if (!$order->delete(true, false))
		{
			$db->rollback();
			return false;
		}
		
		$db->commit();
		
		return true;
	}
}