<?php

namespace DBTech\eCommerce\Finder;

use XF\Mvc\Entity\Finder;

/**
 * Class Sale
 * @package DBTech\eCommerce\Finder
 */
class Sale extends Finder
{
	/**
	 * @return $this
	 */
	public function onlyActive(): Sale
	{
		$this->where('start_date', '<=', \XF::$time);
		$this->where('end_date', '>', \XF::$time);
		$this->where('sale_state', 'visible');
		
		return $this;
	}
	
	/**
	 * @return $this
	 */
	public function onlyValidRecurringSales(): Sale
	{
		$this->where('is_recurring', true);
		$this->where('recurring_length_amount', '>', 0);
		$this->where('recurring_length_unit', '!=', '');
		
		return $this;
	}

	/**
	 * @param string $match
	 * @param bool $caseSensitive
	 * @param bool $prefixMatch
	 * @return $this
	 */
	public function searchText(
		string $match,
		bool $caseSensitive = false,
		bool $prefixMatch = false
	): Sale {
		if ($match)
		{
//			$expression = 'MasterTitle.phrase_text';
			$expression = 'title';
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
	public function orderTitle(string $direction = 'ASC'): Sale
	{
		//		$expression = $this->columnUtf8('MasterTitle.phrase_text');
		$expression = $this->columnUtf8('title');
		$this->order($expression, $direction);

		return $this;
	}
}