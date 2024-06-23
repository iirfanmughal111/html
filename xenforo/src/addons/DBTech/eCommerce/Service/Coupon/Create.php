<?php

namespace DBTech\eCommerce\Service\Coupon;

use DBTech\eCommerce\Entity\Coupon;

/**
 * Class Create
 *
 * @package DBTech\eCommerce\Service\Coupon
 */
class Create extends \XF\Service\AbstractService
{
	use \XF\Service\ValidateAndSavableTrait;


	/** @var Coupon */
	protected $coupon;

	/** @var string */
	protected $title;

	/** @var array */
	protected $productDiscounts;

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

		$this->coupon = $app->em()->create('DBTech\eCommerce:Coupon');

		$this->setupDefaults();
	}

	/**
	 *
	 */
	protected function setupDefaults()
	{
		$this->coupon->coupon_state = 'visible';
	}

	/**
	 * @return Coupon
	 */
	public function getCoupon(): Coupon
	{
		return $this->coupon;
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
	 * @param string $title
	 *
	 * @return $this
	 */
	public function setTitle(string $title): Create
	{
		$this->title = $title;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getTitle(): string
	{
		return $this->title;
	}

	/**
	 * @param int $date
	 * @param string $time
	 *
	 * @return $this
	 * @throws \Exception
	 */
	public function setStartDate(int $date, string $time): Create
	{
		$coupon = $this->coupon;
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

		$coupon->start_date = $dateTime->getTimestamp();

		return $this;
	}

	/**
	 * @param int $amount
	 * @param string $unit
	 *
	 * @return $this
	 */
	public function setDuration(int $amount, string $unit): Create
	{
		$coupon = $this->coupon;
		$coupon->expiry_date = strtotime('+' . $amount . ' ' . $unit, $coupon->start_date);

		return $this;
	}

	/**
	 * @param array $discounts
	 *
	 * @return $this
	 */
	public function setProductDiscounts(array $discounts): Create
	{
		$this->productDiscounts = $discounts;

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
	protected function _validate()
	{
		$this->finalSetup();

		$coupon = $this->coupon;

		$coupon->preSave();
		return $coupon->getErrors();
	}
	
	/**
	 * @return mixed
	 * @throws \LogicException
	 * @throws \Exception
	 * @throws \XF\PrintableException
	 */
	protected function _save(): Coupon
	{
		$coupon = $this->coupon;

		$this->beforeInsert();

		$db = $this->db();
		$db->beginTransaction();

		$coupon->save(true, false);

		$this->afterInsert();

		$db->commit();

		return $coupon;
	}

	public function beforeInsert()
	{
	}
	
	/**
	 * @throws \LogicException
	 * @throws \Exception
	 * @throws \XF\PrintableException
	 */
	public function afterInsert()
	{
		$coupon = $this->coupon;

		if ($this->title)
		{
			/** @var \XF\Entity\Phrase $title */
			$title = $coupon->getMasterTitlePhrase();
			$title->phrase_text = $this->title;
			$title->save();
		}
		
		if ($this->productDiscounts)
		{
			/** @var \DBTech\eCommerce\Repository\ProductCoupon $repo */
			$repo = $this->repository('DBTech\eCommerce:ProductCoupon');
			$repo->updateContentAssociations($this->coupon->coupon_id, $this->productDiscounts);
		}
	}
}