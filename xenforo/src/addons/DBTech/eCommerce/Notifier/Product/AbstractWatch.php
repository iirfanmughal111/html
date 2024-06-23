<?php

namespace DBTech\eCommerce\Notifier\Product;

use XF\Notifier\AbstractNotifier;

/**
 * Class AbstractWatch
 *
 * @package DBTech\eCommerce\Notifier\Product
 */
abstract class AbstractWatch extends AbstractNotifier
{
	/** @var \DBTech\eCommerce\Entity\Product */
	protected $product;

	/** @var bool */
	protected $isApplicable;


	abstract protected function getDefaultWatchNotifyData();
	abstract protected function getWatchEmailTemplateName();


	/**
	 * AbstractWatch constructor.
	 *
	 * @param \XF\App $app
	 * @param \DBTech\eCommerce\Entity\Product $product
	 */
	public function __construct(\XF\App $app, \DBTech\eCommerce\Entity\Product $product)
	{
		parent::__construct($app);

		$this->product = $product;
		$this->isApplicable = $this->isApplicable();
	}

	/**
	 * @return bool
	 */
	protected function isApplicable(): bool
	{
		if (!$this->product->isVisible())
		{
			return false;
		}

		return true;
	}

	/**
	 * @param \XF\Entity\User $user
	 *
	 * @return bool
	 */
	public function canNotify(\XF\Entity\User $user): bool
	{
		if (!$this->isApplicable)
		{
			return false;
		}

		$product = $this->product;
		
		return !($user->user_id == $product->user_id || $user->isIgnoring($product->user_id));
	}

	/**
	 * @param \XF\Entity\User $user
	 *
	 * @return mixed
	 */
	public function sendAlert(\XF\Entity\User $user)
	{
		$product = $this->product;

		return $this->basicAlert(
			$user,
			$product->user_id,
			$product->username,
			'dbtech_ecommerce_product',
			$product->product_id,
			'insert',
			['depends_on_addon_id' => 'DBTech/eCommerce']
		);
	}

	/**
	 * @param \XF\Entity\User $user
	 *
	 * @return bool
	 */
	public function sendEmail(\XF\Entity\User $user): bool
	{
		if (!$user->email || $user->user_state != 'valid')
		{
			return false;
		}

		$product = $this->product;

		$params = [
			'product' => $product,
			'category' => $product->Category,
			'receiver' => $user
		];

		$template = $this->getWatchEmailTemplateName();

		$this->app()->mailer()->newMail()
			->setToUser($user)
			->setTemplate($template, $params)
			->queue();

		return true;
	}

	/**
	 * @return array
	 */
	public function getDefaultNotifyData(): array
	{
		if (!$this->isApplicable)
		{
			return [];
		}

		return $this->getDefaultWatchNotifyData();
	}
}