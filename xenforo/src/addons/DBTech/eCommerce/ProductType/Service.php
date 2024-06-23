<?php

namespace DBTech\eCommerce\ProductType;

use DBTech\eCommerce\Entity\OrderItem;
use DBTech\eCommerce\Entity\Product;
use XF\Entity\PurchaseRequest;
use XF\Entity\User;

/**
 * Class Service
 *
 * @package DBTech\eCommerce\ProductType
 */
class Service extends AbstractHandler
{
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
		return \XF::app()->templater()->renderTemplate('public:dbtech_ecommerce_product_edit_service', $params);
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

		$mail->setTemplate('dbtech_ecommerce_purchase_alert_service', $params);
		$mail->send();
	}
}