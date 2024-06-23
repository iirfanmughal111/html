<?php

namespace DBTech\eCommerce\Import\DataHelper;

use XF\Import\DataHelper\AbstractHelper;

/**
 * Class Download
 *
 * @package DBTech\eCommerce\Import\DataHelper
 */
class Download extends AbstractHelper
{
	/**
	 * @param $downloadId
	 * @param array $versionInfo
	 */
	public function importDownloadVersion($downloadId, array $versionInfo)
	{
		$this->importDownloadVersionBulk($downloadId, [$versionInfo]);
	}
	
	/**
	 * @param $downloadId
	 * @param array $versionConfigs
	 */
	public function importDownloadVersionBulk($downloadId, array $versionConfigs)
	{
		$insert = [];

		foreach ($versionConfigs AS $config)
		{
			$insert[] = [
				'download_id' => $downloadId,
				'product_id' => $config['product_id'],
				'product_version' => $config['product_version'],
				'product_version_type' => $config['product_version_type'],
				'directories' => !empty($config['directories']) ? $config['directories'] : '',
				'download_url' => !empty($config['download_url']) ? $config['download_url'] : '',
			];
		}

		if ($insert)
		{
			$this->db()->insertBulk(
				'xf_dbtech_ecommerce_download_version',
				$insert,
				false,
				'
					download_id = VALUES(download_id),
					product_id = VALUES(product_id),
					product_version = VALUES(product_version),
					product_version_type = VALUES(product_version_type),
					directories = VALUES(directories),
					download_url = VALUES(download_url)
				'
			);
		}
	}
}