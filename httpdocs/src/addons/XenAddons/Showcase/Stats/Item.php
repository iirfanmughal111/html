<?php

namespace XenAddons\Showcase\Stats;

use XF\Stats\AbstractHandler;

class Item extends AbstractHandler
{
	public function getStatsTypes()
	{
		return [
			'sc_item' => \XF::phrase('xa_sc_items'),
			'sc_item_reaction' => \XF::phrase('xa_sc_item_reactions'),
						
			'sc_update' => \XF::phrase('xa_sc_updates'),
			'sc_update_reaction' => \XF::phrase('xa_sc_update_reactions'),
			'sc_update_reply' => \XF::phrase('xa_sc_update_replies'),
			'sc_update_reply_reaction' => \XF::phrase('xa_sc_update_reply_reactions'),
			
			'sc_comment' => \XF::phrase('xa_sc_comments'),
			'sc_comment_reaction' => \XF::phrase('xa_sc_comment_reactions'),
			
			'sc_rating' => \XF::phrase('xa_sc_ratings'),
			'sc_rating_reaction' => \XF::phrase('xa_sc_rating_reactions'),
			'sc_rating_reply' => \XF::phrase('xa_sc_rating_replies'),
			'sc_rating_reply_reaction' => \XF::phrase('xa_sc_rating_reply_reactions'),
			
			'sc_series' => \XF::phrase('xa_sc_series'),
			'sc_series_part' => \XF::phrase('xa_sc_series_parts'),
			'sc_series_reaction' => \XF::phrase('xa_sc_series_reactions')
		];
	}

	public function getData($start, $end)
	{
		$db = $this->db();

		// ITEMS .....
		
		$items = $db->fetchPairs(
			$this->getBasicDataQuery('xf_xa_sc_item', 'create_date', 'item_state = ?'),
			[$start, $end, 'visible']
		);
		
		$itemReactions = $db->fetchPairs(
				$this->getBasicDataQuery('xf_reaction_content', 'reaction_date', 'content_type = ?'),
				[$start, $end, 'sc_item']
		);
		

		// ITEM UPDATES.... 
		
		$updates = $db->fetchPairs(
			$this->getBasicDataQuery('xf_xa_sc_item_update', 'update_date', 'update_state = ?'),
			[$start, $end, 'visible']
		);
		
		$updateReactions = $db->fetchPairs(
			$this->getBasicDataQuery('xf_reaction_content', 'reaction_date', 'content_type = ?'),
			[$start, $end, 'sc_update']
		);
		
		$updateReplies = $db->fetchPairs(
			$this->getBasicDataQuery('xf_xa_sc_item_update_reply', 'reply_date', 'reply_state = ?'),
			[$start, $end, 'visible']
		);
		
		$updateReplyReactions = $db->fetchPairs(
			$this->getBasicDataQuery('xf_reaction_content', 'reaction_date', 'content_type = ?'),
			[$start, $end, 'sc_update_reply']
		);		
		
		
		// ITEM COMMENTS....
				
		$comments = $db->fetchPairs(
			$this->getBasicDataQuery('xf_xa_sc_comment', 'comment_date', 'comment_state = ?'),
			[$start, $end, 'visible']
		);
		
		$commentReactions = $db->fetchPairs(
			$this->getBasicDataQuery('xf_reaction_content', 'reaction_date', 'content_type = ?'),
			[$start, $end, 'sc_comment']
		);		
		
		
		// ITEM RATINGS....

		$ratings = $db->fetchPairs(
			$this->getBasicDataQuery('xf_xa_sc_item_rating', 'rating_date', 'rating_state = ?'),
			[$start, $end, 'visible']
		);
		
		$ratingReactions = $db->fetchPairs(
			$this->getBasicDataQuery('xf_reaction_content', 'reaction_date', 'content_type = ?'),
			[$start, $end, 'sc_rating']
		);
		
		$ratingReplies = $db->fetchPairs(
			$this->getBasicDataQuery('xf_xa_sc_item_rating_reply', 'reply_date', 'reply_state = ?'),
			[$start, $end, 'visible']
		);
		
		$ratingReplyReactions = $db->fetchPairs(
			$this->getBasicDataQuery('xf_reaction_content', 'reaction_date', 'content_type = ?'),
			[$start, $end, 'sc_rating_reply']
		);
		

		// ITEM SERIES....
		
		$series = $db->fetchPairs(
			$this->getBasicDataQuery('xf_xa_sc_series', 'create_date', ''),
			[$start, $end]
		);
		
		$seriesParts = $db->fetchPairs(
			$this->getBasicDataQuery('xf_xa_sc_series_part', 'create_date', ''),
			[$start, $end]
		);
		
		$seriesReactions = $db->fetchPairs(
			$this->getBasicDataQuery('xf_reaction_content', 'reaction_date', 'content_type = ?'),
			[$start, $end, 'sc_series']
		);
		
		
		return [
			'sc_item' => $items,
			'sc_item_reaction' => $itemReactions,
			
			'sc_update' => $updates,	
			'sc_update_reaction' => $updateReactions,
			'sc_update_reply' => $updateReplies,
			'sc_update_reply_reaction' => $updateReplyReactions,
	
			'sc_comment' => $comments,
			'sc_comment_reaction' => $commentReactions,
			
			'sc_rating' => $ratings,
			'sc_rating_reaction' => $ratingReactions,
			'sc_rating_reply' => $ratingReplies,
			'sc_rating_reply_reaction' => $ratingReplyReactions,
			

			'sc_series' => $series,
			'sc_series_part' => $seriesParts,
			'sc_series_reaction' => $seriesReactions,
		];
	}
}