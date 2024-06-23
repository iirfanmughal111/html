<?php /** @noinspection PhpMissingReturnTypeInspection */

namespace DBTech\eCommerce\XF\Payment;

use XF\Entity\PurchaseRequest;
use XF\Purchasable\Purchase;

/**
 * Class Register
 *
 * @package DBTech\eCommerce\XF\Payment
 */
class PayPal extends XFCP_PayPal
{
	/**
	 * @param PurchaseRequest $purchaseRequest
	 * @param Purchase $purchase
	 *
	 * @return array
	 */
	protected function getPaymentParams(PurchaseRequest $purchaseRequest, Purchase $purchase)
	{
		$params = parent::getPaymentParams($purchaseRequest, $purchase);
		
		if (isset($purchase->extraData['tax_amount']))
		{
			$params['tax'] = $purchase->extraData['tax_amount'];
		}
		
		return $params;
	}
}