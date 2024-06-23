<?php

namespace XenAddons\Showcase\Repository;

use XF\Repository\AbstractCategoryTree;

class Category extends AbstractCategoryTree
{
	protected function getClassName()
	{
		return 'XenAddons\Showcase:Category';
	}

	public function mergeCategoryListExtras(array $extras, array $childExtras)
	{
		$output = array_merge([
			'childCount' => 0,
			'item_count' => 0,
			'last_item_date' => 0,
			'last_item_title' => '',
			'last_item_id' => 0
		], $extras);

		foreach ($childExtras AS $child)
		{
			if (!empty($child['item_count']))
			{
				$output['item_count'] += $child['item_count'];
			}

			if (!empty($child['last_item_date']) && $child['last_item_date'] > $output['last_item_date'])
			{
				$output['last_item_date'] = $child['last_item_date'];
				$output['last_item_title'] = $child['last_item_title'];
				$output['last_item_id'] = $child['last_item_id'];
			}

			$output['childCount'] += 1 + (!empty($child['childCount']) ? $child['childCount'] : 0);
		}

		return $output;
	}
}