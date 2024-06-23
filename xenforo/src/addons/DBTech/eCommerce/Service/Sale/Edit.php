<?php

namespace DBTech\eCommerce\Service\Sale;

use DBTech\eCommerce\Entity\Sale;

/**
 * Class Edit
 *
 * @package DBTech\eCommerce\Service\Sale
 */
class Edit extends \XF\Service\AbstractService
{
	use \XF\Service\ValidateAndSavableTrait;

	/** @var \DBTech\eCommerce\Entity\Sale */
	protected $sale;
	
	/** @var string */
	protected $description;
	
	/** @var array */
	protected $productDiscounts;
	
	/** @var array */
	protected $otherDates;

	/** @var bool */
	protected $logIp = true;

	/** @var bool */
	protected $performValidations = true;


	/**
	 * Edit constructor.
	 *
	 * @param \XF\App $app
	 * @param Sale $sale
	 */
	public function __construct(\XF\App $app, Sale $sale)
	{
		parent::__construct($app);

		$this->sale = $sale;
	}

	/**
	 * @return Sale
	 */
	public function getSale(): Sale
	{
		return $this->sale;
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
	 * @return $this
	 */
	public function setIsAutomated(): Edit
	{
		$this->setPerformValidations(false);

		return $this;
	}

	/**
	 * @param string $description
	 *
	 * @return $this
	 */
	public function setDescription(string $description): Edit
	{
		$this->description = $description;

		return $this;
	}
	
	/**
	 * @return string
	 */
	public function getDescription(): string
	{
		return $this->description;
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
		$sale = $this->sale;
		$language = \XF::language();
		
		$dateTime = new \DateTime('@' . $date);
		$dateTime->setTimezone($language->getTimeZone());

		if (!$time || strpos($time, ':') === false)
		{
			// We didn't have a valid time string
			$hours = $language->date($sale->start_date, 'H');
			$minutes = $language->date($sale->start_date, 'i');
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

		$sale->start_date = $dateTime->getTimestamp();

		return $this;
	}

	/**
	 * @param int $date
	 * @param string $time
	 *
	 * @return $this
	 * @throws \Exception
	 */
	public function setEndDate(int $date, string $time): Edit
	{
		$sale = $this->sale;
		$language = \XF::language();
		
		$dateTime = new \DateTime('@' . $date);
		$dateTime->setTimezone($language->getTimeZone());

		if (!$time || strpos($time, ':') === false)
		{
			// We didn't have a valid time string
			$hours = $language->date($sale->end_date, 'H');
			$minutes = $language->date($sale->end_date, 'i');
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

		$sale->end_date = $dateTime->getTimestamp();

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
	 * @param array $dates
	 *
	 * @return $this
	 */
	public function setOtherDates(array $dates): Edit
	{
		$this->otherDates = $dates;

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
	 *
	 * @throws \Exception
	 */
	protected function finalSetup()
	{
		$sale = $this->sale;
		
		if (!empty($this->otherDates))
		{
			$sale->is_recurring = false;
			$sale->recurring_length_unit = '';
			$sale->recurring_length_amount = 0;
			
			$language = \XF::language();
			
			$startHours = $language->date($sale->start_date, 'H');
			$startMinutes = $language->date($sale->start_date, 'i');
			
			$endHours = $language->date($sale->end_date, 'H');
			$endMinutes = $language->date($sale->end_date, 'i');
			
			$dates = $this->otherDates;
			foreach ($dates as $i => $date)
			{
				if ($date['start'] >= $date['end'])
				{
					unset($dates[$i]);
					continue;
				}
				
				if ($date['start'] <= $this->sale->start_date)
				{
					unset($dates[$i]);
					continue;
				}
				
				$startDateTime = new \DateTime('@' . $date['start']);
				$startDateTime->setTimezone($language->getTimeZone());
				$startDateTime->setTime($startHours, $startMinutes);
				$dates[$i]['start'] = $startDateTime->getTimestamp();
				
				$endDateTime = new \DateTime('@' . $date['end']);
				$endDateTime->setTimezone($language->getTimeZone());
				$endDateTime->setTime($endHours, $endMinutes);
				$dates[$i]['end'] = $endDateTime->getTimestamp();
			}
			
			usort($dates, function (array $a, array $b): int
			{
				return ($a['start'] < $b['start']) ? -1 : 1;
			});
			
			$sale->other_dates = $dates;
		}
		
		if ($this->productDiscounts !== null)
		{
			$sale->product_discounts = $this->productDiscounts;
		}
	}
	
	/**
	 * @return array
	 * @throws \Exception
	 */
	protected function _validate(): array
	{
		$this->finalSetup();

		$sale = $this->sale;

		$sale->preSave();
		return $sale->getErrors();
	}
	
	/**
	 * @return mixed
	 * @throws \LogicException
	 * @throws \Exception
	 * @throws \XF\PrintableException
	 */
	protected function _save(): Sale
	{
		$sale = $this->sale;

		$this->beforeUpdate();

		$db = $this->db();
		$db->beginTransaction();

		$sale->save(true, false);

		$this->afterUpdate();

		$db->commit();
		
		// need to rebuild sales cache
		$this->app->jobManager()->enqueueUnique('dbtEcomSaleRebuild', 'DBTech\eCommerce:SaleRebuild');

		return $sale;
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
		$sale = $this->sale;
		
		if ($this->description)
		{
			/** @var \XF\Entity\Phrase $description */
			$description = $sale->getMasterDescriptionPhrase();
			$description->phrase_text = $this->description;
			$description->save();
		}
	}
}