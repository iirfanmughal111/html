<?php

namespace XenAddons\Showcase\Job;

use XF\Job\AbstractRebuildJob;

class Review extends AbstractRebuildJob
{
	protected function getNextIds($start, $batch)
	{
		$db = $this->app->db();

		return $db->fetchAllColumn($db->limit(
			"
				SELECT rating_id
				FROM xf_xa_sc_item_rating
				WHERE rating_id > ?
				AND is_review = 1
				ORDER BY rating_id
			", $batch
		), $start);
	}

	protected function rebuildById($id)
	{
		/** @var \XenAddons\Showcase\Entity\ItemRating $review */
		$review = $this->app->em()->find('XenAddons\Showcase:ItemRating', $id);
		if ($review)
		{
			$review->rebuildCounters();
			$review->save();
		}
	}

	protected function getStatusType()
	{
		return \XF::phrase('xa_sc_reviews');
	}
}