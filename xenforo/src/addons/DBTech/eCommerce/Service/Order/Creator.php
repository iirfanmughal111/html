<?php

namespace DBTech\eCommerce\Service\Order;

use DBTech\eCommerce\Entity\Order;
use DBTech\eCommerce\Entity\OrderItem;
use DBTech\eCommerce\Entity\Product;
use DBTech\eCommerce\Entity\ProductCost;
use DBTech\eCommerce\Entity\License;
use XF\Mvc\Entity\ArrayCollection;

/**
 * Class Creator
 *
 * @package DBTech\eCommerce\Service\Order
 */
class Creator extends \XF\Service\AbstractService
{
	use \XF\Service\ValidateAndSavableTrait;

	/** @var \DBTech\eCommerce\Entity\Order */
	protected $order;

	/** @var OrderItem[] */
	protected $orderItems = [];

	/** @var \XF\Entity\User|null */
	protected $user;


	/**
	 * Creator constructor.
	 *
	 * @param \XF\App $app
	 * @param \DBTech\eCommerce\Entity\Order|null $order
	 *
	 * @throws \XF\PrintableException
	 */
	public function __construct(\XF\App $app, ?Order $order = null)
	{
		parent::__construct($app);
		$this->setUser(\XF::visitor());
		
		if ($order)
		{
			$this->setOrder($order);
		}
		
		$this->setDefaults();
	}
	
	/**
	 * @throws \LogicException
	 * @throws \Exception
	 * @throws \XF\PrintableException
	 */
	protected function setDefaults()
	{
		if (!$this->order)
		{
			/** @var \DBTech\eCommerce\Repository\Order $orderRepo */
			$orderRepo = $this->repository('DBTech\eCommerce:Order');
	
			if ($this->user->user_id)
			{
				$this->order = $orderRepo->findCurrentOrderForUser($this->user->user_id)->fetchOne();
			}
			else
			{
				$this->order = $orderRepo->findCurrentOrderForGuest($this->app->request()->getIp())->fetchOne();
			}
	
			if (!$this->order)
			{
				$this->order = $this->em()->create('DBTech\eCommerce:Order');
				$this->order->order_date = \XF::$time;
				$this->order->ip_address = $this->app->request()->getIp();
				$this->order->user_id = $this->user->user_id;
				$this->order->save();
			}
		}
	}

	/**
	 * @param \DBTech\eCommerce\Entity\Order $order
	 *
	 * @return $this
	 */
	protected function setOrder(Order $order): Creator
	{
		$this->order = $order;

		return $this;
	}

	/**
	 * @return \DBTech\eCommerce\Entity\Order
	 */
	public function getOrder(): Order
	{
		return $this->order;
	}

	/**
	 * @param \XF\Entity\User|null $user
	 *
	 * @return $this
	 */
	public function setUser(?\XF\Entity\User $user = null): Creator
	{
		$this->user = $user;

		return $this;
	}

	/**
	 * @return null|\XF\Entity\User
	 */
	public function getUser(): ?\XF\Entity\User
	{
		return $this->user;
	}

	/**
	 * @param \DBTech\eCommerce\Entity\Product $product
	 * @param \DBTech\eCommerce\Entity\ProductCost|null $cost
	 * @param \DBTech\eCommerce\Entity\License|null $license
	 * @param \DBTech\eCommerce\Entity\License|null $parentLicense
	 * @param int $quantity
	 *
	 * @return $this
	 * @throws \Exception
	 */
	public function addItem(
		Product $product,
		?ProductCost $cost = null,
		?License $license = null,
		?License $parentLicense = null,
		int $quantity = 1
	): Creator {
		if ($product->hasQuantityFunctionality() && $cost)
		{
			// Quantity functionality means it should be treated as "unique"
			// so check if it's already added

			/** @var \DBTech\eCommerce\Entity\OrderItem $orderItem */
			foreach ($this->order->Items as $orderItem)
			{
				if ($orderItem->product_cost_id === $cost->product_cost_id)
				{
					// We found a matching product, add quantity then return
					$orderItem->quantity += $quantity;

					return $this;
				}
			}
		}

		$this->orderItems[] = $this->order->getNewOrderItem($product, $cost, $license, $parentLicense, $quantity);

		return $this;
	}

	/**
	 *
	 */
	protected function finalSetup()
	{
		$this->order->ip_address = $this->app->request()->getIp();
		$this->order->order_date = \XF::$time;
	}

	/**
	 * @return array
	 * @throws \Exception
	 */
	protected function _validate(): array
	{
		$this->finalSetup();

		$this->order->preSave();
		$errors = $this->order->getErrors();

		$renewals = [];

		/** @var \DBTech\eCommerce\Entity\OrderItem $item */
		foreach ($this->order->Items as $item)
		{
			if ($item->license_id)
			{
				$renewals[] = $item->license_id;
			}
		}

		foreach ($this->order->Items as $orderItem)
		{
			if ($orderItem->Product->hasStockFunctionality())
			{
				if ($orderItem->Cost->stock <= 0)
				{
					$errors[] = \XF::phrase('dbtech_ecommerce_selected_variation_out_of_stock');
				}
				elseif ($orderItem->Cost->stock < $orderItem->quantity)
				{
					$errors[] = \XF::phrase('dbtech_ecommerce_selected_variation_only_has_x_in_stock', [
						'available' => \XF::language()->numberFormat($orderItem->Cost->stock),
					]);
				}
			}

			$orderItem->preSave();
			$errors = array_merge($errors, $orderItem->getErrors());
		}

		foreach ($this->orderItems as $orderItem)
		{
			if (!$orderItem->Product || !$orderItem->Product->canView() || !$orderItem->Product->canPurchase($orderItem->License))
			{
				$errors[] = \XF::phrase('dbtech_ecommerce_cannot_purchase_product');
			}

			if ($orderItem->license_id && in_array($orderItem->license_id, $renewals))
			{
				$errors[] = \XF::phrase('dbtech_ecommerce_cannot_renew_license_twice');
			}

			if ($orderItem->Product->hasStockFunctionality())
			{
				if ($orderItem->Cost->stock <= 0)
				{
					$errors[] = \XF::phrase('dbtech_ecommerce_selected_variation_out_of_stock');
				}
				elseif ($orderItem->Cost->stock < $orderItem->quantity)
				{
					$errors[] = \XF::phrase('dbtech_ecommerce_selected_variation_only_has_x_in_stock', [
						'available' => \XF::language()->numberFormat($orderItem->Cost->stock),
					]);
				}
			}

			$orderItem->preSave();
			$errors = array_merge($errors, $orderItem->getErrors());
		}

		return $errors;
	}
	
	/**
	 * @return Order
	 * @throws \LogicException
	 * @throws \Exception
	 * @throws \XF\PrintableException
	 */
	protected function _save(): Order
	{
		$order = $this->order;
		$order->save();

		foreach ($this->order->Items as $orderItem)
		{
			$orderItem->saveIfChanged();
		}

		foreach ($this->orderItems as $orderItem)
		{
			$orderItem->save();
		}
		
		$orderItems = new ArrayCollection($this->orderItems);
		$orderItems = $orderItems->filter(function (OrderItem $orderItem): ?OrderItem
		{
			if ($orderItem->parent_license_id || !$orderItem->Product->hasLicenseFunctionality())
			{
				return null;
			}
			
			return $orderItem;
		})->groupBy(function (OrderItem $orderItem): int
		{
			return $orderItem->Product->parent_product_id;
		});
		
		if (!empty($orderItems[0]))
		{
			/** @var OrderItem $parentProduct */
			foreach ($orderItems[0] as $parentProduct)
			{
				if (!empty($orderItems[$parentProduct->product_id]))
				{
					/** @var OrderItem $childProduct */
					foreach ($orderItems[$parentProduct->product_id] as $childProduct)
					{
						$childProduct->fastUpdate('parent_order_item_id', $parentProduct->order_item_id);
					}
				}
			}
		}

		return $order;
	}
}