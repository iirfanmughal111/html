<?php

namespace XenAddons\Showcase\Repository;

use XF\Repository\AbstractField;

class UpdateField extends AbstractField
{
	protected function getRegistryKey()
	{
		return 'xa_scUpdateFields';
	}

	protected function getClassIdentifier()
	{
		return 'XenAddons\Showcase:UpdateField';
	}

	public function getDisplayGroups()
	{
		return [
			'above' => \XF::phrase('xa_sc_above_update'),
			'below' => \XF::phrase('xa_sc_below_update'),
			'self_place' => \XF::phrase('xa_sc_self_placement'),
		];
	}
	
	public function getUpdateFieldValues($itemUpdateId)
	{
		$fields = $this->db()->fetchAll('
			SELECT field_value.*, field.field_type
			FROM xf_xa_sc_update_field_value AS field_value
			INNER JOIN xf_xa_sc_update_field AS field ON (field.field_id = field_value.field_id)
			WHERE field_value.item_update_id = ?
		', $itemUpdateId);
	
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
	
	public function rebuildUpdateFieldValuesCache($itemUpdateId)
	{
		$cache = $this->getUpdateFieldValues($itemUpdateId);
	
		$this->db()->update('xf_xa_sc_item_update',
			['custom_fields' => json_encode($cache)],
			'item_update_id = ?', $itemUpdateId
		);
	}	
}