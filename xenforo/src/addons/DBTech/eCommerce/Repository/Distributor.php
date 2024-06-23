<?php

namespace DBTech\eCommerce\Repository;

use XF\Mvc\Entity\Finder;
use XF\Mvc\Entity\Repository;

/**
 * Class Distributor
 * @package DBTech\eCommerce\Repository
 */
class Distributor extends Repository
{
	/**
	 * @return Finder
	 */
	public function findDistributorsForList(): Finder
	{
		return $this->finder('DBTech\eCommerce:Distributor')
			->order('User.username', 'ASC')
			;
	}
}