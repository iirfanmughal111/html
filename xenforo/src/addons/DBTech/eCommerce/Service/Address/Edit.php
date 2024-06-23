<?php

namespace DBTech\eCommerce\Service\Address;

use DBTech\eCommerce\Entity\Address;

/**
 * Class Edit
 *
 * @package DBTech\eCommerce\Service\Address
 */
class Edit extends \XF\Service\AbstractService
{
	use \XF\Service\ValidateAndSavableTrait;

	/** @var Address */
	protected $address;

	/** @var bool */
	protected $logIp = true;

	/** @var bool */
	protected $performValidations = true;

	/** @var bool */
	protected $alert = false;

	/** @var string */
	protected $alertReason = '';


	/**
	 * Edit constructor.
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
	 * @param bool $perform
	 *
	 * @return $this
	 */
	public function setPerformValidations(bool $perform): Edit
	{
		$this->performValidations = $perform;

		return $this;
	}

	/**
	 * @return bool
	 */
	public function getPerformValidations(): bool
	{
		return $this->performValidations;
	}

	/**
	 *
	 */
	public function setIsAutomated(): Edit
	{
		$this->setPerformValidations(false);

		return $this;
	}

	/**
	 * @param bool $logIp
	 */
	public function logIp(bool $logIp)
	{
		$this->logIp = $logIp;
	}

	/**
	 * @param bool $alert
	 * @param string|null $reason
	 *
	 * @return $this
	 */
	public function setSendAlert(bool $alert, ?string $reason = null): Edit
	{
		$this->alert = $alert;
		if ($reason !== null)
		{
			$this->alertReason = $reason;
		}

		return $this;
	}

	/**
	 *
	 */
	protected function finalSetup()
	{
		if (!$this->performValidations)
		{
			$this->address->setOption('admin_edit', true);
		}
	}
	
	/**
	 * @return mixed
	 * @throws \Exception
	 */
	protected function _validate(): array
	{
		$this->finalSetup();

		$address = $this->address;
		
		if ($this->performValidations)
		{
			if ($address->isChanged('sales_tax_id') && $address->sales_tax_id)
			{
				$addressRepo = $this->getAddressRepo();
				$valid = $addressRepo->validateVatId($address->sales_tax_id, $address, $error);
				
				if ($error)
				{
					return [$error];
				}
				
				if (!$valid)
				{
					return [
						\XF::phraseDeferred('dbtech_ecommerce_invalid_vat_number')
					];
				}
			}
		}

		$address->preSave();
		return $address->getErrors();
	}

	/**
	 * @return mixed
	 * @throws \LogicException
	 * @throws \Exception
	 * @throws \XF\PrintableException
	 */
	protected function _save(): Address
	{
		$address = $this->address;

		$this->beforeUpdate();

		$db = $this->db();
		$db->beginTransaction();

		$address->save(true, false);

		$this->afterUpdate();

		$db->commit();

		if ($this->alert)
		{
			/** @var \DBTech\eCommerce\Repository\Address $addressRepo */
			$addressRepo = $this->repository('DBTech\eCommerce:Address');
			$addressRepo->sendModeratorActionAlert($this->address, 'edit', $this->alertReason);
		}

		return $address;
	}

	public function beforeUpdate()
	{
	}

	public function afterUpdate()
	{
	}
	
	/**
	 * @return \DBTech\eCommerce\Repository\Address|\XF\Mvc\Entity\Repository
	 */
	protected function getAddressRepo()
	{
		return $this->repository('DBTech\eCommerce:Address');
	}
}