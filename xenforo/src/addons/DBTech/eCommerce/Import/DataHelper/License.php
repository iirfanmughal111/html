<?php

namespace DBTech\eCommerce\Import\DataHelper;

use XF\Import\DataHelper\AbstractHelper;

/**
 * Class License
 *
 * @package DBTech\eCommerce\Import\DataHelper
 */
class License extends AbstractHelper
{
	/**
	 * @param $licenseId
	 * @param array $itemInfo
	 */
	public function importLicenseFieldValue($licenseId, array $itemInfo)
	{
		$this->importLicenseFieldValueBulk($licenseId, [$itemInfo]);
	}
	
	/**
	 * @param $licenseId
	 * @param array $itemConfigs
	 */
	public function importLicenseFieldValueBulk($licenseId, array $itemConfigs)
	{
		$insert = [];

		foreach ($itemConfigs AS $config)
		{
			$insert[] = [
				'license_id' => $licenseId,
				'field_id' => $config['field_id'],
				'field_value' => $config['field_value']
			];
		}

		if ($insert)
		{
			$this->db()->insertBulk(
				'xf_dbtech_ecommerce_license_field_value',
				$insert,
				false,
				'license_id = VALUES(license_id), field_id = VALUES(field_id), field_value = VALUES(field_value)'
			);
		}
	}
}