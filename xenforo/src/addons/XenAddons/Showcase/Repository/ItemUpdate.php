<?php

namespace XenAddons\Showcase\Repository;

use XF\Mvc\Entity\Finder;
use XF\Mvc\Entity\Repository;
use XF\PrintableException;

class ItemUpdate extends Repository
{
	public function findUpdatesForItem(\XenAddons\Showcase\Entity\Item $item, array $limits = [])
	{
		/** @var \XenAddons\Showcase\Finder\ItemUpdate $finder */
		$finder = $this->finder('XenAddons\Showcase:ItemUpdate');
		$finder->inItem($item, $limits)
			->setDefaultOrder('update_date', 'desc');

		$userId = \XF::visitor()->user_id;
		if ($userId)
		{
			$finder->with('Reactions|' . $userId);
			$finder->with('Bookmarks|' . $userId);
		}
		
		return $finder;
	}

	public function findLatestUpdates(array $viewableCategoryIds = null, $cutOffDays = null)
	{
		/** @var \XenAddons\Showcase\Finder\ItemUpdate $finder */
		$finder = $this->finder('XenAddons\Showcase:ItemUpdate');

		$finder->where([
				'Item.item_state' => 'visible',
				'update_state' => 'visible',
			])
			->with('Item', true)
			->with(['Item.Category', 'User'])
			->setDefaultOrder('update_date', 'desc');
		
		if (is_array($viewableCategoryIds))
		{
			$finder->where('Item.category_id', $viewableCategoryIds);
		}
		else
		{
			$finder->with('Item.Category.Permissions|' . \XF::visitor()->permission_combination_id);
		}
		
		$userId = \XF::visitor()->user_id;
		if ($userId)
		{
			$finder->with('Reactions|' . $userId);
			$finder->with('Bookmarks|' . $userId);
		}
		
		if ($cutOffDays)
		{
			$cutOffDate = \XF::$time - ($cutOffDays * 86400);
			$finder->where('update_date', '>', $cutOffDate);
		}
		else
		{
			if ($this->options()->xaScLatestUpdatesCutOffDays)
			{
				$cutOffDate = \XF::$time - ($this->options()->xaScLatestUpdatesCutOffDays * 86400);
				$finder->where('update_date', '>', $cutOffDate);
			}
		}

		return $finder;
	}
	
	// This is its own function in case we need to do things differently for the Latest udpates Widget Definition! 
	public function findLatestUpdatesForWidget(array $viewableCategoryIds = null, $cutOffDays = null)
	{
		/** @var \XenAddons\Showcase\Finder\ItemUpdate $finder */
		$finder = $this->finder('XenAddons\Showcase:ItemUpdate');
	
		$finder->where([
				'Item.item_state' => 'visible',
				'update_state' => 'visible',
			])
			->with('Item', true)
			->with(['Item.Category', 'User'])
			->setDefaultOrder('update_date', 'desc');
	
		if (is_array($viewableCategoryIds))
		{
			$finder->where('Item.category_id', $viewableCategoryIds);
		}
		else
		{
			$finder->with('Item.Category.Permissions|' . \XF::visitor()->permission_combination_id);
		}
		
		// TODO check to make sure we need this for the Latest Updates Widget
		$userId = \XF::visitor()->user_id;
		if ($userId)
		{
			$finder->with('Reactions|' . $userId);
			$finder->with('Bookmarks|' . $userId);
		}
	
		if ($cutOffDays)
		{
			$cutOffDate = \XF::$time - ($cutOffDays * 86400);
			$finder->where('update_date', '>', $cutOffDate);
		}
	
		return $finder;
	}
	
	/**
	 * @param \XenAddons\Showcase\Entity\ItemUpdate $upadte
	 * @param array $limits
	 *
	 * @return \XenAddons\Showcase\Finder\ItemUpdateReply
	 */
	public function findItemUpdateReplies(\XenAddons\Showcase\Entity\ItemUpdate $update, array $limits = [])
	{
		/** @var \XenAddons\Showcase\Finder\ItemUpdateReply $replyFinder */
		$replyFinder = $this->finder('XenAddons\Showcase:ItemUpdateReply');
		$replyFinder->setDefaultOrder('reply_date');
		$replyFinder->forItemUpdate($update, $limits);
	
		return $replyFinder;
	}
	
	public function findNewestRepliesForItemUpdate(\XenAddons\Showcase\Entity\ItemUpdate $update, $newerThan, array $limits = [])
	{
		/** @var \XenAddons\Showcase\Finder\ItemUpdateReply $replyFinder */
		$replyFinder = $this->finder('XenAddons\Showcase:ItemUpdateReply');
		$replyFinder
			->setDefaultOrder('reply_date', 'DESC')
			->forItemUpdate($update, $limits)
			->newerThan($newerThan);
	
		return $replyFinder;
	}
	
	/**
	 * @param \XenAddons\Showcase\Entity\ItemUpdate[] $itemUpdates
	 */
	public function addRepliesToItemUpdates($itemUpdates)
	{
		$replyFinder = $this->finder('XenAddons\Showcase:ItemUpdateReply');
	
		$visitor = \XF::visitor();
	
		$ids = [];
		foreach ($itemUpdates AS $itemUpdateId => $itemUpdate)
		{
			$replyIds = $itemUpdate->latest_reply_ids;
			foreach ($replyIds AS $replyId => $state)
			{
				$replyId = intval($replyId);
	
				switch ($state[0])
				{
					case 'visible':
						$ids[] = $replyId;
						break;
	
					case 'moderated':
						if ($itemUpdate->canViewModeratedReplies())
						{
							// can view all moderated comments
							$ids[] = $replyId;
						}
						else if ($visitor->user_id && $visitor->user_id == $state[1])
						{
							// can view your own moderated comments
							$ids[] = $replyId;
						}
						break;
	
					case 'deleted':
						if ($itemUpdate->canViewDeletedReplies())
						{
							$ids[] = $replyId;
	
							$replyFinder->with('DeletionLog');
						}
						break;
				}
			}
		}
	
		if ($ids)
		{
			$replyFinder->with('full');
	
			$replies = $replyFinder
				->where('reply_id', $ids)
				->order('reply_date')
				->fetch()
				->groupBy('item_update_id');
	
			foreach ($itemUpdates AS $itemUpdateId => $itemUpdate)
			{
				$itemUpdateReplies = isset($replies[$itemUpdateId]) ? $replies[$itemUpdateId] : [];
				$itemUpdateReplies = $this->em->getBasicCollection($itemUpdateReplies)
				->filterViewable()
				->slice(-3, 3);
	
				$itemUpdate->setLatestReplies($itemUpdateReplies->toArray());
			}
		}
	
		return $itemUpdates;
	}
	
	public function addRepliesToItemUpdate(\XenAddons\Showcase\Entity\ItemUpdate $update)
	{
		$id = $update->item_update_id;
		$result = $this->addRepliesToItemUpdates([$id => $update]);
		return $result[$id];
	}
	
	public function getLatestReplyCache(\XenAddons\Showcase\Entity\ItemUpdate $update)
	{
		$replies = $this->finder('XenAddons\Showcase:ItemUpdateReply')
			->where('item_update_id', $update->item_update_id)
			->order('reply_date', 'DESC')
			->limit(20)
			->fetch();
	
		$visCount = 0;
		$latestReplies = [];
	
		/** @var \XenAddons\Showcase\Entity\ItemUpdateReply $reply */
		foreach ($replies AS $replyId => $reply)
		{
			if ($reply->reply_state == 'visible')
			{
				$visCount++;
			}
	
			$latestReplies[$replyId] = [$reply->reply_state, $reply->user_id];
	
			if ($visCount === 3)
			{
				break;
			}
		}
	
		return array_reverse($latestReplies, true);
	}
	
	public function getUpdatesImagesForItem(\XenAddons\Showcase\Entity\Item $item)
	{
		$db = $this->db();
	
		$ids = $db->fetchAllColumn("
			SELECT item_update_id
			FROM xf_xa_sc_item_update
			WHERE item_id = ?
			AND update_state = 'visible'
			AND attach_count > 0
			ORDER BY item_update_id
			", $item->item_id
		);
	
		if ($ids)
		{
			$attachments = $this->finder('XF:Attachment')
				->where([
					'content_type' => 'sc_update',
					'content_id' => $ids
				])
				->order('attach_date')
				->fetch();
		}
		else
		{
			$attachments = $this->em->getEmptyCollection();
		}
	
		return $attachments;
	}
	
	public function sendModeratorActionAlert(\XenAddons\Showcase\Entity\ItemUpdate $update, $action, $reason = '', array $extra = [], \XF\Entity\User $forceUser = null)
	{
		$item = $update->Item;

		if (!$item || !$item->user_id || !$item->User)
		{
			return false;
		}

		if (!$forceUser)
		{
			if (!$update->user_id || !$update->User)
			{
				return false;
			}
		
			$forceUser = $update->User;
		}
		
		$extra = array_merge([
			'title' => $update->title,
			'link' => $this->app()->router('public')->buildLink('nopath:showcase/update', $update),
			'itemTitle' => $item->title,
			'itemLink' => $this->app()->router('public')->buildLink('nopath:showcase', $item),
			'prefix_id' =>	$item->prefix_id,
			'reason' => $reason
		], $extra);

		/** @var \XF\Repository\UserAlert $alertRepo */
		$alertRepo = $this->repository('XF:UserAlert');
		$alertRepo->alert(
			$forceUser,
			0, '',
			'user', $forceUser->user_id,
			"sc_update_{$action}", $extra,
			['dependsOnAddOnId' => 'XenAddons/Showcase']
		);

		return true;
	}
	
	public function sendUpdateAlertToItemAuthor(\XenAddons\Showcase\Entity\ItemUpdate $update)
	{
		if (!$update->isVisible())
		{
			return false;
		}

		$item = $update->Item;
		$itemAuthor = $item->User;

		if (!$itemAuthor)
		{
			return false;
		}

		$senderId = $update->user_id;
		$senderName = $update->User ? $update->User->username : \XF::phrase('unknown')->render('raw');

		$alertRepo = $this->repository('XF:UserAlert');
		return $alertRepo->alert(
			$itemAuthor, $senderId, $senderName, 'sc_update', $update->item_update_id, 'update'
		);
	}


	// This is for update replies only!
	
	public function sendReplyModeratorActionAlert(\XenAddons\Showcase\Entity\ItemUpdateReply $reply, $action, $reason = '', array $extra = [], \XF\Entity\User $forceUser = null)
	{
		if (!$forceUser)
		{
			if (!$reply->user_id || !$reply->User)
			{
				return false;
			}
	
			$forceUser = $reply->User;
		}
	
		/** @var \XenAddons\Showcase\Entity\ItemUpdate $itemUpdate */
		$itemUpdate = $reply->ItemUpdate;
		$item = $itemUpdate->Item;
		if (!$itemUpdate)
		{
			return false;
		}
	
		$router = $this->app()->router('public');
	
		$extra = array_merge([
			'title' => $item->title,
			'prefix_id' => $item->prefix_id,
			'link' => $router->buildLink('nopath:showcase/update-reply', $reply),
			'update_title' => $itemUpdate->title,
			'updateLink' => $router->buildLink('nopath:showcase/update', $itemUpdate),
			'item_title' => $item->title,
			'itemLink' => $router->buildLink('nopath:showcase', $item),
			'reason' => $reason
		], $extra);
	
		/** @var \XF\Repository\UserAlert $alertRepo */
		$alertRepo = $this->repository('XF:UserAlert');
		$alertRepo->alert(
			$forceUser,
			0, '',
			'user', $forceUser->user_id,
			"sc_update_reply_{$action}", $extra
		);
	
		return true;
	}	
}