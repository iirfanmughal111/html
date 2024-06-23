<?php

namespace DBTech\eCommerce\Searcher;

use XF\Searcher\AbstractSearcher;
use XF\Mvc\Entity\Finder;

/**
 * Class StoreCreditLog
 * @package DBTech\eCommerce\Searcher
 */
class StoreCreditLog extends AbstractSearcher
{
	/** @var array */
	protected $allowedRelations = ['User', 'Ip', 'Product'];

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
		return 'DBTech\eCommerce:StoreCreditLog';
	}

	/**
	 * @return array
	 */
	protected function getDefaultOrderOptions(): array
	{
		$orders = [
			'log_date' => \XF::phrase('date'),
			'store_credit_amount' => \XF::phrase('dbtech_ecommerce_store_credit_amount_used'),
			'User.username' => \XF::phrase('user_name'),
		];

		\XF::fire('dbtech_ecommerce_store_credit_log_searcher_orders', [$this, &$orders]);

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

	/**
	 * @return array
	 */
	public function getFormDefaults(): array
	{
		return [
			'store_credit_amount' => ['end' => -1]
		];
	}
}