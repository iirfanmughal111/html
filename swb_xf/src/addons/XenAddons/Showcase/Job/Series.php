<?php

namespace XenAddons\Showcase\Job;

use XF\Job\AbstractRebuildJob;

class Series extends AbstractRebuildJob
{
	protected function getNextIds($start, $batch)
	{
		$db = $this->app->db();

		return $db->fetchAllColumn($db->limit(
			"
				SELECT series_id
				FROM xf_xa_sc_series
				WHERE series_id > ?
				ORDER BY series_id
			", $batch
		), $start);
	}

	protected function rebuildById($id)
	{
		/** @var \XenAddons\Showcase\Entity\SeriesItem $series */
		$series = $this->app->em()->find('XenAddons\Showcase:SeriesItem', $id);
		if ($series)
		{
			$series->rebuildCounters();
			$series->save();
			
			/** @var \XenAddons\Showcase\Repository\SeriesWatch $seriesWatchRepo */
			$seriesWatchRepo = $this->app->repository('XenAddons\Showcase:SeriesWatch');
			$seriesWatchRepo->updateSeriesRecordWatchCount($series);
		}
	}

	protected function getStatusType()
	{
		return \XF::phrase('xa_sc_series');
	}
}