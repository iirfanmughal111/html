<?php

namespace DBTech\eCommerce\ProductType;

use DBTech\eCommerce\Entity\Product;

/**
 * Class Digital
 *
 * @package DBTech\eCommerce\ProductType
 */
class Digital extends AbstractHandler
{
	/** @var array */
	protected $options = [
		'addons'    => true,
		'licenses'  => true,
		'downloads' => true,
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
		return \XF::app()->templater()->renderTemplate('public:dbtech_ecommerce_product_edit_digital', $params);
	}
}