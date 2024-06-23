<?php

namespace DBTech\eCommerce\Service\Address;

use DBTech\eCommerce\Entity\Address;

/**
 * Class Approve
 *
 * @package DBTech\eCommerce\Service\Address
 */
class Approve extends \XF\Service\AbstractService
{
	/** @var Address */
	protected $address;
	
	/** @var bool */
	protected $notify = true;
	
	/** @var int */
	protected $notifyRunTime = 3;
	
	/** @var string */
	protected $reason = '';
	
	/**
	 * Approve constructor.
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
	public function getUpdate(): Address
	{
		return $this->address;
	}

	/**
	 * @param bool $notify
	 *
	 * @return $this
	 */
	public function setNotify(bool $notify): Approve
	{
		$this->notify = $notify;

		return $this;
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
	 * @param string $reason
	 *
	 * @return $this
	 */
	public function setReason(string $reason): Approve
	{
		$this->reason = $reason;

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
		if ($this->address->address_state == 'moderated')
		{
			$this->address->address_state = 'verified';
			$this->address->save();
			
			$this->onApprove();
			return true;
		}
		
		return false;
	}
	
	/**
	 * @return bool
	 * @throws \LogicException
	 * @throws \Exception
	 * @throws \XF\PrintableException
	 */
	public function reject(): bool
	{
		if ($this->address->address_state == 'moderated')
		{
			$this->address->address_state = 'visible';
			$this->address->sales_tax_id = '';
			$this->address->save();
			
			$this->onReject();
			return true;
		}
		
		return false;
	}
	
	/**
	 *
	 */
	protected function onApprove()
	{
		if ($this->notify)
		{
			$addressRepo = $this->getAddressRepo();
			$addressRepo->sendModeratorActionAlert($this->address, 'approve', $this->reason);
		}
	}
	
	/**
	 *
	 */
	protected function onReject()
	{
		if ($this->notify)
		{
			$addressRepo = $this->getAddressRepo();
			$addressRepo->sendModeratorActionAlert($this->address, 'reject', $this->reason);
		}
	}
	
	/**
	 * @return \DBTech\eCommerce\Repository\Address|\XF\Mvc\Entity\Repository
	 */
	protected function getAddressRepo()
	{
		return $this->repository('DBTech\eCommerce:Address');
	}
}