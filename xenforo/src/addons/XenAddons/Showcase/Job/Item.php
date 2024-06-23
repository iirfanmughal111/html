<?php

namespace XenAddons\Showcase\Job;

use XF\Job\AbstractRebuildJob;

class Item extends AbstractRebuildJob
{
	protected function getNextIds($start, $batch)
	{
		$db = $this->app->db();

		return $db->fetchAllColumn($db->limit(
			"
				SELECT item_id
				FROM xf_xa_sc_item
				WHERE item_id > ?
				ORDER BY item_id
			", $batch
		), $start);
	}

	protected function rebuildById($id)
	{
		/** @var \XenAddons\Showcase\Entity\Item $item */
		$item = $this->app->em()->find('XenAddons\Showcase:Item', $id);
		if ($item)
		{
			$item->rebuildCounters();
			$item->updateRatingAverage();
			$item->updateCoverImageIfNeeded();
			$item->save();
			
			/** @var \XenAddons\Showcase\Repository\ItemWatch $itemWatchRepo */
			$itemWatchRepo = $this->app->repository('XenAddons\Showcase:ItemWatch');
			
			$itemWatchRepo->updateItemRecordWatchCount($item);
		}
	}

	protected function getStatusType()
	{
		return \XF::phrase('xa_sc_items');
	}
}