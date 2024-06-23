<?php

namespace DBTech\eCommerce\Service\License;

use DBTech\eCommerce\Entity\ProductCost;
use DBTech\eCommerce\Entity\Product;

/**
 * Class Create
 *
 * @package DBTech\eCommerce\Service\License
 */
class Create extends \XF\Service\AbstractService
{
	use \XF\Service\ValidateAndSavableTrait;

	/** @var \DBTech\eCommerce\Entity\Product */
	protected $product;
	
	/** @var \DBTech\eCommerce\Entity\License */
	protected $license;

	/** @var bool */
	protected $allAccess = true;

	/** @var bool */
	protected $performValidations = true;
	
	/** @var int|null */
	protected $maxExpiryDate;


	/**
	 * Create constructor.
	 *
	 * @param \XF\App $app
	 * @param \DBTech\eCommerce\Entity\Product $product
	 */
	public function __construct(\XF\App $app, Product $product)
	{
		parent::__construct($app);

		$this->product = $product;
		$this->license = $product->getNewLicense();

		$this->setupDefaults();
	}

	/**
	 *
	 */
	protected function setupDefaults()
	{
		$this->license->license_state = $this->product->getNewContentState($this->license);

		$visitor = \XF::visitor();
		$this->license->user_id = $visitor->user_id;
		$this->license->username = $visitor->username;
	}

	/**
	 * @return \DBTech\eCommerce\Entity\License
	 */
	public function getLicense(): \DBTech\eCommerce\Entity\License
	{
		return $this->license;
	}

	/**
	 * @return \DBTech\eCommerce\Entity\Product
	 */
	public function getProduct(): Product
	{
		return $this->product;
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
	 * @return $this
	 */
	public function setIsAutomated(): Create
	{
		$this->setPerformValidations(false);

		return $this;
	}

	/**
	 * @param bool $allAccess
	 *
	 * @return $this
	 */
	public function setIsAllAccess(bool $allAccess): Create
	{
		$this->allAccess = $allAccess;

		return $this;
	}

	/**
	 * @param int $date
	 * @param string $time
	 *
	 * @return $this
	 * @throws \Exception
	 */
	public function setPurchaseDate(int $date, string $time): Create
	{
		$license = $this->license;
		$language = \XF::language();

		$dateTime = new \DateTime('@' . $date);
		$dateTime->setTimezone($language->getTimeZone());

		if (!$time || strpos($time, ':') === false)
		{
			// We didn't have a valid time string
			$hours = $language->date(\XF::$time, 'H');
			$minutes = $language->date(\XF::$time, 'i');
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
	 * @param int $amount
	 * @param string $unit
	 *
	 * @return $this
	 */
	public function setDuration(string $type, int $amount, string $unit): Create
	{
		$license = $this->license;
		if ($type == 'permanent')
		{
			$license->expiry_date = 0;
		}
		else
		{
			$license->expiry_date = strtotime('+' . $amount . ' ' . $unit, $license->purchase_date);
		}

		return $this;
	}

	/**
	 * @param \DBTech\eCommerce\Entity\ProductCost $cost
	 *
	 * @return $this
	 */
	public function setExpiryDateFromCost(ProductCost $cost): Create
	{
		// Don't pass $this->license to getNewExpiryDate because we want expiry date to start from now
		$this->license->expiry_date = $cost->getNewExpiryDate();

		return $this;
	}

	/**
	 * @param int|null $expiryDate
	 *
	 * @return $this
	 */
	public function setMaxExpiryDate(?int $expiryDate): Create
	{
		$this->maxExpiryDate = $expiryDate;

		return $this;
	}

	/**
	 * @param array $licenseFields
	 * @param string $editMode
	 *
	 * @return $this
	 */
	public function setLicenseFields(array $licenseFields, string $editMode = 'user'): Create
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
	public function setOrderDetails(int $orderId, ?string $purchaseRequestKey = null): Create
	{
		$this->license->order_id = $orderId;
		if ($purchaseRequestKey)
		{
			$this->license->purchase_request_key = $purchaseRequestKey;
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
	 * @return mixed
	 */
	protected function _validate(): array
	{
		$this->finalSetup();

		$license = $this->license;
		$product = $this->product;

		if (!empty($this->maxExpiryDate) && $license->expiry_date > $this->maxExpiryDate)
		{
			return [
				\XF::phrase('dbtech_ecommerce_license_expiry_date_restricted')
			];
		}

		if ($this->allAccess && $this->getPerformValidations())
		{
			/*
			$existingLicense = $this->findOne('DBTech\eCommerce:License', [
				'product_id' => $product->product_id,
				'user_id' => $license->user_id,
			]);
			*/

			$existingLicenseFinder = $this->finder('DBTech\eCommerce:License');
			$existingLicenseFinder->where([
				'product_id' => $product->product_id,
				'user_id' => $license->user_id,

			]);
			$existingLicenseFinder->where('required_user_group_ids', '!=', '[]');

			if ($existingLicenseFinder->total() > 0)
			{
				return [
					\XF::phrase('dbtech_ecommerce_you_already_own_this_license_as_part_of_pass')
				];
			}
		}

		$license->preSave();
		return $license->getErrors();
	}
	
	/**
	 * @return mixed
	 * @throws \LogicException
	 * @throws \Exception
	 * @throws \XF\PrintableException
	 */
	protected function _save(): \DBTech\eCommerce\Entity\License
	{
		$license = $this->license;

		$this->beforeInsert();

		$db = $this->db();
		$db->beginTransaction();

		$license->save(true, false);

		$this->afterInsert();

		$db->commit();

		return $license;
	}

	/**
	 *
	 */
	public function sendNotifications()
	{
	}

	/**
	 *
	 */
	public function beforeInsert()
	{
	}

	/**
	 * @throws \XF\PrintableException
	 */
	public function afterInsert()
	{
		$product = $this->product;
		$license = $this->license;

		if ($product->product_type === 'dbtech_ecommerce_key')
		{
			$license->generateSerialKey();
		}
	}
}