<?php

namespace DBTech\eCommerce\Service\Sale;

use DBTech\eCommerce\Entity\Sale;

/**
 * Class Create
 *
 * @package DBTech\eCommerce\Service\Sale
 */
class Create extends \XF\Service\AbstractService
{
	use \XF\Service\ValidateAndSavableTrait;


	/** @var Sale */
	protected $sale;
	
	/** @var string */
	protected $description;
	
	/** @var array */
	protected $productDiscounts;
	
	/** @var array */
	protected $otherDates;

	/** @var \XF\Service\Thread\Creator|null */
	protected $threadCreator;

	/** @var bool */
	protected $performValidations = true;
	
	/** @var bool */
	protected $email = false;


	/**
	 * Create constructor.
	 *
	 * @param \XF\App $app
	 */
	public function __construct(\XF\App $app)
	{
		parent::__construct($app);

		$this->sale = $app->em()->create('DBTech\eCommerce:Sale');

		$this->setupDefaults();
	}

	/**
	 *
	 */
	protected function setupDefaults()
	{
		$this->sale->sale_state = 'visible';
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
	 * @param string $description
	 *
	 * @return $this
	 */
	public function setDescription(string $description): Create
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
	public function setStartDate(int $date, string $time): Create
	{
		$sale = $this->sale;
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

		$sale->start_date = $dateTime->getTimestamp();

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
		$sale = $this->sale;
		$sale->end_date = strtotime('+' . $amount . ' ' . $unit, $sale->start_date);

		return $this;
	}

	/**
	 * @param array|null $discounts
	 *
	 * @return $this
	 */
	public function setProductDiscounts(?array $discounts): Create
	{
		$this->productDiscounts = $discounts;

		return $this;
	}

	/**
	 * @param array $dates
	 *
	 * @return $this
	 */
	public function setOtherDates(array $dates): Create
	{
		$this->otherDates = $dates;

		return $this;
	}

	/**
	 * @param bool $email
	 *
	 * @return $this
	 */
	public function setEmail(bool $email): Create
	{
		$this->email = $email;

		return $this;
	}
	
	/**
	 * @throws \Exception
	 */
	protected function finalSetup()
	{
		$sale = $this->sale;
		
		if (!empty($this->otherDates))
		{
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
	 * @return mixed
	 * @throws \Exception
	 */
	protected function _validate()
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

		$this->beforeInsert();

		$db = $this->db();
		$db->beginTransaction();

		$sale->save(true, false);

		$this->afterInsert();

		$db->commit();
		
		// need to rebuild sales cache
		$this->app->jobManager()->enqueueUnique('dbtEcomSaleRebuild', 'DBTech\eCommerce:SaleRebuild');
		
		return $sale;
	}

	/**
	 * @param \XF\Entity\Forum $forum
	 *
	 * @return \XF\Service\Thread\Creator
	 */
	protected function setupSaleThreadCreation(\XF\Entity\Forum $forum): \XF\Service\Thread\Creator
	{
		/** @var \XF\Service\Thread\Creator $creator */
		$creator = $this->service('XF:Thread\Creator', $forum);
		$creator->setIsAutomated();

		$creator->setContent($this->getThreadTitle(), $this->getThreadMessage(), false);
		$creator->setPrefix($this->sale->thread_prefix_id);

		$thread = $creator->getThread();
		$thread->bulkSet([
			'discussion_type' => 'dbtech_ecommerce_sale',
			'discussion_state' => 'visible'
		]);

		return $creator;
	}

	/**
	 * @return mixed|null|string|string[]
	 */
	protected function getThreadTitle()
	{
		$sale = $this->sale;

		$phraseParams = [
			'title' => $sale->title,
			'sale_link' => $this->app->router('public')->buildLink('canonical:dbtech-ecommerce/sale', $sale),
		];

		$phrase = \XF::phrase('dbtech_ecommerce_sale_thread_title_create', $phraseParams);

		return $phrase->render('raw');
	}

	/**
	 * @return mixed|null|string|string[]
	 */
	protected function getThreadMessage()
	{
		$sale = $this->sale;

		$phraseParams = [
			'title' => $sale->title,
			'sale_link' => $this->app->router('public')->buildLink('canonical:dbtech-ecommerce/sale', $sale),
		];

		$phrase = \XF::phrase('dbtech_ecommerce_sale_thread_body_create', $phraseParams);

		return $phrase->render('raw');
	}

	/**
	 * @param \XF\Entity\Thread $thread
	 */
	protected function afterSaleThreadCreated(\XF\Entity\Thread $thread)
	{
		/** @var \XF\Repository\Thread $threadRepo */
		$threadRepo = $this->repository('XF:Thread');
		$threadRepo->markThreadReadByVisitor($thread);

		/** @var \XF\Repository\ThreadWatch $threadWatchRepo */
		$threadWatchRepo = $this->repository('XF:ThreadWatch');
		$threadWatchRepo->autoWatchThread($thread, \XF::visitor(), true);
	}

	/**
	 *
	 */
	public function sendNotifications()
	{
		if ($this->threadCreator)
		{
			$this->threadCreator->sendNotifications();
		}
		
		if ($this->email)
		{
			$criteria = [
				'no_empty_email' => true,
				'user_state' => 'valid',
				'is_banned' => 0,
				'Option'	=> [
					'dbtech_ecommerce_email_on_sale' => true
				]
			];
			
			$searcher = $this->app->searcher('XF:User', $criteria);
			
			$total = $searcher->getFinder()->total();
			if ($total)
			{
				$this->app->jobManager()->enqueueLater(
					'dbtEcomSaleFuture' . $this->sale->sale_id,
					\XF::$time,
					'DBTech\eCommerce:SaleEmail',
					[
						'criteria'  => $criteria,
						'sale_id'   => $this->sale->sale_id,
						'sale_type' => 'future'
					]
				);
			}
		}
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
		$sale = $this->sale;
		
		/** @var \XF\Entity\Phrase $description */
		$description = $sale->getMasterDescriptionPhrase();
		$description->phrase_text = $this->description;
		$description->save();
		
		if ($sale->thread_node_id && $sale->ThreadForum && $sale->isVisible())
		{
			$creator = $this->setupSaleThreadCreation($sale->ThreadForum);
			if ($creator && $creator->validate())
			{
				/** @var \XF\Entity\Thread $thread */
				$thread = $creator->save();
				$sale->fastUpdate('discussion_thread_id', $thread->thread_id);
				$this->threadCreator = $creator;

				$this->afterSaleThreadCreated($thread);
			}
		}
	}
}