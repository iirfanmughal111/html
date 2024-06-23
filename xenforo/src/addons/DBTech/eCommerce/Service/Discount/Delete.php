<?php

namespace DBTech\eCommerce\Service\Discount;

use DBTech\eCommerce\Entity\Discount;

/**
 * Class Delete
 *
 * @package DBTech\eCommerce\Service\Discount
 */
class Delete extends \XF\Service\AbstractService
{
	/** @var \DBTech\eCommerce\Entity\Discount */
	protected $discount;

	/** @var \XF\Entity\User|null */
	protected $user;
	

	/**
	 * Delete constructor.
	 *
	 * @param \XF\App $app
	 * @param Discount $discount
	 */
	public function __construct(\XF\App $app, Discount $discount)
	{
		parent::__construct($app);
		$this->discount = $discount;
	}

	/**
	 * @return Discount
	 */
	public function getDiscount(): Discount
	{
		return $this->discount;
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
	 * @return null|\XF\Entity\User
	 */
	public function getUser(): ?\XF\Entity\User
	{
		return $this->user;
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

		if ($type == 'soft')
		{
			$result = $this->discount->softDelete($reason, $user);
		}
		else
		{
			$result = $this->discount->delete();
		}

		return $result;
	}
	
	/**
	 * @throws \LogicException
	 * @throws \Exception
	 * @throws \XF\PrintableException
	 */
	public function unDelete(): bool
	{
		$user = $this->user ?: \XF::visitor();

		return $this->discount->unDelete($user);
	}
}