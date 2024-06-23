<?php

namespace DBTech\eCommerce\Finder;

use XF\Mvc\Entity\Finder;

/**
 * Class Address
 * @package DBTech\eCommerce\Finder
 */
class Address extends Finder
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
	): Address {
		if ($match)
		{
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
	public function orderTitle(string $direction = 'ASC'): Address
	{
		$expression = $this->columnUtf8('title');
		$this->order($expression, $direction);

		return $this;
	}
}