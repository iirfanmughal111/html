<?php

namespace DBTech\eCommerce\Searcher;

use XF\Searcher\AbstractSearcher;
use XF\Mvc\Entity\Finder;

/**
 * Class CouponLog
 * @package DBTech\eCommerce\Searcher
 */
class CouponLog extends AbstractSearcher
{
	/** @var array */
	protected $allowedRelations = ['User', 'Ip', 'Product', 'Coupon'];

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
		return 'DBTech\eCommerce:CouponLog';
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

		\XF::fire('dbtech_ecommerce_coupon_log_searcher_orders', [$this, &$orders]);

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
		if ($key == 'product_id')
		{
			if ($value == '_any')
			{
				return true;
			}
		}
		
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
		/** @var \DBTech\eCommerce\Repository\Coupon $couponRepo */
		$couponRepo = $this->em->getRepository('DBTech\eCommerce:Coupon');
		
		return [
			'coupons' => $couponRepo
				->findCouponsForList()
				->orderTitle()
				->fetch()
				->pluckNamed('title', 'coupon_id')
		];
	}
	
	/**
	 * @return array
	 */
	public function getFormDefaults(): array
	{
		return [
			'coupon_discounts' => ['end' => -1],
		];
	}
}