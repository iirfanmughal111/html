<?php

namespace DBTech\eCommerce\Searcher;

use XF\Searcher\AbstractSearcher;
use XF\Mvc\Entity\Finder;

/**
 * Class Order
 * @package DBTech\eCommerce\Searcher
 */
class Order extends AbstractSearcher
{
	/** @var array */
	protected $allowedRelations = ['User', 'Address', 'ShippingAddress'];

	/** @var array */
	protected $formats = [
		'username' => 'like',
		'order_date' => 'date',
		'completed_date' => 'date',
		'reversed_date' => 'date'
	];

	/** @var array */
	protected $order = [['order_date', 'desc']];

	/**
	 * @return string
	 */
	protected function getEntityType(): string
	{
		return 'DBTech\eCommerce:Order';
	}

	/**
	 * @return array
	 */
	protected function getDefaultOrderOptions(): array
	{
		$orders = [
			'order_date' => \XF::phrase('date'),
			'completed_date' => \XF::phrase('dbtech_ecommerce_paid_date'),
			'reversed_date' => \XF::phrase('dbtech_ecommerce_reversed_date'),
			'User.username' => \XF::phrase('user_name'),
			'cost_amount' => \XF::phrase('dbtech_ecommerce_order_total'),
		];

		\XF::fire('dbtech_ecommerce_order_searcher_orders', [$this, &$orders]);

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
				$finder->where('ip_address', '>=', $parsed['startRange']);
				$finder->where('ip_address', '<=', $parsed['endRange']);
			}
			else
			{
				$finder->where('ip_address', $parsed['startRange']);
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
		/** @var \DBTech\eCommerce\Repository\Coupon $couponRepo */
		$couponRepo = $this->em->getRepository('DBTech\eCommerce:Coupon');
		
		/** @var \DBTech\eCommerce\Repository\Country $countryRepo */
		$countryRepo = $this->em->getRepository('DBTech\eCommerce:Country');
		
		return [
			'coupons' => $couponRepo
				->findCouponsForList()
				->orderTitle()
				->fetch()
				->pluckNamed('title', 'coupon_id'),
			'countries' => $countryRepo
				->findCountriesForList()
				->orderName()
				->fetch()
				->pluckNamed('name', 'country_code'),
		];
	}
	
	/**
	 * @return array
	 */
	public function getFormDefaults(): array
	{
		return [
			'is_test' => ['end' => -1],
			'sale_discounts' => ['end' => -1],
			'coupon_discounts' => ['end' => -1],
			'automatic_discounts' => ['end' => -1],
			'store_credit_amount' => ['end' => -1],
			'sales_tax' => ['end' => -1],
			'cost_amount' => ['end' => -1],
			
			'order_state' => ['pending', 'awaiting_payment', 'completed', 'shipped', 'reversed'],
		];
	}
}