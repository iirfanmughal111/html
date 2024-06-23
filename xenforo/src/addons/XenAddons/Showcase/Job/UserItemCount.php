<?php

namespace XenAddons\Showcase\Job;

use XF\Job\AbstractRebuildJob;

class UserItemCount extends AbstractRebuildJob
{
	protected function getNextIds($start, $batch)
	{
		$db = $this->app->db();

		return $db->fetchAllColumn($db->limit(
			"
				SELECT user_id
				FROM xf_user
				WHERE user_id > ?
				ORDER BY user_id
			", $batch
		), $start);
	}

	protected function rebuildById($id)
	{
		/** @var \XenAddons\Showcase\Repository\Item $itemRepo */
		$itemRepo = $this->app->repository('XenAddons\Showcase:Item');
		$itemCount = $itemRepo->getUserItemCount($id);
		
		/** @var \XenAddons\Showcase\Repository\Comment $commentRepo */
		$commentRepo = $this->app->repository('XenAddons\Showcase:Comment');
		$commentCount = $commentRepo->getUserCommentCount($id);
		
		/** @var \XenAddons\Showcase\Repository\ItemRating $itemRatingRepo */
		$itemRatingRepo = $this->app->repository('XenAddons\Showcase:ItemRating');
		$reviewCount = $itemRatingRepo->getUserReviewCount($id);
		
		/** @var \XenAddons\Showcase\Repository\Series $seriesRepo */
		$seriesRepo = $this->app->repository('XenAddons\Showcase:Series');
		$seriesCount = $seriesRepo->getUserSeriesCount($id);
		
		$this->app->db()->update('xf_user', [
			'xa_sc_item_count' => $itemCount,
			'xa_sc_comment_count' => $commentCount,
			'xa_sc_review_count' => $reviewCount,
			'xa_sc_series_count' => $seriesCount
		], 'user_id = ?', $id);
	}

	protected function getStatusType()
	{
		return \XF::phrase('xa_sc_user_counts');
	}
}