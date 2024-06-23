<?php

namespace DBTech\eCommerce\Import\DataHelper;

use XF\Import\DataHelper\AbstractHelper;

/**
 * Class DownloadLog
 *
 * @package DBTech\eCommerce\Import\DataHelper
 */
class DownloadLog extends AbstractHelper
{
	/**
	 * @param $downloadId
	 * @param array $itemInfo
	 */
	public function importProductDownload($downloadId, array $itemInfo)
	{
		$this->importProductDownloadBulk($downloadId, [$itemInfo]);
	}
	
	/**
	 * @param $downloadId
	 * @param array $itemConfigs
	 */
	public function importProductDownloadBulk($downloadId, array $itemConfigs)
	{
		$insert = [];
		
		foreach ($itemConfigs AS $config)
		{
			$insert[] = [
				'download_id' => $downloadId,
				'user_id' => $config['user_id'],
				'product_id' => $config['product_id'],
				'last_download_date' => $config['last_download_date']
			];
		}
		
		if ($insert)
		{
			$this->db()->insertBulk(
				'xf_dbtech_ecommerce_product_download',
				$insert,
				false,
				'
					download_id = VALUES(download_id),
					user_id = VALUES(user_id),
					product_id = VALUES(product_id),
					last_download_date = GREATEST(last_download_date, VALUES(last_download_date))
				'
			);
		}
	}
	
	/**
	 * @param $downloadLogId
	 * @param array $itemInfo
	 */
	public function importLicenseFieldValue($downloadLogId, array $itemInfo)
	{
		$this->importLicenseFieldValueBulk($downloadLogId, [$itemInfo]);
	}
	
	/**
	 * @param $downloadLogId
	 * @param array $itemConfigs
	 */
	public function importLicenseFieldValueBulk($downloadLogId, array $itemConfigs)
	{
		$insert = [];

		foreach ($itemConfigs AS $config)
		{
			$insert[] = [
				'download_log_id' => $downloadLogId,
				'field_id' => $config['field_id'],
				'field_value' => $config['field_value']
			];
		}

		if ($insert)
		{
			$this->db()->insertBulk(
				'xf_dbtech_ecommerce_download_log_field_value',
				$insert,
				false,
				'download_log_id = VALUES(download_log_id), field_id = VALUES(field_id), field_value = VALUES(field_value)'
			);
		}
	}
}