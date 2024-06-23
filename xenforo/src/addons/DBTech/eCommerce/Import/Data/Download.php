<?php

namespace DBTech\eCommerce\Import\Data;

use XF\Import\Data\AbstractEmulatedData;
use XF\Import\Data\HasDeletionLogTrait;

/**
 * Class Download
 *
 * @package DBTech\eCommerce\Import\Data
 */
class Download extends AbstractEmulatedData
{
	use HasDeletionLogTrait;
	
	/**
	 * @return string
	 */
	public function getImportType(): string
	{
		return 'download';
	}
	
	/**
	 * @return string
	 */
	protected function getEntityShortName(): string
	{
		return 'DBTech\eCommerce:Download';
	}
	
	/**
	 * @param $oldId
	 * @param $newId
	 */
	protected function postSave($oldId, $newId)
	{
		$this->insertStateRecord($this->download_state, $this->release_date);
	}
}