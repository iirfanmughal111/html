<?php /** @noinspection PhpMissingReturnTypeInspection */

namespace DBTech\eCommerce\XF\Admin\Controller;

/**
 * Class Index
 * @package DBTech\eCommerce\XF\Admin\Controller
 */
class Index extends XFCP_Index
{
	protected function getDashboardStatGraphs()
	{
		$previous = parent::getDashboardStatGraphs();
		
		if (!$this->options()->dbtechEcommerceEnableIncomeGraph)
		{
			return $previous;
		}
		
		return array_merge($previous, [
			['dbt_ecom_income']
		]);
	}
}