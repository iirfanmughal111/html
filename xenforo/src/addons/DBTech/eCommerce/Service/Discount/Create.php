<?php

namespace DBTech\eCommerce\Service\Discount;

use DBTech\eCommerce\Entity\Discount;

/**
 * Class Create
 *
 * @package DBTech\eCommerce\Service\Discount
 */
class Create extends \XF\Service\AbstractService
{
	use \XF\Service\ValidateAndSavableTrait;


	/** @var Discount */
	protected $discount;

	/** @var string */
	protected $title;

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

		$this->discount = $app->em()->create('DBTech\eCommerce:Discount');

		$this->setupDefaults();
	}

	/**
	 *
	 */
	protected function setupDefaults()
	{
		$this->discount->discount_state = 'visible';
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

		$this->beforeInsert();

		$db = $this->db();
		$db->beginTransaction();

		$discount->save(true, false);

		$this->afterInsert();

		$db->commit();

		return $discount;
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