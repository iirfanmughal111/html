<?php

namespace DBTech\eCommerce\Service\Address;

use DBTech\eCommerce\Entity\Address;

/**
 * Class Delete
 *
 * @package DBTech\eCommerce\Service\Address
 */
class Delete extends \XF\Service\AbstractService
{
	/** @var \DBTech\eCommerce\Entity\Address */
	protected $address;

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
	 * @param Address $address
	 */
	public function __construct(\XF\App $app, Address $address)
	{
		parent::__construct($app);
		$this->address = $address;
	}

	/**
	 * @return Address
	 */
	public function getAddress(): Address
	{
		return $this->address;
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
	 * @return bool
	 * @throws \InvalidArgumentException
	 * @throws \LogicException
	 * @throws \Exception
	 * @throws \XF\PrintableException
	 */
	public function delete(): bool
	{
		return $this->address->delete();
	}
}