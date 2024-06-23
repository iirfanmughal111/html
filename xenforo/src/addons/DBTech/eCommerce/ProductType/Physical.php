<?php

namespace DBTech\eCommerce\ProductType;

use DBTech\eCommerce\Entity\OrderItem;
use DBTech\eCommerce\Entity\Product;
use XF\Entity\PurchaseRequest;
use XF\Entity\User;

/**
 * Class Physical
 *
 * @package DBTech\eCommerce\ProductType
 */
class Physical extends AbstractHandler
{
	/** @var array */
	protected $options = [
		'quantity'  => true,
		'shipping'  => true,
		'stock'     => true,
		'weight'    => true,
	];


	/**
	 * @inheritDoc
	 */
	public function renderOptions(Product $product, string $context, string $linkPrefix): string
	{
		$params = [
			'product'       => $product,
			'shippingZones' => $product->hasShippingFunctionality()
				? $this->getShippingZoneRepo()->getShippingZoneTitlePairs()
				: []
			,
			'context'       => $context,
			'linkPrefix'    => $linkPrefix
		];
		return \XF::app()->templater()->renderTemplate('public:dbtech_ecommerce_product_edit_physical', $params);
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
		$options = \XF::options();

		$mail = \XF::app()
			->mailer()
			->newMail()
		;

		if ($purchaser->user_id)
		{
			if ($options->contactEmailSenderHeader)
			{
				$senderEmail = $options->contactEmailAddress
					?: $options->defaultEmailAddress;

				$mail->setSender($senderEmail)
					->setFrom($purchaser->email, $purchaser->username)
				;
			}
			elseif ($purchaser->email)
			{
				$mail->setReplyTo($purchaser->email, $purchaser->username);
			}
		}
		elseif ($orderItem->Order->ShippingAddress->email)
		{
			$mail->setReplyTo($orderItem->Order->ShippingAddress->email);
		}

		if (
			$options->dbtechEcommerceShippingAlert == 'seller'
			&& $orderItem->Product->User
			&& $orderItem->Product->User->email
		) {
			$mail->setToUser($orderItem->Product->User);
		}
		else
		{
			$mail->setTo($options->contactEmailAddress);
		}

		$params['item'] = $orderItem;

		$mail->setTemplate('dbtech_ecommerce_purchase_alert', $params);
		$mail->send();
	}

	/**
	 * @return \DBTech\eCommerce\Repository\ShippingZone|\XF\Mvc\Entity\Repository
	 */
	protected function getShippingZoneRepo()
	{
		return \XF::repository('DBTech\eCommerce:ShippingZone');
	}
}