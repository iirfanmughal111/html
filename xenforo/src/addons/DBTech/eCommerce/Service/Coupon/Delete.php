<?php

namespace DBTech\eCommerce\Service\Coupon;

use DBTech\eCommerce\Entity\Coupon;

/**
 * Class Delete
 *
 * @package DBTech\eCommerce\Service\Coupon
 */
class Delete extends \XF\Service\AbstractService
{
	/** @var \DBTech\eCommerce\Entity\Coupon */
	protected $coupon;

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
	 * @param Coupon $coupon
	 */
	public function __construct(\XF\App $app, Coupon $coupon)
	{
		parent::__construct($app);
		$this->coupon = $coupon;
	}

	/**
	 * @return Coupon
	 */
	public function getCoupon(): Coupon
	{
		return $this->coupon;
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

		if ($type == 'soft')
		{
			$result = $this->coupon->softDelete($reason, $user);
		}
		else
		{
			$result = $this->coupon->delete();
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

		return $this->coupon->unDelete($user);
	}
}