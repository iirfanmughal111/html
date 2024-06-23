<?php

namespace DBTech\eCommerce\Import\Data;

use XF\Import\Data\AbstractEmulatedData;

/**
 * Class DownloadLog
 *
 * @package DBTech\eCommerce\Import\Data
 */
class DownloadLog extends AbstractEmulatedData
{
	/**
	 * @var string|null
	 */
	protected $loggedIp;
	
	/**
	 * @return string
	 */
	public function getImportType(): string
	{
		return 'download_log';
	}
	
	/**
	 * @return string
	 */
	protected function getEntityShortName(): string
	{
		return 'DBTech\eCommerce:DownloadLog';
	}
	
	/**
	 * @param $loggedIp
	 */
	public function setLoggedIp($loggedIp)
	{
		$this->loggedIp = $loggedIp;
	}
	
	/**
	 * @param $oldId
	 * @param $newId
	 */
	protected function postSave($oldId, $newId)
	{
		$this->logIp($this->loggedIp, $this->log_date, [
			'action' => 'download',
		]);
		
		if ($this->user_id)
		{
			/** @var \DBTech\eCommerce\Import\DataHelper\DownloadLog $downloadLogHelper */
			$downloadLogHelper = $this->dataManager->helper('DBTech\eCommerce:DownloadLog');
			$downloadLogHelper->importProductDownload($this->download_id, [
				'user_id'            => $this->user_id,
				'product_id'         => $this->product_id,
				'last_download_date' => $this->log_date
			]);
		}
	}
}