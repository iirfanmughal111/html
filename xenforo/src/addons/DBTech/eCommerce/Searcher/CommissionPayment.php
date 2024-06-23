<?php

namespace DBTech\eCommerce\Searcher;

use XF\Searcher\AbstractSearcher;
use XF\Mvc\Entity\Finder;

/**
 * Class CommissionPayment
 * @package DBTech\eCommerce\Searcher
 */
class CommissionPayment extends AbstractSearcher
{
	/** @var array */
	protected $allowedRelations = ['Commission', 'User', 'Ip'];

	/** @var array */
	protected $formats = [
		'username' => 'like',
		'payment_date' => 'date',
	];

	/** @var array */
	protected $order = [['payment_date', 'desc']];

	/**
	 * @return string
	 */
	protected function getEntityType(): string
	{
		return 'DBTech\eCommerce:CommissionPayment';
	}

	/**
	 * @return array
	 */
	protected function getDefaultOrderOptions(): array
	{
		$orders = [
			'payment_date' => \XF::phrase('date'),
			'User.username' => \XF::phrase('user_name'),
		];

		\XF::fire('dbtech_ecommerce_commission_searcher_orders', [$this, &$orders]);

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
		/** @var \DBTech\eCommerce\Repository\Commission $commissionRepo */
		$commissionRepo = $this->em->getRepository('DBTech\eCommerce:Commission');
		
		return [
			'commissions' => $commissionRepo
				->findCommissionsForList()
				->fetch()
				->pluckNamed('name', 'commission_id')
		];
	}
	
	/**
	 * @return array
	 */
	public function getFormDefaults(): array
	{
		return [
			'payment_amount' => ['end' => -1],
		];
	}
}