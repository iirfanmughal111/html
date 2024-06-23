<?php

namespace DBTech\eCommerce\ProductType;

use DBTech\eCommerce\Entity\OrderItem;
use DBTech\eCommerce\Entity\Product;
use XF\Entity\PurchaseRequest;
use XF\Entity\User;

/**
 * Class Key
 *
 * @package DBTech\eCommerce\ProductType
 */
class Key extends AbstractHandler
{
	/** @var array */
	protected $options = [
		'licenses'  => true,
	];

	/**
	 * @inheritDoc
	 */
	public function renderOptions(Product $product, string $context, string $linkPrefix): string
	{
		$params = [
			'product'    => $product,
			'context'    => $context,
			'linkPrefix' => $linkPrefix
		];
		return \XF::app()->templater()->renderTemplate('public:dbtech_ecommerce_product_edit_key', $params);
	}

	/**
	 * @inheritDoc
	 */
	public function canPurchase(Product $product): bool
	{
		if (empty($product->product_type_data['serial_key_formula'])
			&& empty($product->product_type_data['serial_key_list'])
		) {
			// This is a key list, but there's no available keys
			return false;
		}

		return true;
	}

	/**
	 * @inheritDoc
	 */
	public function sendPaymentReceipt(
		User $purchaser,
		PurchaseRequest $purchaseRequest,
		OrderItem $orderItem,
		array $params = []
	) {
		if (!$purchaser->email)
		{
			return;
		}

		$mail = \XF::app()
			->mailer()
			->newMail()
			->setToUser($purchaser)
		;

		$params['item'] = $orderItem;

		$serialKey = \XF::em()->findOne('DBTech\eCommerce:SerialKey', [
			'license_id' => $orderItem->license_id
		]);
		$params['serialKey'] = $serialKey;

		$mail->setTemplate('dbtech_ecommerce_purchase_alert_key', $params);
		$mail->send();
	}
}