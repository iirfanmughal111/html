<?php

namespace DBTech\eCommerce\Repository;

use XF\Repository\AbstractField;

/**
 * Class OrderField
 * @package DBTech\eCommerce\Repository
 */
class OrderField extends AbstractField
{
	/**
	 * @return string
	 */
	protected function getRegistryKey(): string
	{
		return 'dbtEcOrderFieldsInfo';
	}

	/**
	 * @return string
	 */
	protected function getClassIdentifier(): string
	{
		return 'DBTech\eCommerce:OrderField';
	}

	/**
	 * @return array
	 */
	public function getDisplayGroups(): array
	{
		return [
			'above_terms' => \XF::phrase('dbtech_ecommerce_above_terms_and_conditions')
		];
	}

	/**
	 * @param int $orderItemId
	 * @return array
	 */
	public function getOrderFieldValues(int $orderItemId): array
	{
		$fields = $this->db()->fetchAll('
			SELECT field_value.*, field.field_type
			FROM xf_dbtech_ecommerce_order_field_value AS field_value
			INNER JOIN xf_dbtech_ecommerce_order_field AS field ON (field.field_id = field_value.field_id)
			WHERE field_value.order_item_id = ?
		', $orderItemId);

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
}