<?php

namespace XenAddons\Showcase\Job;

use XF\Job\AbstractRebuildJob;

class ItemUpdate extends AbstractRebuildJob
{
	protected function getNextIds($start, $batch)
	{
		$db = $this->app->db();

		return $db->fetchAllColumn($db->limit(
			"
				SELECT item_update_id
				FROM xf_xa_sc_item_update
				WHERE item_update_id > ?
				ORDER BY item_update_id
			", $batch
		), $start);
	}

	protected function rebuildById($id)
	{
		/** @var \XenAddons\Showcase\Entity\ItemUpdate $update */
		$update = $this->app->em()->find('XenAddons\Showcase:ItemUpdate', $id);
		if ($update)
		{
			$update->rebuildCounters();
			$update->save();
		}
	}

	protected function getStatusType()
	{
		return \XF::phrase('xa_sc_item_updates');
	}
}