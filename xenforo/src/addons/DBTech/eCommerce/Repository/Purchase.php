<?php

namespace DBTech\eCommerce\Repository;

use XF\Mvc\Entity\Repository;

/**
 * Class Purchase
 * @package DBTech\eCommerce\Repository
 */
class Purchase extends Repository
{
	/**
	 * @param int $userId
	 *
	 * @return float
	 */
	public function getAmountSpentForUser(int $userId): float
	{
		return (float)$this->db()->fetchOne('
			SELECT SUM(cost_amount)
			FROM xf_dbtech_ecommerce_purchase_log
			WHERE user_id = ?
		', $userId);
	}
}