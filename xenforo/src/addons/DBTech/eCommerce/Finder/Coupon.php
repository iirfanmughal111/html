<?php

namespace DBTech\eCommerce\Finder;

use XF\Mvc\Entity\Finder;

/**
 * Class Coupon
 * @package DBTech\eCommerce\Finder
 */
class Coupon extends Finder
{
	/**
	 * @param string $match
	 * @param bool $caseSensitive
	 * @param bool $prefixMatch
	 *
	 * @return $this
	 */
	public function searchText(
		string $match,
		bool $caseSensitive = false,
		bool $prefixMatch = false
	): Coupon {
		if ($match)
		{
			$expression = 'MasterTitle.phrase_text';
			if ($caseSensitive)
			{
				$expression = $this->expression('BINARY %s', $expression);
			}

			$this->where($expression, 'LIKE', $this->escapeLike($match, $prefixMatch ? '?%' : '%?%'));
		}

		return $this;
	}
	
	/**
	 * @param string $direction
	 * @return $this
	 */
	public function orderTitle(string $direction = 'ASC'): Coupon
	{
		$expression = $this->columnUtf8('MasterTitle.phrase_text');
		$this->order($expression, $direction);
		
		return $this;
	}
	
	/**
	 * @return $this
	 */
	public function isValid(): Coupon
	{
		$this->where('coupon_state', 'visible')
			->where('start_date', '<=', \XF::$time)
			->where('expiry_date', '>', \XF::$time);
		
		return $this;
	}
}