<?php

namespace XenAddons\Showcase\Cron;

class Statistics
{
	public static function cacheShowcaseStatistics()
	{
		$simpleCache = \XF::app()->simpleCache();
		$db = \XF::db();

		$categoryCount = $db->fetchOne('
			SELECT COUNT(*)
			FROM xf_xa_sc_category
		');

		$itemCount = $db->fetchOne('
			SELECT COUNT(*)
			FROM xf_xa_sc_item 
			WHERE item_state = \'visible\'
		');
		
		$seriesCount = $db->fetchOne('
			SELECT COUNT(*)
			FROM xf_xa_sc_series
		');
		
		$viewCount = $db->fetchOne('
			SELECT SUM(view_count)
			FROM xf_xa_sc_item
			WHERE item_state = \'visible\'
		');

		$commentCount = $db->fetchOne('
			SELECT COUNT(*)
			FROM xf_xa_sc_comment AS comment
			LEFT JOIN xf_xa_sc_item AS item ON
				(comment.item_id = item.item_id)
			WHERE comment.comment_state = \'visible\'
		');
		
		$ratingCount = $db->fetchOne('
			SELECT COUNT(*)
			FROM xf_xa_sc_item_rating AS rating
			LEFT JOIN xf_xa_sc_item AS item ON
				(rating.item_id = item.item_id)
			WHERE rating.rating_state = \'visible\'
		');	

		$reviewCount = $db->fetchOne('
			SELECT COUNT(*)
			FROM xf_xa_sc_item_rating AS rating
			LEFT JOIN xf_xa_sc_item AS item ON
				(rating.item_id = item.item_id)
			WHERE rating.rating_state = \'visible\'
				AND rating.is_review = 1
		');

		$simpleCache['XenAddons/Showcase']['statisticsCache'] = [
			'category_count' => $categoryCount,
			'item_count' => $itemCount,
			'series_count' => $seriesCount,
			'view_count' => $viewCount,
			'comment_count' => $commentCount,
			'rating_count' => $ratingCount,
			'review_count' => $reviewCount,
		];
	}
}