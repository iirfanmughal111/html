<?php

namespace DBTech\eCommerce\Repository;

use XF\Mvc\Entity\ArrayCollection;
use XF\Mvc\Entity\Finder;
use XF\Mvc\Entity\Repository;

/**
 * Class Sale
 * @package DBTech\eCommerce\Repository
 */
class Sale extends Repository
{
	/**
	 * @return Finder
	 */
	public function findSalesForList(): Finder
	{
		return $this->finder('DBTech\eCommerce:Sale')->order([['end_date', 'DESC'], ['start_date', 'DESC']], 'DESC');
	}
	
	/**
	 * @throws \InvalidArgumentException
	 */
	public function resetProductSales()
	{
		$this->db()->emptyTable('xf_dbtech_ecommerce_product_sale');
	}
	
	/**
	 * @throws \XF\PrintableException
	 */
	public function advanceSaleDates()
	{
		/** @var \DBTech\eCommerce\Entity\Sale[]|ArrayCollection $expiredSales */
		$expiredSales = $this->finder('DBTech\eCommerce:Sale')
			->where('other_dates', '!=', '[]')
			->where('end_date', '<=', \XF::$time)
		;
		foreach ($expiredSales as $sale)
		{
			if (count($sale->other_dates))
			{
				$dates = $sale->other_dates;
				
				$date = array_shift($dates);
				
				$sale->start_date = $date['start'];
				$sale->end_date = $date['end'];
				$sale->other_dates = $dates;
				$sale->save();
			}
		}
	}
	
	/**
	 * @throws \XF\PrintableException
	 */
	public function updateRecurringSales()
	{
		/** @var \DBTech\eCommerce\Entity\Sale[]|ArrayCollection $expiredRecurringSales */
		$expiredRecurringSales = $this->finder('DBTech\eCommerce:Sale')
			->onlyValidRecurringSales()
			->where('end_date', '<=', \XF::$time)
		;
		foreach ($expiredRecurringSales as $sale)
		{
			$sale->start_date = strtotime("+{$sale->recurring_length_amount} {$sale->recurring_length_unit}", $sale->start_date);
			$sale->end_date = strtotime("+{$sale->recurring_length_amount} {$sale->recurring_length_unit}", $sale->end_date);
			$sale->save();
		}
	}
}