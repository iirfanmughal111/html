<?php

namespace DBTech\eCommerce\Option;

use XF\Entity\Option;
use XF\Option\AbstractOption;

/**
 * Class Country
 *
 * @package DBTech\eCommerce\Option
 */
class Country extends AbstractOption
{
	/**
	 * @param \XF\Entity\Option $option
	 * @param array $htmlParams
	 *
	 * @return string
	 */
	public static function renderSelect(Option $option, array $htmlParams): string
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
	public static function renderSelectMultiple(Option $option, array $htmlParams): string
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
	protected static function getSelectData(Option $option, array $htmlParams): array
	{
		/** @var \DBTech\eCommerce\Repository\Country $countryRepo */
		$countryRepo = \XF::repository('DBTech\eCommerce:Country');

		$choices = $countryRepo->getCountryOptionsData(true, 'option');
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