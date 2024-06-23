<?php /** @noinspection PhpMissingReturnTypeInspection */

namespace DBTech\eCommerce\XF\Searcher;

use XF\Mvc\Entity\Finder;

/**
 * Class User
 *
 * @package DBTech\eCommerce\XF\Searcher
 */
class User extends XFCP_User
{
	public function getFormData()
	{
		$formData = parent::getFormData();
		
		/** @var \DBTech\eCommerce\Repository\Product $productRepo */
		$productRepo = $this->em->getRepository('DBTech\eCommerce:Product');
		$formData['dbtechEcommerceProducts'] = $productRepo->getProductsByCategory();
		
		return $formData;
	}
	
	/**
	 * @return array
	 */
	public function getFormDefaults()
	{
		$previous = parent::getFormDefaults();
		
		$previous['dbtech_ecommerce_product_count'] = ['end' => -1];
		$previous['dbtech_ecommerce_license_count'] = ['end' => -1];
		$previous['dbtech_ecommerce_amount_spent'] = ['end' => -1];
		$previous['dbtech_ecommerce_is_distributor'] = [0, 1];
		
		return $previous;
	}
	
	/**
	 * @param Finder $finder
	 * @param $key
	 * @param $value
	 * @param $column
	 * @param $format
	 * @param $relation
	 *
	 * @return bool
	 */
	protected function applySpecialCriteriaValue(Finder $finder, $key, $value, $column, $format, $relation)
	{
		if ($key == 'dbtech_ecommerce_products' || $key == 'not_dbtech_ecommerce_products')
		{
			if (!is_array($value))
			{
				$value = [$value];
			}
			
			$positiveMatch = ($key == 'dbtech_ecommerce_products');
			$columnName = $finder->columnSqlName('user_id');
			if (!empty($value))
			{
				$finder->whereSql(($positiveMatch ? '' : 'NOT ') . 'EXISTS (SELECT license_id FROM xf_dbtech_ecommerce_license WHERE user_id = ' . $columnName . ' AND product_id IN(' . $finder->quote($value) . '))');
			}
			return true;
		}
		
		return parent::applySpecialCriteriaValue($finder, $key, $value, $column, $format, $relation);
	}
}