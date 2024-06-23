<?php

namespace DBTech\eCommerce\Service\Product;

use DBTech\eCommerce\Entity\Product;

/**
 * Class Approve
 *
 * @package DBTech\eCommerce\Service\Product
 */
class Approve extends \XF\Service\AbstractService
{
	/**
	 * @var Product
	 */
	protected $product;
	
	/**
	 * @var int
	 */
	protected $notifyRunTime = 3;
	
	/**
	 * Approve constructor.
	 *
	 * @param \XF\App $app
	 * @param Product $product
	 */
	public function __construct(\XF\App $app, Product $product)
	{
		parent::__construct($app);
		$this->product = $product;
	}
	
	/**
	 * @return Product
	 */
	public function getProduct(): Product
	{
		return $this->product;
	}

	/**
	 * @param int $time
	 *
	 * @return $this
	 */
	public function setNotifyRunTime(int $time): Approve
	{
		$this->notifyRunTime = $time;

		return $this;
	}
	
	/**
	 * @return bool
	 * @throws \LogicException
	 * @throws \Exception
	 * @throws \XF\PrintableException
	 */
	public function approve(): bool
	{
		if ($this->product->product_state == 'moderated')
		{
			$this->product->product_state = 'visible';
			$this->product->save();

			$this->onApprove();
			return true;
		}
		
		return false;
	}

	protected function onApprove()
	{
		$latestVersion = $this->product->LatestVersion;

		if ($latestVersion)
		{
			/** @var \DBTech\eCommerce\Service\Download\Notify $notifier */
			$notifier = $this->service('DBTech\eCommerce:Download\Notify', $latestVersion, 'product');
			$notifier->notifyAndEnqueue($this->notifyRunTime);
		}
	}
}