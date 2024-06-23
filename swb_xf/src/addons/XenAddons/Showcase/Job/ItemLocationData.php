<?php

namespace XenAddons\Showcase\Job;

use XF\Job\AbstractRebuildJob;

class ItemLocationData extends AbstractRebuildJob
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
		if ($item && $item->location)
		{
			$item->rebuildLocationData();
			$item->save();
		}
	}

	protected function getStatusType()
	{
		return \XF::phrase('xa_sc_items');
	}
}