<?php

namespace DBTech\eCommerce\Repository;

use XF\Repository\AbstractField;

/**
 * Class ProductField
 * @package DBTech\eCommerce\Repository
 */
class ProductField extends AbstractField
{
	/**
	 * @return string
	 */
	protected function getRegistryKey(): string
	{
		return 'dbtEcProductFieldsInfo';
	}

	/**
	 * @return string
	 */
	protected function getClassIdentifier(): string
	{
		return 'DBTech\eCommerce:ProductField';
	}

	/**
	 * @return array
	 */
	public function getDisplayGroups(): array
	{
		return [
			'above_main' => \XF::phrase('dbtech_ecommerce_above_main_info'),
			'below_main' => \XF::phrase('dbtech_ecommerce_below_main_info'),
			'above_info' => \XF::phrase('dbtech_ecommerce_above_product_info'),
			'below_info' => \XF::phrase('dbtech_ecommerce_below_product_info'),
			'new_tab' => \XF::phrase('dbtech_ecommerce_own_tab'),
			'custom' => \XF::phrase('dbtech_ecommerce_custom_location_manual_insert')
		];
	}

	/**
	 * @param int $productId
	 * @return array
	 */
	public function getProductFieldValues(int $productId): array
	{
		$fields = $this->db()->fetchAll('
			SELECT field_value.*, field.field_type
			FROM xf_dbtech_ecommerce_product_field_value AS field_value
			INNER JOIN xf_dbtech_ecommerce_product_field AS field ON (field.field_id = field_value.field_id)
			WHERE field_value.product_id = ?
		', $productId);

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
	
	/**
	 * @param int $productId
	 */
	public function rebuildProductFieldValuesCache(int $productId)
	{
		$cache = $this->getProductFieldValues($productId);
		
		$this->db()->update(
			'xf_dbtech_ecommerce_product',
			['product_fields' => json_encode($cache)],
			'product_id = ?',
			$productId
		);
	}
}