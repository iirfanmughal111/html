<?php

namespace XenAddons\Showcase\Repository;

use XF\Repository\AbstractField;

class ItemField extends AbstractField
{
	protected function getRegistryKey()
	{
		return 'xa_scItemFields';
	}

	protected function getClassIdentifier()
	{
		return 'XenAddons\Showcase:ItemField';
	}

	public function getDisplayGroups()
	{
		return [
			'header' => \XF::phrase('xa_sc_item_header'),
			'section_1_above' => \XF::phrase('xa_sc_section_1_above_section'),
			'section_1_below' => \XF::phrase('xa_sc_section_1_below_section'),
			'section_2_above' => \XF::phrase('xa_sc_section_2_above_section'),
			'section_2_below' => \XF::phrase('xa_sc_section_2_below_section'),			
			'section_3_above' => \XF::phrase('xa_sc_section_3_above_section'),
			'section_3_below' => \XF::phrase('xa_sc_section_3_below_section'),
			'section_4_above' => \XF::phrase('xa_sc_section_4_above_section'),
			'section_4_below' => \XF::phrase('xa_sc_section_4_below_section'),
			'section_5_above' => \XF::phrase('xa_sc_section_5_above_section'),
			'section_5_below' => \XF::phrase('xa_sc_section_5_below_section'),
			'section_6_above' => \XF::phrase('xa_sc_section_6_above_section'),
			'section_6_below' => \XF::phrase('xa_sc_section_6_below_section'),
			'new_tab' => \XF::phrase('xa_sc_own_tab'),
			'sidebar' => \XF::phrase('xa_sc_sidebar_block'),
			'new_sidebar_block' => \XF::phrase('xa_sc_own_sidebar_block'),
			'self_place' => \XF::phrase('xa_sc_self_placement'),
		];
	}
	
	public function getItemFieldValues($itemId)
	{
		$fields = $this->db()->fetchAll('
			SELECT field_value.*, field.field_type
			FROM xf_xa_sc_item_field_value AS field_value
			INNER JOIN xf_xa_sc_item_field AS field ON (field.field_id = field_value.field_id)
			WHERE field_value.item_id = ?
		', $itemId);
	
		$values = [];
		foreach ($fields AS $field)
		{
			if ($field['field_type'] == 'checkbox' || $field['field_type'] == 'multiselect')
			{
				$values[$field['field_id']] = \XF\Util\Php::safeUnserialize($field['field_value']);
			}
			else
			{
				$values[$field['field_id']] = $field['field_value'];
			}
		}
		return $values;
	}
	
	public function rebuildItemFieldValuesCache($itemId)
	{
		$cache = $this->getItemFieldValues($itemId);
	
		$this->db()->update('xf_xa_sc_item',
			['custom_fields' => json_encode($cache)],
			'item_id = ?', $itemId
		);
	}	
}