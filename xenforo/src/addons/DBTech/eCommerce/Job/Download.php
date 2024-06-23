<?php

namespace DBTech\eCommerce\Job;

use XF\Job\AbstractRebuildJob;

/**
 * Class Download
 *
 * @package DBTech\eCommerce\Job
 */
class Download extends AbstractRebuildJob
{
	/**
	 * @param $start
	 * @param $batch
	 *
	 * @return array
	 */
	protected function getNextIds($start, $batch): array
	{
		$db = $this->app->db();

		return $db->fetchAllColumn($db->limit(
			'
				SELECT download_id
				FROM xf_dbtech_ecommerce_download
				WHERE download_id > ?
				ORDER BY download_id
			',
			$batch
		), $start);
	}
	
	/**
	 * @param $id
	 *
	 * @throws \LogicException
	 * @throws \Exception
	 * @throws \XF\PrintableException
	 */
	protected function rebuildById($id)
	{
		/** @var \DBTech\eCommerce\Entity\Download $download */
		$download = $this->app->em()->find('DBTech\eCommerce:Download', $id);
		if ($download)
		{
			$download->rebuildCounters();
			$download->save();
		}
	}

	/**
	 * @return \XF\Phrase
	 */
	protected function getStatusType(): \XF\Phrase
	{
		return \XF::phrase('dbtech_ecommerce_ecommerce_downloads');
	}
}