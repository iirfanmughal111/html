<?php

namespace Siropu\Chat\Option;

class BbCode extends \XF\Option\AbstractOption
{
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
		$choices = [];

		$bbCodes = \XF::repository('XF:BbCode')
			->findBbCodesForList()
			->fetch();

		foreach ($bbCodes as $code)
		{
			$choices[$code->bb_code_id] = [
				'value' => $code->bb_code_id,
                    'label' => \XF::escapeString($code->title)
			];
		}

		return [
			'choices'        => $choices,
			'controlOptions' => self::getControlOptions($option, $htmlParams),
			'rowOptions'     => self::getRowOptions($option, $htmlParams)
		];
	}
}
