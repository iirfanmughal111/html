<?php

namespace DBTech\eCommerce\Service\Coupon;

use DBTech\eCommerce\Entity\Coupon;

/**
 * Class Edit
 *
 * @package DBTech\eCommerce\Service\Coupon
 */
class Edit extends \XF\Service\AbstractService
{
	use \XF\Service\ValidateAndSavableTrait;

	/** @var Coupon */
	protected $coupon;

	/** @var string */
	protected $title;

	/** @var array */
	protected $productDiscounts = null;

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
	 * @param string $title
	 *
	 * @return $this
	 */
	public function setTitle(string $title): Edit
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
	public function setStartDate(int $date, string $time): Edit
	{
		$coupon = $this->coupon;
		$language = \XF::language();
		
		$dateTime = new \DateTime('@' . $date);
		$dateTime->setTimezone($language->getTimeZone());

		if (!$time || strpos($time, ':') === false)
		{
			// We didn't have a valid time string
			$hours = $language->date($coupon->start_date, 'H');
			$minutes = $language->date($coupon->start_date, 'i');
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
	 * @param int $date
	 * @param string $time
	 *
	 * @return $this
	 * @throws \Exception
	 */
	public function setExpiryDate(int $date, string $time): Edit
	{
		$coupon = $this->coupon;
		$language = \XF::language();
		
		$dateTime = new \DateTime('@' . $date);
		$dateTime->setTimezone($language->getTimeZone());

		if (!$time || strpos($time, ':') === false)
		{
			// We didn't have a valid time string
			$hours = $language->date($coupon->expiry_date, 'H');
			$minutes = $language->date($coupon->expiry_date, 'i');
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

		$coupon->expiry_date = $dateTime->getTimestamp();

		return $this;
	}

	/**
	 * @param array $discounts
	 *
	 * @return $this
	 */
	public function setProductDiscounts(array $discounts): Edit
	{
		$this->productDiscounts = $discounts;

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

		$this->beforeUpdate();

		$db = $this->db();
		$db->beginTransaction();

		$coupon->save(true, false);

		$this->afterUpdate();

		$db->commit();

		if ($this->alert && $coupon->isVisible())
		{
			/** @var \DBTech\eCommerce\Repository\Coupon $couponRepo */
			$couponRepo = $this->repository('DBTech\eCommerce:Coupon');
			$couponRepo->sendModeratorActionAlert($this->coupon, 'edit', $this->alertReason);
		}

		return $coupon;
	}

	public function beforeUpdate()
	{
	}
	
	/**
	 * @throws \LogicException
	 * @throws \Exception
	 * @throws \XF\PrintableException
	 */
	public function afterUpdate()
	{
		$coupon = $this->coupon;

		if ($this->title)
		{
			/** @var \XF\Entity\Phrase $title */
			$title = $coupon->getMasterTitlePhrase();
			$title->phrase_text = $this->title;
			$title->save();
		}
		
		if ($this->productDiscounts !== null)
		{
			/** @var \DBTech\eCommerce\Repository\ProductCoupon $repo */
			$repo = $this->repository('DBTech\eCommerce:ProductCoupon');
			$repo->updateContentAssociations($this->coupon->coupon_id, $this->productDiscounts);
		}
	}
}