<?php

namespace DBTech\eCommerce\ProductType;

use DBTech\eCommerce\Entity\OrderItem;
use DBTech\eCommerce\Entity\Product;
use XF\Entity\PurchaseRequest;
use XF\Entity\User;

/**
 * Class AbstractHandler
 *
 * @package DBTech\eCommerce\ProductType
 */
abstract class AbstractHandler
{
	/** @var string */
	protected $productType;

	/** @var array */
	protected $options = [];

	/** @var array */
	protected $defaultOptions = [
		'addons'    => false,
		'licenses'  => false,
		'downloads' => false,
		'quantity'  => false,
		'shipping'  => false,
		'stock'     => false,
		'weight'    => false,
	];


	/**
	 * AbstractHandler constructor.
	 *
	 * @param string $productType
	 */
	public function __construct(string $productType)
	{
		$this->productType = $productType;
		$this->options = array_replace($this->defaultOptions, $this->options);
	}

	/**
	 * @param string $functionality
	 *
	 * @return bool
	 */
	public function hasFunctionality(string $functionality): bool
	{
		if (!array_key_exists($functionality, $this->options))
		{
			throw new \LogicException("$functionality is not a valid option.");
		}

		return $this->options[$functionality];
	}

	/**
	 * @param \DBTech\eCommerce\Entity\Product $product
	 *
	 * @return bool
	 */
	public function canPurchase(Product $product): bool
	{
		return true;
	}

	/**
	 * @param \DBTech\eCommerce\Entity\Product $product
	 * @param \DBTech\eCommerce\Entity\OrderItem $orderItem
	 *
	 * @return bool
	 */
	public function orderComplete(Product $product, OrderItem $orderItem): bool
	{
		return true;
	}

	/**
	 * @param \DBTech\eCommerce\Entity\Product $product
	 * @param \DBTech\eCommerce\Entity\OrderItem $orderItem
	 */
	public function orderReversed(Product $product, OrderItem $orderItem)
	{
	}

	/**
	 * @param \DBTech\eCommerce\Entity\Product $product
	 * @param string $context
	 * @param string $linkPrefix
	 *
	 * @return string
	 */
	public function renderOptions(Product $product, string $context, string $linkPrefix): string
	{
		return '';
	}

	/**
	 * @param \XF\Entity\User $purchaser
	 * @param \XF\Entity\PurchaseRequest $purchaseRequest
	 * @param \DBTech\eCommerce\Entity\OrderItem $orderItem
	 * @param array $params
	 */
	public function sendPaymentReceipt(
		User $purchaser,
		PurchaseRequest $purchaseRequest,
		OrderItem $orderItem,
		array $params = []
	) {
	}

	/**
	 * @return string
	 */
	public function getProductType(): string
	{
		return $this->productType;
	}

	/**
	 * @return \XF\Phrase
	 */
	public function getProductTypePhrase(): \XF\Phrase
	{
		return \XF::app()->getContentTypePhrase($this->productType);
	}
}