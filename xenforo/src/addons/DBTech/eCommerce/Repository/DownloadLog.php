<?php

namespace DBTech\eCommerce\Repository;

use XF\Mvc\Entity\Repository;

/**
 * Class DownloadLog
 * @package DBTech\eCommerce\Repository
 */
class DownloadLog extends Repository
{
	/**
	 * @param int $downloadLogId
	 * @return array
	 */
	public function getDownloadLogValues(int $downloadLogId): array
	{
		$fields = $this->db()->fetchAll('
			SELECT field_value.*, field.field_type
			FROM xf_dbtech_ecommerce_download_log_field_value AS field_value
			INNER JOIN xf_dbtech_ecommerce_license_field AS field ON (field.field_id = field_value.field_id)
			WHERE field_value.download_log_id = ?
		', $downloadLogId);

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
	 * @param int $downloadLogId
	 */
	public function rebuildDownloadLogValuesCache(int $downloadLogId)
	{
		$cache = $this->getDownloadLogValues($downloadLogId);

		$this->db()->update(
			'xf_dbtech_ecommerce_download_log',
			['license_fields' => json_encode($cache)],
			'download_log_id = ?',
			$downloadLogId
		);
	}
}