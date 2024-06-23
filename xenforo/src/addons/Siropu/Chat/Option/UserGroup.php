<?php

namespace Siropu\Chat\Option;

class UserGroup extends \XF\Option\AbstractOption
{
	public static function renderSelect(\XF\Entity\Option $option, array $htmlParams)
	{
		$userGroupRepo = \XF::repository('XF:UserGroup');

		$choices = [0 => ''];

		foreach ($userGroupRepo->findUserGroupsForList()->fetch() AS $group)
		{
			$choices[$group->user_group_id] = $group->title;
		}

		return self::getSelectRow($option, $htmlParams, $choices);
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
		$choices = [];

          foreach (\XF::repository('XF:UserGroup')->findUserGroupsForList()->fetch() as $group)
          {
               $choices[$group->user_group_id] = [
                    'value' => $group->user_group_id,
                    'label' => \XF::escapeString($group->title)
               ];
          }

		return [
			'choices'        => $choices,
			'controlOptions' => self::getControlOptions($option, $htmlParams),
			'rowOptions'     => self::getRowOptions($option, $htmlParams)
		];
	}
}
