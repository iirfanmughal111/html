<?php

namespace DBTech\eCommerce\Finder;

use XF\Mvc\Entity\Finder;

/**
 * Class Order
 * @package DBTech\eCommerce\Finder
 */
class Order extends Finder
{
	/**
	 * @return $this
	 * @throws \InvalidArgumentException
	 */
	public function applyGlobalVisibilityChecks(): Order
	{
		$conditions = [];
		$viewableStates = ['awaiting_payment', 'completed', 'shipped', 'reversed'];

		$conditions[] = ['order_state', $viewableStates];

		$this->whereOr($conditions);

		return $this;
	}

	/**
	 * @return $this
	 * @throws \InvalidArgumentException
	 */
	public function useDefaultOrder(): Order
	{
		$defaultOrder = $this->app()->options()->dbtechEcommerceOrderDefaultOrder ?: 'order_date';
		$defaultDir = $defaultOrder == 'address' ? 'asc' : 'desc';

		$this->setDefaultOrder($defaultOrder, $defaultDir);

		return $this;
	}
}