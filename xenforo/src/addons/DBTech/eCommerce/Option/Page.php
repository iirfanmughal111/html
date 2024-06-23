<?php

namespace DBTech\eCommerce\Option;

use XF\Option\AbstractOption;

/**
 * Class Page
 *
 * @package DBTech\eCommerce\Option
 */
class Page extends AbstractOption
{
	/**
	 * @param \XF\Entity\Option $option
	 * @param array $htmlParams
	 *
	 * @return string
	 */
	public static function renderSelect(\XF\Entity\Option $option, array $htmlParams): string
	{
		$data = self::getSelectData($option, $htmlParams);

		return self::getTemplater()->formSelectRow(
			$data['controlOptions'],
			$data['choices'],
			$data['rowOptions']
		);
	}
	
	/**
	 * @param \XF\Entity\Option $option
	 * @param array $htmlParams
	 *
	 * @return string
	 */
	public static function renderSelectMultiple(\XF\Entity\Option $option, array $htmlParams): string
	{
		$data = self::getSelectData($option, $htmlParams);
		$data['controlOptions']['multiple'] = true;
		$data['controlOptions']['size'] = 8;

		return self::getTemplater()->formSelectRow(
			$data['controlOptions'],
			$data['choices'],
			$data['rowOptions']
		);
	}
	
	/**
	 * @param \XF\Entity\Option $option
	 * @param array $htmlParams
	 *
	 * @return array
	 */
	protected static function getSelectData(\XF\Entity\Option $option, array $htmlParams): array
	{
		/** @var \XF\Repository\Node $nodeRepo */
		$nodeRepo = \XF::repository('XF:Node');

		$choices = $nodeRepo->getNodeOptionsData(true, 'Page', 'option');
		$choices = array_map(function ($v): array
		{
			$v['label'] = \XF::escapeString($v['label']);
			return $v;
		}, $choices);

		return [
			'choices' => $choices,
			'controlOptions' => self::getControlOptions($option, $htmlParams),
			'rowOptions' => self::getRowOptions($option, $htmlParams)
		];
	}
}