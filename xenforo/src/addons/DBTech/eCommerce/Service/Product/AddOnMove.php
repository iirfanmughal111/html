<?php

namespace DBTech\eCommerce\Service\Product;

use DBTech\eCommerce\Entity\Product;

/**
 * Class AddOnMove
 *
 * @package DBTech\eCommerce\Service\Product
 */
class AddOnMove extends \XF\Service\AbstractService
{
	/**
	 * @var \DBTech\eCommerce\Entity\Product
	 */
	protected $product;

	/**
	 * @var bool
	 */
	protected $alert = false;
	/**
	 * @var string
	 */
	protected $alertReason = '';

	/**
	 * @var bool
	 */
	protected $notifyWatchers = false;

	/**
	 * @var null
	 */
	protected $prefixId;

	/**
	 * @var array
	 */
	protected $extraSetup = [];

	/**
	 * Move constructor.
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
	 * @param int|null $prefixId
	 *
	 * @return $this
	 */
	public function setPrefix(?int $prefixId): AddOnMove
	{
		$this->prefixId = $prefixId;

		return $this;
	}

	/**
	 * @param bool $alert
	 * @param string|null $reason
	 *
	 * @return $this
	 */
	public function setSendAlert(bool $alert, ?string $reason = null): AddOnMove
	{
		$this->alert = $alert;
		if ($reason !== null)
		{
			$this->alertReason = $reason;
		}

		return $this;
	}

	/**
	 * @param bool $value
	 *
	 * @return $this
	 */
	public function setNotifyWatchers(bool $value = true): AddOnMove
	{
		$this->notifyWatchers = (bool)$value;

		return $this;
	}

	/**
	 * @param callable $extra
	 */
	public function addExtraSetup(callable $extra)
	{
		$this->extraSetup[] = $extra;
	}
	
	/**
	 * @param Product $targetProduct
	 *
	 * @return bool
	 * @throws \LogicException
	 * @throws \Exception
	 * @throws \XF\PrintableException
	 */
	public function move(Product $targetProduct): bool
	{
		$user = \XF::visitor();

		$product = $this->product;

		$moved = ($product->parent_product_id != $targetProduct->product_id);

		foreach ($this->extraSetup AS $extra)
		{
			$extra($product, $targetProduct);
		}

		$product->parent_product_id = $targetProduct->product_id;
		if ($this->prefixId !== null)
		{
			$product->prefix_id = $this->prefixId;
		}
		
		if (!$targetProduct->Category->isPrefixUsable($product->prefix_id))
		{
			$product->prefix_id = 0;
		}

		if (!$product->preSave())
		{
			throw new \XF\PrintableException($product->getErrors());
		}

		$db = $this->db();
		$db->beginTransaction();

		$product->save(true, false);

		$db->commit();

		if ($moved && $this->alert && $product->user_id != $user->user_id && $product->isVisible())
		{
			/** @var \DBTech\eCommerce\Repository\Product $productRepo */
			$productRepo = $this->repository('DBTech\eCommerce:Product');
			$productRepo->sendModeratorActionAlert($this->product, 'addon_move', $this->alertReason);
		}

		return $moved;
	}
}