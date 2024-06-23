<?php

namespace Siropu\Chat\Option;

class Room extends \XF\Option\AbstractOption
{
	public static function renderSelect(\XF\Entity\Option $option, array $htmlParams)
	{
		$data = self::getSelectData($option, $htmlParams);

		array_unshift($data['choices'], [
			'value' => 0,
			'label' => ''
		]);

		return self::getTemplater()->formSelectRow(
			$data['controlOptions'], $data['choices'], $data['rowOptions']
		);
	}
	public static function renderSelectMultiple(\XF\Entity\Option $option, array $htmlParams)
	{
		$data = self::getSelectData($option, $htmlParams);
		$data['controlOptions']['multiple'] = true;
		$data['controlOptions']['size']     = 5;

		return self::getTemplater()->formSelectRow(
			$data['controlOptions'], $data['choices'], $data['rowOptions']
		);
	}
	protected static function getSelectData(\XF\Entity\Option $option, array $htmlParams)
	{
		return [
			'choices'        => \XF::repository('Siropu\Chat:Room')->getRoomOptionsData(),
			'controlOptions' => self::getControlOptions($option, $htmlParams),
			'rowOptions'     => self::getRowOptions($option, $htmlParams)
		];
	}
}
