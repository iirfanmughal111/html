<?php

namespace XenAddons\Showcase\Service\Review;

use XenAddons\Showcase\Entity\ItemRating;
use XenAddons\Showcase\Entity\Item;
use XF\Entity\User;

class Mover extends \XF\Service\AbstractService
{
	/**
	 * @var Item
	 */
	protected $target;

	protected $existingTarget = false;

	protected $alert = false;
	protected $alertReason = '';

	protected $log = true;

	/**
	 * @var Item[]
	 */
	protected $sourceItems = [];

	/**
	 * @var ItemRating[]
	 */
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

	public function setExistingTarget($existing)
	{
		$this->existingTarget = (bool)$existing;
	}

	public function setLog($log)
	{
		$this->log = (bool)$log;
	}

	public function setSendAlert($alert, $reason = null)
	{
		$this->alert = (bool)$alert;
		if ($reason !== null)
		{
			$this->alertReason = $reason;
		}
	}

	public function move($sourceReviewsRaw)
	{
		if ($sourceReviewsRaw instanceof \XF\Mvc\Entity\AbstractCollection)
		{
			$sourceReviewsRaw = $sourceReviewsRaw->toArray();
		}
		else if ($sourceReviewsRaw instanceof ItemRating)
		{
			$sourceReviewsRaw = [$sourceReviewsRaw];
		}
		else if (!is_array($sourceReviewsRaw))
		{
			throw new \InvalidArgumentException('Reviews must be provided as collection, array or entity');
		}

		if (!$sourceReviewsRaw)
		{
			return false;
		}

		$db = $this->db();

		/** @var ItemRating[] $sourceReviews */
		/** @var Item[] $sourceItems */
		$sourceReviews = [];
		$sourceItems = [];

		$target = $this->target;

		foreach ($sourceReviewsRaw AS $sourceReview)
		{
			if ($sourceReview->item_id == $target->item_id)
			{
				continue;
			}

			$sourceReview->setOption('log_moderator', false);
			$sourceReviews[$sourceReview->rating_id] = $sourceReview;

			/** @var Item $sourceItem */
			$sourceItem = $sourceReview->Item;
			if ($sourceItem && !isset($sourceItems[$sourceItem->item_id]))
			{
				$sourceItem->setOption('log_moderator', false);
				$sourceItems[$sourceItem->item_id] = $sourceItem;
			}
		}

		if (!$sourceReviews)
		{
			return false; // nothing to do
		}

		$sourceReviews = \XF\Util\Arr::columnSort($sourceReviews, 'rating_date');

		$this->sourceItems = $sourceItems;
		$this->sourceReviews = $sourceReviews;

		$target->setOption('log_moderator', false);

		$db->beginTransaction();

		$target->save();

		$this->moveDataToTarget();
		$this->updateTargetData();
		$this->updateSourceData();
		$this->updateUserCounters();

		if ($this->alert)
		{
			$this->sendAlert();
		}

		$this->finalActions();

		$db->commit();

		return true;
	}

	protected function moveDataToTarget()
	{
		$db = $this->db();
		$target = $this->target;
		$sourceReviewIds = array_keys($this->sourceReviews);
		$sourceIdsQuoted = $db->quote($sourceReviewIds);

		$db->update('xf_xa_sc_item_rating',
			['item_id' => $target->item_id],
			"rating_id IN ($sourceIdsQuoted)"
		);
	}

	protected function updateTargetData()
	{
		$target = $this->target;

		$target->rebuildCounters();
		$target->save();

		$target->Category->rebuildCounters();
		$target->Category->save();
	}

	protected function updateSourceData()
	{
		/** @var \XenAddons\Showcase\Repository\Item $itemRepo */
		$itemRepo = $this->repository('XenAddons\Showcase:Item');

		foreach ($this->sourceItems AS $sourceItem)
		{
			$sourceItem->rebuildCounters();

			$sourceItem->save(); // has to be saved for the delete to work (if needed).

			$sourceItem->Category->rebuildCounters();
			$sourceItem->Category->save();
		}
	}

	protected function updateUserCounters()
	{
		// TODO force run the cache rebuild Showcase: Rebuild user counts?
	}

	protected function sendAlert()
	{
		$target = $this->target;

		/** @var \XenAddons\Showcase\Repository\ItemRating $ratingRepo */
		$ratingRepo = $this->repository('XenAddons\Showcase:ItemRating');

		foreach ($this->sourceReviews AS $sourceReview)
		{
			if ($sourceReview->Item->item_state == 'visible'
				&& $sourceReview->rating_state == 'visible'
				&& $sourceReview->user_id != \XF::visitor()->user_id
			)
			{
				$targetReview = clone $sourceReview;
				$targetReview->setAsSaved('item_id', $target->item_id);

				$alertExtras = [
					'sourceTitle' => $sourceReview->Item->title,
					'targetLink' => $this->app->router('public')->buildLink('nopath:showcase/review', $sourceReview)
				];

				$ratingRepo->sendModeratorActionAlert($targetReview, 'move', $this->alertReason, $alertExtras);
			}
		}
	}

	protected function finalActions()
	{
		$target = $this->target;
		$reviewIds = array_keys($this->sourceReviews);

		if ($reviewIds)
		{
			$this->app->jobManager()->enqueue('XF:SearchIndex', [
				'content_type' => 'sc_rating',
				'content_ids' => $reviewIds
			]);
		}

		if ($this->log)
		{
			$this->app->logger()->logModeratorAction('sc_item', $target, 'review_move_target' . ($this->existingTarget ? '_exist' : ''),
				['ids' => implode(', ', $reviewIds)]
			);

			foreach ($this->sourceItems AS $sourceItem)
			{
				$this->app->logger()->logModeratorAction('sc_item', $sourceItem, 'review_move_source', [
					'url' => $this->app->router('public')->buildLink('nopath:showcase', $sourceItem),
					'title' => $target->title
				]);
			}
		}
	}
}