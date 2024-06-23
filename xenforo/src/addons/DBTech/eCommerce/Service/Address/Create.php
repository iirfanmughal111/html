<?php

namespace DBTech\eCommerce\Service\Address;

use DBTech\eCommerce\Entity\Address;

/**
 * Class Create
 *
 * @package DBTech\eCommerce\Service\Address
 */
class Create extends \XF\Service\AbstractService
{
	use \XF\Service\ValidateAndSavableTrait;


	/** @var Address */
	protected $address;

	/** @var bool */
	protected $performValidations = true;


	/**
	 * Create constructor.
	 *
	 * @param \XF\App $app
	 */
	public function __construct(\XF\App $app)
	{
		parent::__construct($app);

		$this->address = $app->em()->create('DBTech\eCommerce:Address');

		$this->setupDefaults();
	}

	/**
	 *
	 */
	protected function setupDefaults()
	{
		$this->address->user_id = \XF::visitor()->user_id;
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
	public function setPerformValidations(bool $perform): Create
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
	public function setIsAutomated(): Create
	{
		$this->setPerformValidations(false);

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
			if ($address->sales_tax_id)
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

		$this->beforeInsert();

		$db = $this->db();
		$db->beginTransaction();

		$address->save(true, false);

		$this->afterInsert();

		$db->commit();

		return $address;
	}

	public function beforeInsert()
	{
	}

	public function afterInsert()
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