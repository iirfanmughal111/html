<?php

namespace XenAddons\Showcase\Option;

use XF\Option\AbstractOption;

class Category extends AbstractOption
{
	public static function renderSelect(\XF\Entity\Option $option, array $htmlParams)
	{
		$data = self::getSelectData($option, $htmlParams);

		return self::getTemplater()->formSelectRow(
			$data['controlOptions'], $data['choices'], $data['rowOptions']
		);
	}

	public static function renderSelectMultiple(\XF\Entity\Option $option, array $htmlParams)
	{
		$data = self::getSelectData($option, $htmlParams);
		$data['controlOptions']['multiple'] = true;
		$data['controlOptions']['size'] = 8;

		return self::getTemplater()->formSelectRow(
			$data['controlOptions'], $data['choices'], $data['rowOptions']
		);
	}

	protected static function getSelectData(\XF\Entity\Option $option, array $htmlParams)
	{
		/** @var \XenAddons\Showcase\Repository\Category $categoryRepo */
		$categoryRepo = \XF::repository('XenAddons\Showcase:Category');
		
		$categories = $categoryRepo->findCategoryList()->fetch();
		$categoryTree = $categoryRepo->createCategoryTree($categories);

		$choices = [];
		
		foreach ($categoryTree->getFlattened() as $entry)
		{
			$category = $entry['record'];
		
			if ($entry['depth'])
			{
				$prefix = str_repeat('--', $entry['depth']) . ' ';
			}
			else
			{
				$prefix = '';
			}
		
			$choices[$category->category_id] = [
				'value' => $category->category_id,
				'label' => $prefix . \XF::escapeString($category->title)
			];
		}	

		return [
			'choices' => $choices,
			'controlOptions' => self::getControlOptions($option, $htmlParams),
			'rowOptions' => self::getRowOptions($option, $htmlParams)
		];
	}
}