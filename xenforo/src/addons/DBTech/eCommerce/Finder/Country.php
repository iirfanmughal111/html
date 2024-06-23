<?php

namespace DBTech\eCommerce\Finder;

use XF\Mvc\Entity\Finder;

/**
 * Class Country
 * @package DBTech\eCommerce\Finder
 */
class Country extends Finder
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
	): Country {
		if ($match)
		{
			$expression = 'name';
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
	public function orderName(string $direction = 'ASC'): Country
	{
		$expression = $this->columnUtf8('name');
		$this->order($expression, $direction);

		return $this;
	}
}