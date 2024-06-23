<?php

namespace DBTech\eCommerce\Import\DataHelper;

use XF\Import\DataHelper\AbstractHelper;

/**
 * Class Order
 *
 * @package DBTech\eCommerce\Import\DataHelper
 */
class Order extends AbstractHelper
{
	/**
	 * @param array $purchase
	 *
	 * @return \XF\Mvc\Entity\Entity
	 * @throws \Exception
	 * @throws \XF\PrintableException
	 */
	public function insertPurchaseRequest(array $purchase)
	{
		/** @var \XF\Repository\Purchase $purchaseRepo */
		$purchaseRepo = \XF::repository('XF:Purchase');
		
		$providerId = !empty($purchase['provider_id']) ? $purchase['provider_id'] : '';
		
		/** @var \XF\Entity\PaymentProfile $paymentProfile */
		$paymentProfile = \XF::finder('XF:PaymentProfile')->where('provider_id', $providerId)->fetchOne();
		
		/** @var \XF\Entity\PurchaseRequest $purchaseRequest */
		$purchaseRequest = $this->em()->create('XF:PurchaseRequest');
		
		$purchaseRequest->request_key = $purchaseRepo->generateRequestKey();
		$purchaseRequest->user_id = $purchase['user_id'];
		$purchaseRequest->provider_id = $providerId;
		$purchaseRequest->payment_profile_id = $paymentProfile ? $paymentProfile->payment_profile_id : 0;
		$purchaseRequest->purchasable_type_id = 'dbtech_ecommerce_order';
		$purchaseRequest->cost_amount = $purchase['cost'];
		$purchaseRequest->cost_currency = $purchase['currency'];
		$purchaseRequest->extra_data = ['order_id' => $purchase['order_id']];
		
		$purchaseRequest->save();
		
		$this->db()->update(
			'xf_dbtech_ecommerce_order',
			['purchase_request_key' => $purchaseRequest->request_key],
			'order_id = ?',
			$purchase['order_id']
		);
		
		return $purchaseRequest;
	}
	
	/**
	 * @param $orderId
	 * @param array $itemInfo
	 */
	public function importOrderItem($orderId, array $itemInfo)
	{
		$this->importOrderItemBulk($orderId, [$itemInfo]);
	}
	
	/**
	 * @param $orderId
	 * @param array $itemConfigs
	 */
	public function importOrderItemBulk($orderId, array $itemConfigs)
	{
		$insert = [];

		foreach ($itemConfigs AS $config)
		{
			$itemType = 'new';
			if (!empty($config['upgradetype']))
			{
				$itemType = 'upgrade';
			}
			elseif (!empty($config['license_id']))
			{
				$itemType = 'renew';
			}
			
			$insert[] = [
				'order_id' => $orderId,
				'user_id' => $config['user_id'],
				'product_id' => $config['product_id'],
				'product_cost_id' => $config['product_cost_id'],
				'parent_order_item_id' => 0,
				'shipping_method_id' => $config['shipping_method_id'],
				'license_id' => $config['license_id'],
				'parent_license_id' => 0,
				'coupon_id' => $config['coupon_id'],
				'item_type' => $itemType,
				'product_fields' => json_encode([]),
				'base_price' => isset($config['extra_data']['base_price']) ? $config['extra_data']['base_price'] : 0.00,
				'sale_discount' => isset($config['extra_data']['sale_discount']) ? $config['extra_data']['sale_discount'] : 0.00,
				'coupon_discount' => isset($config['extra_data']['coupon_discount']) ? $config['extra_data']['coupon_discount'] : 0.00,
				'shipping_cost' => isset($config['extra_data']['shipping_cost']) ? $config['extra_data']['shipping_cost'] : 0.00,
				'taxable_price' => isset($config['extra_data']['taxable_price']) ? $config['extra_data']['taxable_price'] : 0.00,
				'sales_tax' => isset($config['extra_data']['sales_tax']) ? $config['extra_data']['sales_tax'] : 0.00,
				'price' => isset($config['extra_data']['price']) ? $config['extra_data']['price'] : 0.00,
				'currency' => isset($config['extra_data']['currency']) ? $config['extra_data']['currency'] : 'USD',
				'extra_data' => json_encode($config['extra_data'])
			];
		}

		if ($insert)
		{
			$this->db()->insertBulk(
				'xf_dbtech_ecommerce_order_item',
				$insert,
				false,
				'
					order_id = VALUES(order_id),
					user_id = VALUES(user_id),
					product_id = VALUES(product_id),
					product_cost_id = VALUES(product_cost_id),
					shipping_method_id = VALUES(shipping_method_id),
					license_id = VALUES(license_id),
					parent_license_id = VALUES(parent_license_id),
					coupon_id = VALUES(coupon_id),
					item_type = VALUES(item_type),
					product_fields = VALUES(product_fields),
					base_price = VALUES(base_price),
					sale_discount = VALUES(sale_discount),
					shipping_cost = VALUES(shipping_cost),
					taxable_price = VALUES(taxable_price),
					sales_tax = VALUES(sales_tax),
					price = VALUES(price),
					currency = VALUES(currency),
					extra_data = VALUES(extra_data)
				'
			);
		}
	}
}