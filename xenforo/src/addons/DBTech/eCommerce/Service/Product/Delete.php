<?php

namespace DBTech\eCommerce\Service\Product;

use DBTech\eCommerce\Entity\Product;

/**
 * Class Delete
 *
 * @package DBTech\eCommerce\Service\Product
 */
class Delete extends \XF\Service\AbstractService
{
	/** @var \DBTech\eCommerce\Entity\Product */
	protected $product;

	/** @var \XF\Entity\User|null */
	protected $user;
	
	/** @var bool */
	protected $alert = false;

	/** @var string */
	protected $alertReason = '';
	
	/**
	 * Delete constructor.
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
	 * @return \DBTech\eCommerce\Entity\Product
	 */
	public function getProduct(): Product
	{
		return $this->product;
	}

	/**
	 * @param \XF\Entity\User|null $user
	 *
	 * @return $this
	 */
	public function setUser(?\XF\Entity\User $user = null): Delete
	{
		$this->user = $user;

		return $this;
	}

	/**
	 * @return \XF\Entity\User|null
	 */
	public function getUser(): ?\XF\Entity\User
	{
		return $this->user;
	}

	/**
	 * @param bool $alert
	 * @param string|null $reason
	 *
	 * @return $this
	 */
	public function setSendAlert(bool $alert, ?string $reason = null): Delete
	{
		$this->alert = $alert;
		if ($reason !== null)
		{
			$this->alertReason = $reason;
		}

		return $this;
	}
	
	/**
	 * @param string $type
	 * @param string $reason
	 *
	 * @return bool
	 * @throws \InvalidArgumentException
	 * @throws \LogicException
	 * @throws \Exception
	 * @throws \XF\PrintableException
	 */
	public function delete(string $type, string $reason = ''): bool
	{
		$user = $this->user ?: \XF::visitor();
		$wasVisible = $this->product->isVisible();

		if ($type == 'soft')
		{
			$result = $this->product->softDelete($reason, $user);
		}
		else
		{
			$result = $this->product->delete();
		}

		if ($result && $wasVisible && $this->alert && $this->product->user_id != $user->user_id)
		{
			/** @var \DBTech\eCommerce\Repository\Product $productRepo */
			$productRepo = $this->repository('DBTech\eCommerce:Product');
			$productRepo->sendModeratorActionAlert($this->product, 'delete', $this->alertReason);
		}

		return $result;
	}
	
	/**
	 * @return bool
	 * @throws \LogicException
	 * @throws \Exception
	 * @throws \XF\PrintableException
	 */
	public function unDelete(): bool
	{
		$user = $this->user ?: \XF::visitor();
		$wasDeleted = $this->product->product_state == 'deleted';
		
		$result = $this->product->unDelete($user);
		
		if ($result && $wasDeleted && $this->alert && $this->product->user_id != $user->user_id)
		{
			/** @var \DBTech\eCommerce\Repository\Product $productRepo */
			$productRepo = $this->repository('DBTech\eCommerce:Product');
			$productRepo->sendModeratorActionAlert($this->product, 'undelete', $this->alertReason);
		}
		
		return $result;
	}
}