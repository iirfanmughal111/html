<?php

namespace DBTech\eCommerce\Service\IncomeStats;

/**
 * Class Grapher
 *
 * @package DBTech\eCommerce\Service\IncomeStats
 */
class Grapher extends \XF\Service\AbstractService
{
	/** @var int */
	protected $start;

	/** @var int */
	protected $end;

	/** @var array */
	protected $types;


	/**
	 * Grapher constructor.
	 *
	 * @param \XF\App $app
	 * @param int $start
	 * @param int $end
	 * @param array $types
	 */
	public function __construct(\XF\App $app, int $start, int $end, array $types = [])
	{
		parent::__construct($app);

		$this->setDateRange($start, $end);
		$this->types = $types;
	}
	
	/**
	 * @param string $type
	 */
	public function addType(string $type)
	{
		$this->types[] = $type;
	}

	/**
	 * @param int $start
	 * @param int $end
	 *
	 * @return $this
	 */
	public function setDateRange(int $start, int $end): Grapher
	{
		$start -= $start % 86400; // make sure we always get the start of the day

		if ($end < $start)
		{
			$end = $start;
		}

		$this->start = $start;
		$this->end = $end;

		return $this;
	}

	/**
	 * @return array
	 * @throws \XF\Db\Exception
	 * @throws \XF\Db\Exception
	 */
	protected function getRawData(): array
	{
		if (!$this->types)
		{
			throw new \LogicException("Must have at least one type selected");
		}

		$output = [];
		$db = $this->db();
		$stats = $db->query('
			SELECT stats_date, product_id, counter
			FROM xf_dbtech_ecommerce_income_stats_daily
			WHERE stats_date BETWEEN ? AND ?
				AND product_id IN (' . $db->quote($this->types) . ')
			ORDER BY stats_date
		', [$this->start, $this->end]);
		while ($stat = $stats->fetch())
		{
			$output[$stat['stats_date']][$stat['product_id']] = $stat['counter'];
		}

		return $output;
	}

	/**
	 * @param \XF\Stats\Grouper\AbstractGrouper $grouper
	 *
	 * @return array
	 * @throws \XF\Db\Exception
	 * @throws \XF\Db\Exception
	 */
	public function getGroupedData(\XF\Stats\Grouper\AbstractGrouper $grouper): array
	{
		$baseValues = [];
		foreach ($this->types AS $type)
		{
			$baseValues[$type] = 0;
		}

		$groupings = [];
		foreach ($grouper->getGroupingsInRange($this->start, $this->end) AS $k => $grouping)
		{
			$grouping['count'] = 0;
			$grouping['values'] = $baseValues;
			$grouping['averages'] = $baseValues;
			$groupings[$k] = $grouping;
		}

		$rawData = $this->getRawData();

		foreach ($rawData AS $timestamp => $typeValues)
		{
			$groupValue = $grouper->getGrouping($timestamp);
			if (!isset($groupings[$groupValue]))
			{
				throw new \LogicException("Grouping $groupValue not found. This should have been created internally. Report as a bug.");
			}

			$groupings[$groupValue]['count']++;

			foreach ($typeValues AS $type => $value)
			{
				if (isset($groupings[$groupValue]['values'][$type]))
				{
					$groupings[$groupValue]['values'][$type] += $value;
				}
				else
				{
					$groupings[$groupValue]['values'][$type] = $value;
				}
			}
		}
		
		foreach ($groupings AS $key => $grouping)
		{
			foreach ($grouping['values'] AS $type => $value)
			{
				$average = $value / $grouping['days'];
				if ($grouping['days'] > 1)
				{
					$average = round($average, 2);
				}

				$groupings[$key]['averages'][$type] = $average;
			}
		}

		return $groupings;
	}
}