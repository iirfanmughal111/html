<?php

namespace DBTech\eCommerce\Notifier\Product;

use XF\Notifier\AbstractNotifier;

/**
 * Class Mention
 *
 * @package DBTech\eCommerce\Notifier\Product
 */
class Mention extends AbstractNotifier
{
	/** @var \DBTech\eCommerce\Entity\Product */
	protected $product;

	/**
	 * Mention constructor.
	 *
	 * @param \XF\App $app
	 * @param \DBTech\eCommerce\Entity\Product $product
	 */
	public function __construct(\XF\App $app, \DBTech\eCommerce\Entity\Product $product)
	{
		parent::__construct($app);

		$this->product = $product;
	}

	/**
	 * @param \XF\Entity\User $user
	 *
	 * @return bool
	 */
	public function canNotify(\XF\Entity\User $user): bool
	{
		return ($this->product->isVisible() && $user->user_id != $this->product->user_id);
	}

	/**
	 * @param \XF\Entity\User $user
	 *
	 * @return bool
	 */
	public function sendAlert(\XF\Entity\User $user): bool
	{
		$product = $this->product;

		return $this->basicAlert(
			$user,
			$product->user_id,
			$product->username,
			'dbtech_ecommerce_product',
			$product->product_id,
			'mention',
			['depends_on_addon_id' => 'DBTech/eCommerce']
		);
	}
}