<?php

namespace DBTech\eCommerce\Repository;

use XF\Mvc\Entity\Finder;
use XF\Mvc\Entity\Repository;

/**
 * Class Commission
 * @package DBTech\eCommerce\Repository
 */
class Commission extends Repository
{
	/**
	 * @return Finder
	 */
	public function findCommissionsForList(): Finder
	{
		return $this->finder('DBTech\eCommerce:Commission')->order('name', 'ASC');
	}

	/**
	 * @throws \XF\Db\Exception
	 */
	public function rebuildTotalPayments()
	{
		$this->db()->query('
			UPDATE xf_dbtech_ecommerce_commission AS commission
			SET total_payments = (
				SELECT SUM(payment_amount)
				FROM xf_dbtech_ecommerce_commission_payment AS payment
				WHERE payment.commission_id = commission.commission_id
			)
		');
	}
}