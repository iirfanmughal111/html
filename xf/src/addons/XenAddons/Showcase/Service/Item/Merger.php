<?php

namespace XenAddons\Showcase\Service\Item;

use XenAddons\Showcase\Entity\Item;
use XF\Entity\User;

class Merger extends \XF\Service\AbstractService
{
	/**
	 * @var \XenAddons\Showcase\Entity\Item
	 */
	protected $target;

	protected $alert = false;
	protected $alertReason = '';

	protected $log = true;

	protected $sourceItems = [];
	protected $sourceUpdates = [];
	protected $sourceComments = [];
	protected $sourceRatings = [];
	protected $sourceReviews = [];

	public function __construct(\XF\App $app, Item $target)
	{
		parent::__construct($app);

		$this->target = $target;
	}

	public function getTarget()
	{
		return $this->target;
	}

	public function setSendAlert($alert, $reason = null)
	{
		$this->alert = (bool)$alert;
		if ($reason !== null)
		{
			$this->alertReason = $reason;
		}
	}

	public function setLog($log)
	{
		$this->log = (bool)$log;
	}

	public function merge($sourceItemsRaw)
	{
		if ($sourceItemsRaw instanceof \XF\Mvc\Entity\AbstractCollection)
		{
			$sourceItemsRaw = $sourceItemsRaw->toArray();
		}
		else if ($sourceItemsRaw instanceof Item)
		{
			$sourceItemsRaw = [$sourceItemsRaw];
		}
		else if (!is_array($sourceItemsRaw))
		{
			throw new \InvalidArgumentException('Items must be provided as collection, array or entity');
		}

		if (!$sourceItemsRaw)
		{
			return false;
		}

		$db = $this->db();

		/** @var Item[] $sourceItems */
		$sourceItems = [];
		foreach ($sourceItemsRaw AS $sourceItem)
		{
			$sourceItem->setOption('log_moderator', false);
			$sourceItems[$sourceItem->item_id] = $sourceItem;
		}

		$updates = $db->fetchAllKeyed("
			SELECT item_update_id, item_id, user_id, update_state, reactions
			FROM xf_xa_sc_item_update
			WHERE item_id IN (" . $db->quote(array_keys($sourceItems)) . ")
		", 'item_update_id');
		
		$comments = $db->fetchAllKeyed("
			SELECT comment_id, item_id, user_id, comment_state, reactions
			FROM xf_xa_sc_comment
			WHERE item_id IN (" . $db->quote(array_keys($sourceItems)) . ")
		", 'comment_id');
		
		$ratings = $db->fetchAllKeyed("
			SELECT rating_id, item_id, user_id, rating_state, reactions
			FROM xf_xa_sc_item_rating
			WHERE item_id IN (" . $db->quote(array_keys($sourceItems)) . ")
				AND is_review = 0
		", 'rating_id');
		
		$reviews = $db->fetchAllKeyed("
			SELECT rating_id, item_id, user_id, rating_state, reactions
			FROM xf_xa_sc_item_rating
			WHERE item_id IN (" . $db->quote(array_keys($sourceItems)) . ")
				AND is_review = 1
		", 'rating_id');
		


		$this->sourceItems = $sourceItems;
		$this->sourceUpdates = $updates;
		$this->sourceComments = $comments;
		$this->sourceRatings = $ratings;
		$this->sourceReviews = $reviews;
		
		$target = $this->target;
		$target->setOption('log_moderator', false);

		$db->beginTransaction();

		$this->moveDataToTarget();
		$this->updateTargetData();
		$this->updateUserCounters();

		if ($this->alert)
		{
			$this->sendAlert();
		}

		foreach ($sourceItems AS $sourceItem)
		{
			$sourceItem->delete();
		}

		$this->finalActions();

		$db->commit();

		return true;
	}

	protected function moveDataToTarget()
	{
		$db = $this->db();
		$target = $this->target;

		$sourceUpdates = $this->sourceUpdates;
		$sourceComments = $this->sourceComments;
		$sourceRatings = $this->sourceRatings;
		$sourceReviews = $this->sourceReviews;
		
		$sourceItems = $this->sourceItems;
		$sourceItemIds = array_keys($sourceItems);
		$sourceIdsQuoted = $db->quote($sourceItemIds);

		$db->update('xf_xa_sc_item_rating',
			['item_id' => $target->item_id],
			"item_id IN ($sourceIdsQuoted)"
		);
		
		$db->update('xf_xa_sc_comment',
			['item_id' => $target->item_id],
			"item_id IN ($sourceIdsQuoted)"
		);
		
		$db->update('xf_xa_sc_item_update',
			['item_id' => $target->item_id],
			"item_id IN ($sourceIdsQuoted)"
		);
		
		$db->update('xf_xa_sc_series_part',
			['item_id' => $target->item_id],
			"item_id IN ($sourceIdsQuoted)",
			[], 'IGNORE'
		);
		
		$db->update('xf_xa_sc_series',
			['last_part_item_id' => $target->item_id],
			"last_part_item_id IN ($sourceIdsQuoted)",
			[], 'IGNORE'
		);
		
		$db->update('xf_xa_sc_item_watch',
			['item_id' => $target->item_id],
			"item_id IN ($sourceIdsQuoted)",
			[], 'IGNORE'
		);
		
		$db->update('xf_xa_sc_item_reply_ban',
			['item_id' => $target->item_id],
			"item_id IN ($sourceIdsQuoted)",
			[], 'IGNORE'
		);
		
		$db->update('xf_tag_content',
			['content_id' => $target->item_id],
			"content_type = 'sc_item' AND content_id IN ($sourceIdsQuoted)",
			[], 'IGNORE'
		);
	}

	protected function updateTargetData()
	{
		$db = $this->db();
		$target = $this->target;
		$sourceItems = $this->sourceItems;

		foreach ($sourceItems AS $sourceItem)
		{
			$target->view_count += $sourceItem->view_count;
		}

		$target->rebuildCounters();
		$target->save();

		/** @var \XF\Repository\Tag $tagRepo */
		$tagRepo = $this->repository('XF:Tag');
		$tagRepo->rebuildContentTagCache('sc_item', $target->item_id);
	}

	protected function updateUserCounters()
	{
		// nothing for now, unless she weighs more than a duck
	}

	protected function sendAlert()
	{
		$target = $this->target;
		$actor = \XF::visitor();

		/** @var \XenAddons\Showcase\Repository\Item $itemRepo */
		$itemRepo = $this->repository('XenAddons\Showcase:Item');

		$alertExtras = [
			'targetTitle' => $target->title,
			'targetLink' => $this->app->router('public')->buildLink('nopath:showcase', $target)
		];

		foreach ($this->sourceItems AS $sourceItem)
		{
			if ($sourceItem->item_state == 'visible'
				&& $sourceItem->user_id != $actor->user_id
			)
			{
				$itemRepo->sendModeratorActionAlert($sourceItem, 'merge', $this->alertReason, $alertExtras);
			}
		}
	}

	protected function finalActions()
	{
		$target = $this->target;
		$sourceItems = $this->sourceItems;
		$sourceItemIds = array_keys($sourceItems);
		$updateIds = array_keys($this->sourceUpdates);
		$commentIds = array_keys($this->sourceComments);
		$ratingIds = array_keys($this->sourceRatings);
		$reviewIds = array_keys($this->sourceReviews);
		
		if ($updateIds)
		{
			$this->app->jobManager()->enqueue('XF:SearchIndex', [
				'content_type' => 'sc_update',
				'content_ids' => $updateIds
			]);
		}
		
		if ($commentIds)
		{
			$this->app->jobManager()->enqueue('XF:SearchIndex', [
				'content_type' => 'sc_comment',
				'content_ids' => $commentIds
			]);
		}

		if ($reviewIds)
		{
			$this->app->jobManager()->enqueue('XF:SearchIndex', [
				'content_type' => 'sc_rating',
				'content_ids' => $reviewIds
			]);
		}
		
		if ($this->log)
		{
			$this->app->logger()->logModeratorAction('sc_item', $target, 'merge_target',
				['ids' => implode(', ', $sourceItemIds)]
			);
		}
	}
}