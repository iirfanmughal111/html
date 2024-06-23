<?php

namespace DBTech\eCommerce\Repository;

use XF\Repository\AbstractCategoryTree;

/**
 * Class Category
 * @package DBTech\eCommerce\Repository
 */
class Category extends AbstractCategoryTree
{
	/**
	 * @return string
	 */
	protected function getClassName(): string
	{
		return 'DBTech\eCommerce:Category';
	}
	
	/**
	 * @param array $extras
	 * @param array $childExtras
	 *
	 * @return array
	 */
	public function mergeCategoryListExtras(array $extras, array $childExtras): array
	{
		$output = array_merge([
			'childCount' => 0,
			'product_count' => 0,
			'last_update' => 0,
			'last_product_title' => '',
			'last_product_id' => 0
		], $extras);

		foreach ($childExtras AS $child)
		{
			if (!empty($child['product_count']))
			{
				$output['product_count'] += $child['product_count'];
			}

			if (!empty($child['last_update']) && $child['last_update'] > $output['last_update'])
			{
				$output['last_update'] = $child['last_update'];
				$output['last_product_title'] = $child['last_product_title'];
				$output['last_product_id'] = $child['last_product_id'];
			}

			$output['childCount'] += 1 + (!empty($child['childCount']) ? $child['childCount'] : 0);
		}

		return $output;
	}
}