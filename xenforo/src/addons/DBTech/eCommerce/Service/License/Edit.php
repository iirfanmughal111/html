<?php

namespace DBTech\eCommerce\Service\License;

use DBTech\eCommerce\Entity\License;
use DBTech\eCommerce\Entity\ProductCost;

/**
 * Class Edit
 *
 * @package DBTech\eCommerce\Service\License
 */
class Edit extends \XF\Service\AbstractService
{
	use \XF\Service\ValidateAndSavableTrait;

	/** @var \DBTech\eCommerce\Entity\License */
	protected $license;

	/**
	 * @var bool
	 */
	protected $logIp = true;

	/**
	 * @var bool
	 */
	protected $performValidations = true;

	/**
	 * @var bool
	 */
	protected $alert = false;

	/**
	 * @var string
	 */
	protected $alertReason = '';


	/**
	 * Edit constructor.
	 *
	 * @param \XF\App $app
	 * @param \DBTech\eCommerce\Entity\License $license
	 */
	public function __construct(\XF\App $app, License $license)
	{
		parent::__construct($app);

		$this->license = $license;
	}

	/**
	 * @return License
	 */
	public function getLicense(): License
	{
		return $this->license;
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
	 * @param int $date
	 * @param string $time
	 *
	 * @return $this
	 * @throws \Exception
	 */
	public function setPurchaseDate(int $date, string $time): Edit
	{
		$license = $this->license;
		$language = \XF::language();
		
		$dateTime = new \DateTime('@' . $date);
		$dateTime->setTimezone($language->getTimeZone());

		if (!$time || strpos($time, ':') === false)
		{
			// We didn't have a valid time string
			$hours = $language->date($license->purchase_date, 'H');
			$minutes = $language->date($license->purchase_date, 'i');
		}
		else
		{
			[$hours, $minutes] = explode(':', $time);

			// Sanitise hours and minutes to a maximum of 23:59
			$hours = min((int)$hours, 23);
			$minutes = min((int)$minutes, 59);
		}

		// Finally set it
		$dateTime->setTime($hours, $minutes);

		$license->purchase_date = $dateTime->getTimestamp();

		return $this;
	}

	/**
	 * @param string $type
	 * @param int $date
	 * @param string $time
	 *
	 * @return $this
	 * @throws \Exception
	 */
	public function setExpiryDate(string $type, int $date, string $time): Edit
	{
		$license = $this->license;
		if ($type == 'permanent')
		{
			$license->expiry_date = 0;
		}
		else
		{
			$language = \XF::language();
			
			$dateTime = new \DateTime('@' . $date);
			$dateTime->setTimezone($language->getTimeZone());

			if (!$time || strpos($time, ':') === false)
			{
				// We didn't have a valid time string
				$hours = $language->date($license->expiry_date, 'H');
				$minutes = $language->date($license->expiry_date, 'i');
			}
			else
			{
				[$hours, $minutes] = explode(':', $time);

				// Sanitise hours and minutes to a maximum of 23:59
				$hours = min((int)$hours, 23);
				$minutes = min((int)$minutes, 59);
			}

			// Finally set it
			$dateTime->setTime($hours, $minutes);

			$license->expiry_date = $dateTime->getTimestamp();
		}

		return $this;
	}

	/**
	 * @param \DBTech\eCommerce\Entity\ProductCost $cost
	 *
	 * @return $this
	 */
	public function setExpiryDateFromCost(ProductCost $cost): Edit
	{
		$this->license->expiry_date = $cost->getNewExpiryDate($this->license);

		return $this;
	}

	/**
	 * @param array $licenseFields
	 * @param string $editMode
	 *
	 * @return $this
	 */
	public function setLicenseFields(array $licenseFields, string $editMode = 'user'): Edit
	{
		$license = $this->license;

		/** @var \XF\CustomField\Set $fieldSet */
		$fieldSet = $license->license_fields;
		$fieldDefinition = $fieldSet->getDefinitionSet()
			->filterEditable($fieldSet, $editMode);

		$licenseFieldsShown = array_keys($fieldDefinition->getFieldDefinitions());

		if ($licenseFieldsShown)
		{
			$fieldSet->bulkSet($licenseFields, $licenseFieldsShown, $editMode);
		}

		return $this;
	}

	/**
	 * @param int $orderId
	 * @param string|null $purchaseRequestKey
	 *
	 * @return $this
	 */
	public function setOrderDetails(int $orderId, ?string $purchaseRequestKey = null): Edit
	{
		$this->license->order_id = $orderId;
		if ($purchaseRequestKey)
		{
			$this->license->purchase_request_key = $purchaseRequestKey;
		}

		return $this;
	}

	/**
	 * @param bool $logIp
	 *
	 * @return $this
	 */
	public function logIp(bool $logIp): Edit
	{
		$this->logIp = $logIp;

		return $this;
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
	}

	/**
	 * @return array
	 */
	protected function _validate(): array
	{
		$this->finalSetup();

		$license = $this->license;

		$license->preSave();
		return $license->getErrors();
	}
	
	/**
	 * @return mixed
	 * @throws \LogicException
	 * @throws \Exception
	 * @throws \XF\PrintableException
	 */
	protected function _save(): License
	{
		$license = $this->license;

		$this->beforeUpdate();

		$db = $this->db();
		$db->beginTransaction();

		$license->save(true, false);

		$this->afterUpdate();

		$db->commit();

		if ($this->alert && $license->user_id != \XF::visitor()->user_id && $license->isVisible())
		{
			/** @var \DBTech\eCommerce\Repository\License $licenseRepo */
			$licenseRepo = $this->repository('DBTech\eCommerce:License');
			$licenseRepo->sendModeratorActionAlert($this->license, 'edit', $this->alertReason);
		}

		return $license;
	}

	/**
	 *
	 */
	public function beforeUpdate()
	{
	}

	/**
	 *
	 */
	public function afterUpdate()
	{
	}
}