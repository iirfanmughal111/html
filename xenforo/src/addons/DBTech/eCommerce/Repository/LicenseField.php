<?php

namespace DBTech\eCommerce\Repository;

use XF\Repository\AbstractField;

/**
 * Class LicenseField
 * @package DBTech\eCommerce\Repository
 */
class LicenseField extends AbstractField
{
	/**
	 * @return string
	 */
	protected function getRegistryKey(): string
	{
		return 'dbtEcLicenseFieldsInfo';
	}

	/**
	 * @return string
	 */
	protected function getClassIdentifier(): string
	{
		return 'DBTech\eCommerce:LicenseField';
	}

	/**
	 * @return array
	 */
	public function getDisplayGroups(): array
	{
		return [
			'hidden' => \XF::phrase('dbtech_ecommerce_hidden'),
			'info' => \XF::phrase('dbtech_ecommerce_license_info'),
			'list' => \XF::phrase('dbtech_ecommerce_license_info_and_list')
		];
	}

	/**
	 * @param int $licenseId
	 * @return array
	 */
	public function getLicenseFieldValues(int $licenseId): array
	{
		$fields = $this->db()->fetchAll('
			SELECT field_value.*, field.field_type
			FROM xf_dbtech_ecommerce_license_field_value AS field_value
			INNER JOIN xf_dbtech_ecommerce_license_field AS field ON (field.field_id = field_value.field_id)
			WHERE field_value.license_id = ?
		', $licenseId);

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
	 * @param int $licenseId
	 */
	public function rebuildLicenseFieldValuesCache(int $licenseId)
	{
		$cache = $this->getLicenseFieldValues($licenseId);

		$this->db()->update(
			'xf_dbtech_ecommerce_license',
			['license_fields' => json_encode($cache)],
			'license_id = ?',
			$licenseId
		);
	}
}