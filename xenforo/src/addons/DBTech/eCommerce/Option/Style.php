<?php

namespace DBTech\eCommerce\Option;

use XF\Option\AbstractOption;

/**
 * Class Style
 *
 * @package DBTech\eCommerce\Option
 */
class Style extends AbstractOption
{
	/**
	 * @param \XF\Entity\Option $option
	 * @param array $htmlParams
	 *
	 * @return string
	 */
	public static function renderSelect(\XF\Entity\Option $option, array $htmlParams): string
	{
		/** @var \XF\Repository\Style $styleRepo */
		$styleRepo = \XF::repository('XF:Style');

		$choices = [0 => ''];
		foreach ($styleRepo->getStyleTree(false)->getFlattened() AS $entry)
		{
			$choices[$entry['record']->style_id] = $entry['record']->title;
		}

		return self::getSelectRow($option, $htmlParams, $choices);
	}
}