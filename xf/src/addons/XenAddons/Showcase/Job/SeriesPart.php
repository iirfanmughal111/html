<?php

namespace XenAddons\Showcase\Job;

use XF\Job\AbstractRebuildJob;

class SeriesPart extends AbstractRebuildJob
{
	protected function getNextIds($start, $batch)
	{
		$db = $this->app->db();

		return $db->fetchAllColumn($db->limit(
			"
				SELECT series_part_id
				FROM xf_xa_sc_series_part
				WHERE series_part_id > ?
				ORDER BY series_part_id
			", $batch
		), $start);
	}

	protected function rebuildById($id)
	{
		/** @var \XenAddons\Showcase\Entity\SeriesPart $seriesPart */
		$seriesPart = $this->app->em()->find('XenAddons\Showcase:SeriesPart', $id, ['Item']);
		
		if ($seriesPart)
		{
			if ($seriesPart->Item)
			{
				// check to see if the item is visible, if not, we want to remove the item from the series using the Series Part Deleter Service!
				if (!$seriesPart->Item->isVisible())
				{
					/** @var \XenAddons\Showcase\Service\SeriesPart\Deleter $deleter */
					$deleter = $this->app->service('XenAddons\Showcase:SeriesPart\Deleter', $seriesPart);
					
					$deleter->delete();
				}
			}
		}
	}

	protected function getStatusType()
	{
		return \XF::phrase('xa_sc_series_part');
	}
}