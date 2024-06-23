<?php

namespace DBTech\eCommerce\Service\Discount;

use DBTech\eCommerce\Entity\Discount;

/**
 * Class Edit
 *
 * @package DBTech\eCommerce\Service\Discount
 */
class Edit extends \XF\Service\AbstractService
{
	use \XF\Service\ValidateAndSavableTrait;

	/** @var Discount */
	protected $discount;

	/** @var string */
	protected $title;

	/** @var array */
	protected $productDiscounts;

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

		$discount = $this->discount;

		$discount->preSave();
		return $discount->getErrors();
	}
	
	/**
	 * @return mixed
	 * @throws \LogicException
	 * @throws \Exception
	 * @throws \XF\PrintableException
	 */
	protected function _save(): Discount
	{
		$discount = $this->discount;

		$this->beforeUpdate();

		$db = $this->db();
		$db->beginTransaction();

		$discount->save(true, false);

		$this->afterUpdate();

		$db->commit();

		if ($this->alert && $discount->isVisible())
		{
			/** @var \DBTech\eCommerce\Repository\Discount $discountRepo */
			$discountRepo = $this->repository('DBTech\eCommerce:Discount');
			$discountRepo->sendModeratorActionAlert($this->discount, 'edit', $this->alertReason);
		}

		return $discount;
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
		$discount = $this->discount;

		if ($this->title)
		{
			/** @var \XF\Entity\Phrase $title */
			$title = $discount->getMasterTitlePhrase();
			$title->phrase_text = $this->title;
			$title->save();
		}
	}
}