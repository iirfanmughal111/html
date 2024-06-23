<?php

namespace DBTech\eCommerce\Searcher;

use XF\Searcher\AbstractSearcher;
use XF\Mvc\Entity\Finder;

/**
 * Class DistributorLog
 * @package DBTech\eCommerce\Searcher
 */
class DistributorLog extends AbstractSearcher
{
	/** @var array */
	protected $allowedRelations = ['Recipient', 'Ip', 'Product', 'Distributor'];

	/** @var array */
	protected $formats = [
		'username' => 'like',
		'log_date' => 'date',
	];

	/** @var array */
	protected $order = [['log_date', 'desc']];

	/**
	 * @return string
	 */
	protected function getEntityType(): string
	{
		return 'DBTech\eCommerce:DistributorLog';
	}

	/**
	 * @return array
	 */
	protected function getDefaultOrderOptions(): array
	{
		$orders = [
			'log_date' => \XF::phrase('date'),
			'User.username' => \XF::phrase('user_name'),
		];

		\XF::fire('dbtech_ecommerce_distributor_log_searcher_orders', [$this, &$orders]);

		return $orders;
	}

	/**
	 * @param Finder $finder
	 * @param $key
	 * @param $value
	 * @param $column
	 * @param $format
	 * @param $relation
	 * @return bool
	 */
	protected function applySpecialCriteriaValue(Finder $finder, $key, $value, $column, $format, $relation): bool
	{
		if ($key == 'ip')
		{
			$parsed = \XF\Util\Ip::parseIpRangeString($value);

			if (!$parsed)
			{
				return true;
			}
			
			if ($parsed['isRange'])
			{
				$finder->where('Ip.ip', '>=', $parsed['startRange']);
				$finder->where('Ip.ip', '<=', $parsed['endRange']);
			}
			else
			{
				$finder->where('Ip.ip', $parsed['startRange']);
			}

			return true;
		}

		return false;
	}

	/**
	 * @return array
	 */
	public function getFormData(): array
	{
		return [];
	}
}