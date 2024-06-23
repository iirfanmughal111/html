<?php

namespace XenAddons\Showcase\Repository;

use XF\Mvc\Entity\ArrayCollection;
use XF\Mvc\Entity\Finder;
use XF\Mvc\Entity\Repository;
use XF\PrintableException;
use XF\Util\Arr;

class Item extends Repository
{
	public function findItemsForItemList(array $viewableCategoryIds = null, array $limits = [], \XenAddons\Showcase\Entity\Category $category = null)
	{
		$limits = array_replace([
			'visibility' => true,
			'allowOwnPending' => false
		], $limits);

		/** @var \XenAddons\Showcase\Finder\Item $itemFinder */
		$itemFinder = $this->finder('XenAddons\Showcase:Item');

		if (is_array($viewableCategoryIds))
		{
			$itemFinder->where('category_id', $viewableCategoryIds);
		}
		else
		{
			$itemFinder->with('Category.Permissions|' . \XF::visitor()->permission_combination_id);
		}

		$itemFinder
			->with(['full', 'fullCategory'])
			->useDefaultOrder($category);

		if ($limits['visibility'])
		{
			$itemFinder->applyGlobalVisibilityChecks($limits['allowOwnPending']);
		}

		return $itemFinder;
	}
	
	public function findItemsForIndexMap(array $viewableCategoryIds = null, $itemType = '')
	{
		/** @var \XenAddons\Showcase\Finder\Item $itemFinder */
		$itemFinder = $this->finder('XenAddons\Showcase:Item');
	
		if (is_array($viewableCategoryIds))
		{
			$itemFinder->where('category_id', $viewableCategoryIds);
		}
		else
		{
			$itemFinder->with('Category.Permissions|' . \XF::visitor()->permission_combination_id);
		}
	
		$defaultOrder = $this->options()->xaScIndexFullPageMapOptions['marker_fetch_order'] ?: 'rating_weighted';
	
		$direction = 'desc';
		if ($defaultOrder == 'title')
		{
			$direction = 'asc';
		}
	
		$itemFinder
			->where('location', '<>', '')
			->with(['full', 'fullCategory'])
			->setDefaultOrder($defaultOrder, $direction);
	
		return $itemFinder;
	}	
	
	// TODO expand this in the future for more robust MAPS functionality!
	public function findItemsForCategoryMap(array $viewableCategoryIds = null, \XenAddons\Showcase\Entity\Category $category = null)
	{
		/** @var \XenAddons\Showcase\Finder\Item $itemFinder */
		$itemFinder = $this->finder('XenAddons\Showcase:Item');
	
		if (is_array($viewableCategoryIds))
		{
			$itemFinder->where('category_id', $viewableCategoryIds);
		}
		else
		{
			$itemFinder->with('Category.Permissions|' . \XF::visitor()->permission_combination_id);
		}
	
		if ($category && isset($category['map_options']['marker_fetch_order']))
		{
			$defaultOrder = $category['map_options']['marker_fetch_order'] ?: 'rating_weighted';
		}
		else
		{
			$defaultOrder = 'rating_weighted';
		}
	
		$direction = 'desc';
		if ($defaultOrder == 'title')
		{
			$direction = 'asc';
		}
	
		$itemFinder
			->with(['full', 'fullCategory'])
			->where('location', '<>', '')
			->setDefaultOrder($defaultOrder, $direction);
	
		return $itemFinder;
	}
	
	public function findItemsForAuthorItemList(\XF\Entity\User $user, array $viewableCategoryIds = null, array $limits = [])
	{
		$limits = array_replace([
			'visibility' => true,
			'allowOwnPending' => true // False if you don't want authors to see their moderated items
		], $limits);
	
		/** @var \XenAddons\Showcase\Finder\Item $itemFinder */
		$itemFinder = $this->finder('XenAddons\Showcase:Item');
	
		if (is_array($viewableCategoryIds))
		{
			$itemFinder->where('category_id', $viewableCategoryIds);
		}
		else
		{
			$itemFinder->with('Category.Permissions|' . \XF::visitor()->permission_combination_id);
		}
	
		$itemFinder
			->byUser($user)
			->with(['full', 'fullCategory'])
			->useDefaultOrder();
	
		if ($limits['visibility'])
		{
			$itemFinder->applyGlobalVisibilityChecks($limits['allowOwnPending']);
		}
	
		return $itemFinder;
	}	

	public function findItemsForRssFeed(\XenAddons\Showcase\Entity\Category $category = null)
	{
		/** @var \XenAddons\Showcase\Finder\Item $itemFinder */
		$itemFinder = $this->finder('XenAddons\Showcase:Item');
	
		$itemFinder->where('item_state', 'visible')
			->setDefaultOrder('last_update', 'DESC')
			->with(['Category', 'User']);
	
		if ($category)
		{
			$itemFinder->where('category_id', $category->category_id);
		}
		else
		{
			$itemFinder->where('last_update', '>', $this->getReadMarkingCutOff());
		}
	
		return $itemFinder;
	}

	public function findFeaturedItems(array $viewableCategoryIds = null)
	{
		/** @var \XenAddons\Showcase\Finder\Item $itemFinder */
		$itemFinder = $this->finder('XenAddons\Showcase:Item');

		if (is_array($viewableCategoryIds))
		{
			$itemFinder->where('category_id', $viewableCategoryIds);
		}
		else
		{
			$itemFinder->with('Category.Permissions|' . \XF::visitor()->permission_combination_id);
		}

		$itemFinder
			->with('Featured', true)
			->where('item_state', 'visible')
			->with(['full', 'fullCategory'])
			->setDefaultOrder($itemFinder->expression('RAND()'));

		return $itemFinder;
	}
	
	public function findFeaturedItemsForUser(\XF\Entity\User $user)
	{
		/** @var \XenAddons\Showcase\Finder\Item $itemFinder */
		$itemFinder = $this->finder('XenAddons\Showcase:Item');
	
		$itemFinder
			->with('Featured', true)
			->with(['full', 'fullCategory'])
			->with('Category.Permissions|' . \XF::visitor()->permission_combination_id)
			->where('item_state', 'visible')
			->where('user_id', $user->user_id);
	
		return $itemFinder;
	}
	
	public function findDraftItemsForUser(\XF\Entity\User $user)
	{
		/** @var \XenAddons\Showcase\Finder\Item $itemFinder */
		$itemFinder = $this->finder('XenAddons\Showcase:Item');
	
		$itemFinder
			->with(['full', 'fullCategory'])
			->with('Category.Permissions|' . \XF::visitor()->permission_combination_id)
			->where('item_state', 'draft')
			->where('user_id', $user->user_id);
	
		return $itemFinder;
	}
	
	public function findAwaitingItemsForUser(\XF\Entity\User $user)
	{
		/** @var \XenAddons\Showcase\Finder\Item $itemFinder */
		$itemFinder = $this->finder('XenAddons\Showcase:Item');
	
		$itemFinder
			->with(['full', 'fullCategory'])
			->with('Category.Permissions|' . \XF::visitor()->permission_combination_id)
			->where('item_state', 'awaiting')
			->where('user_id', $user->user_id)
			->setDefaultOrder('create_date', 'ASC');
	
		return $itemFinder;
	}
	
	// fetches "Drafts" and "Awaiting Publishing" items for the Items Queue.
	public function findItemsForItemsQueue(array $viewableCategoryIds = null)
	{
		/** @var \XenAddons\Showcase\Finder\Item $itemFinder */
		$itemFinder = $this->finder('XenAddons\Showcase:Item');
	
		if (is_array($viewableCategoryIds))
		{
			$itemFinder->where('category_id', $viewableCategoryIds);
		}
		else
		{
			$itemFinder->with('Category.Permissions|' . \XF::visitor()->permission_combination_id);
		}
	
		$itemFinder
			->with(['full', 'fullCategory'])
			->where('item_state', ['draft','awaiting'])
			->setDefaultOrder('last_update', 'DESC');
	
		return $itemFinder;
	}
	
	// currently only used by the listener to fetch counts of drafts/awaiting for the moderator bar.
	public function findItemsPending()
	{
		/** @var \XenAddons\Showcase\Finder\Item $itemFinder */
		$itemFinder = $this->finder('XenAddons\Showcase:Item');
	
		$itemFinder
			->with('Category.Permissions|' . \XF::visitor()->permission_combination_id)
			->where('item_state', ['draft','awaiting']);
	
		return $itemFinder;
	}
	
	public function findItemsForUserByPrefix(\XF\Entity\User $user, $prefixId)
	{
		/** @var \XenAddons\Showcase\Finder\Item $itemFinder */
		$itemFinder = $this->finder('XenAddons\Showcase:Item');
		
		$itemFinder
			->where('item_state', 'visible')
			->where('prefix_id', $prefixId)
			->where('user_id', $user->user_id);
		
		return $itemFinder;		
	}

	public function findItemsForWatchedList($userId = null)
	{
		if ($userId === null)
		{
			$userId = \XF::visitor()->user_id;
		}
		$userId = intval($userId);

		/** @var \XenAddons\Showcase\Finder\Item $finder */
		$itemFinder = $this->finder('XenAddons\Showcase:Item');

		$itemFinder
			->with(['full', 'fullCategory'])
			->with('Watch|' . $userId, true)
			->with('Category.Permissions|' . \XF::visitor()->permission_combination_id)
			->where('item_state', 'visible')
			->setDefaultOrder('last_update', 'desc');

		return $itemFinder;
	}
	
	// currently only used by the add item to series function!
	public function findItemsForSelectList($userId = null)
	{
		if ($userId === null)
		{
			$userId = \XF::visitor()->user_id;
		}
		$userId = intval($userId);
	
		/** @var \XenAddons\Showcase\Finder\Item $finder */
		$itemFinder = $this->finder('XenAddons\Showcase:Item');
	
		$itemFinder
			->with(['Category', 'Category.Permissions|' . \XF::visitor()->permission_combination_id])
			->where('item_state', 'visible')
			->where('user_id', $userId)
			->setDefaultOrder('last_update', 'desc');
	
		return $itemFinder;
	}

	public function findOtherItemsByCategory(\XenAddons\Showcase\Entity\Item $item)
	{
		/** @var \XenAddons\Showcase\Finder\Item $itemFinder */
		$itemFinder = $this->finder('XenAddons\Showcase:Item');

		$itemFinder
			->with(['full', 'fullCategory'])
			->with(['User', 'Category', 'Category.Permissions|' . \XF::visitor()->permission_combination_id])
			->where('item_state', 'visible')
			->where('category_id', $item->category_id)
			->where('item_id', '<>', $item->item_id)
			->indexHint('USE', 'category_last_update')
			->setDefaultOrder('last_update', 'desc');

		return $itemFinder;
	}
	
	public function findOtherItemsByAuthor(\XenAddons\Showcase\Entity\Item $item, $excludeItemIds = [])
	{
		/** @var \XenAddons\Showcase\Finder\Item $itemFinder */
		$itemFinder = $this->finder('XenAddons\Showcase:Item');
	
		$itemFinder
			->with(['full', 'fullCategory'])
			->with(['User', 'Category', 'Category.Permissions|' . \XF::visitor()->permission_combination_id])
			->where('item_state', 'visible')
			->where('user_id', $item->user_id)
			->where('item_id', '<>', $item->item_id)
			->indexHint('USE', 'user_id_last_update')
			->setDefaultOrder('last_update', 'desc');
	
		if ($excludeItemIds)
		{
			$itemFinder->where('item_id', '<>', $excludeItemIds);
		}
		
		return $itemFinder;
	}
	
	public function findItemsForUser(\XF\Entity\User $user, array $viewableCategoryIds = null, array $limits = [])
	{
		/** @var \XenAddons\Showcase\Finder\Item $itemFinder */
		$itemFinder = $this->finder('XenAddons\Showcase:Item');
	
		$itemFinder->byUser($user)
			->with(['full', 'fullCategory'])
			->setDefaultOrder('last_update', 'desc');
	
		if (is_array($viewableCategoryIds))
		{
			// if we have viewable category IDs, we likely have those permissions
			$itemFinder->where('category_id', $viewableCategoryIds);
		}
		else
		{
			$itemFinder->with('Category.Permissions|' . \XF::visitor()->permission_combination_id);
		}
	
		$limits = array_replace([
			'visibility' => true,
			'allowOwnPending' => $user->user_id == \XF::visitor()->user_id
		], $limits);
	
		if ($limits['visibility'])
		{
			$itemFinder->applyGlobalVisibilityChecks($limits['allowOwnPending']);
		}
	
		return $itemFinder;
	}	
	

	public function findItemForThread(\XF\Entity\Thread $thread)
	{
		/** @var \XenAddons\Showcase\Finder\Item $finder */
		$itemFinder = $this->finder('XenAddons\Showcase:Item');

		$itemFinder->where('discussion_thread_id', $thread->thread_id)
			->with('full')
			->with('Category.Permissions|' . \XF::visitor()->permission_combination_id);

		return $itemFinder;
	}
	
	public function logItemView(\XenAddons\Showcase\Entity\Item $item)
	{
		$this->db()->query("
			-- XFDB=noForceAllWrite	
			INSERT INTO xf_xa_sc_item_view
				(item_id, total)
			VALUES
				(? , 1)
			ON DUPLICATE KEY UPDATE
				total = total + 1
		", $item->item_id);
	}
	
	public function batchUpdateItemViews()
	{
		$db = $this->db();
		$db->query("
			UPDATE xf_xa_sc_item AS item
			INNER JOIN xf_xa_sc_item_view AS item_view ON (item.item_id = item_view.item_id)
			SET item.view_count = item.view_count + item_view.total
		");
		$db->emptyTable('xf_xa_sc_item_view');
	}
	
	public function markItemsReadByVisitor($categoryIds = null, $newViewed = null)
	{
		$itemFinder = $this->findItemsForItemList($categoryIds)
			->unreadOnly();
	
		$items = $itemFinder->fetch();
	
		foreach ($items AS $item)
		{
			$this->markItemReadByVisitor($item, $newViewed);
		}
	}
	
	public function markAllItemCommentsReadByVisitor($categoryIds = null, $newRead = null)
	{
		$itemFinder = $this->findItemsForItemList($categoryIds) 
			->withUnreadCommentsOnly();
	
		$items = $itemFinder->fetch();
	
		foreach ($items AS $item)
		{
			$this->markItemCommentsReadByVisitor($item, $newRead);
		}
	}
	
	public function markItemReadByVisitor(\XenAddons\Showcase\Entity\Item $item, $newRead = null)
	{
		$visitor = \XF::visitor();
		return $this->markItemReadByUser($item, $visitor, $newRead);
	}
	
	public function markItemReadByUser(\XenAddons\Showcase\Entity\Item $item, \XF\Entity\User $user, $newRead = null)
	{
		if (!$user->user_id)
		{
			return false;
		}
	
		if ($newRead === null)
		{
			$newRead = \XF::$time;
		}
	
		$cutOff = $this->getReadMarkingCutOff();
		if ($newRead <= $cutOff)
		{
			return false;
		}
		
		$read = $item->Read[$user->user_id];
		if ($read && $newRead <= $read->item_read_date)
		{
			return false;
		}
	
		$session = $this->app()->session();
		$itemsUnread = $session->get('scUnreadItems');  
		if (isset($itemsUnread['unread'][$item->item_id]))
		{
			unset($itemsUnread['unread'][$item->item_id]);
			$session->set('scUnreadItems', $itemsUnread);
		}
	
		$this->db()->insert('xf_xa_sc_item_read', [
			'item_id' => $item->item_id,
			'user_id' => $user->user_id,
			'item_read_date' => $newRead
		], false, 'item_read_date = VALUES(item_read_date)');
	
		return true;
	}
	
	public function markItemCommentsReadByVisitor(\XenAddons\Showcase\Entity\Item $item, $newRead = null)
	{
		$visitor = \XF::visitor();
		if (!$visitor->user_id)
		{
			return false;
		}
	
		if ($newRead === null)
		{
			$newRead = \XF::$time;
		}
	
		$cutOff = $this->getReadMarkingCutOff();
		if ($newRead <= $cutOff)
		{
			return false;
		}
	
		$viewed = $item->CommentRead[$visitor->user_id];
		if ($viewed && $newRead <= $viewed->comment_read_date)
		{
			return false;
		}
	
		$this->db()->insert('xf_xa_sc_comment_read', [
			'item_id' => $item->item_id,
			'user_id' => $visitor->user_id,
			'comment_read_date' => $newRead
		], false, 'comment_read_date = VALUES(comment_read_date)');
	
		return true;
	}
	
	public function getReadMarkingCutOff()
	{
		return \XF::$time - $this->options()->readMarkingDataLifetime * 86400;
	}
	
	public function pruneItemReadLogs($cutOff = null)
	{
		if ($cutOff === null)
		{
			$cutOff = $this->getReadMarkingCutOff();
		}
	
		$this->db()->delete('xf_xa_sc_item_read', 'item_read_date < ?', $cutOff);
	}
	
	
	
	// Note:  This is BETA/NOT SUPPORTED... (Dec 5th, 2022) 
	public function autoFeatureItems()
	{
		// do not allow this to run unless the auto feature option is enabled!
		
		if ($this->options()->xaScAutoFeatureItems)
		{
			/** @var \XenAddons\Showcase\Finder\Item $itemFinder */
			$itemFinder = $this->finder('XenAddons\Showcase:Item');
			
			$itemFinder
				->with(['full', 'fullCategory'])
				->where('item_state', 'visible')
				->where('last_feature_date', 0); // only content that has never been featured before will be fetched!
			
			if (is_array($this->options()->xaScAutoFeatureCategories))
			{
				$itemFinder->where('category_id', $this->options()->xaScAutoFeatureCategories);
			}

			$featuredUserGroups = $this->options()->xaScAutoFeatureUserGroups;
			if ($featuredUserGroups && $featuredUserGroups[0] == 0)
			{
				$featuredUserGroups = []; // if NONE is selected, always set to empty!
			}
				
			// Note: this is considered beta and not supported. 
			// Added for R&D purposes as a favor to Alfa1
			// TODO test for 2-3 months and make any adjustements as neccessary
			if ($featuredUserGroups)
			{
				if (!is_array($featuredUserGroups))
				{
					$featuredUserGroups = [$featuredUserGroups];
				}
			
				$itemFinder->with('User');
			
				$userGroupIdColumn = $itemFinder->columnSqlName('User.user_group_id');
				$secondaryGroupIdsColumn = $itemFinder->columnSqlName('User.secondary_group_ids');
				
				$positiveMatch = true;
				
				$parts = [];
			
				// for negative matches, we default to allowing guests, but if they say "not the guest"
				// group, then we'll disable it
				$orIsGuest = $positiveMatch ? false : true;
			
				foreach ($featuredUserGroups AS $userGroupId)
				{
					$quotedGroupId = $itemFinder->quote($userGroupId);
					if ($positiveMatch)
					{
						$parts[] = "$userGroupIdColumn = $quotedGroupId "
							. "OR FIND_IN_SET($quotedGroupId, $secondaryGroupIdsColumn)";
			
						if ($userGroupId == \XF\Entity\User::GROUP_GUEST)
						{
							// if explicitly selecting the guest group, allow guest items
							// as they're hard to filter for otherwise
							$parts[] = $itemFinder->columnSqlName('user_id') . ' = 0';
						}
					}
					else
					{
						$parts[] = "$userGroupIdColumn <> $quotedGroupId "
						. "AND FIND_IN_SET($quotedGroupId, $secondaryGroupIdsColumn) = 0";
					
						if ($userGroupId == \XF\Entity\User::GROUP_GUEST)
						{
							$orIsGuest = false;
						}
					}
				}
				if ($parts)
				{
					$joiner = $positiveMatch ? ' OR ' : ' AND ';
					$sql = implode($joiner, $parts);
					if ($orIsGuest)
					{
						$sql = "($sql) OR " . $itemFinder->columnSqlName('user_id') . ' = 0';
					}
					$itemFinder->whereSql($sql);
				}
			}			
			
			if ($this->options()->xaScAutoFeatureExclusive)
			{
				// exclusive (W OR x OR y OR Z)
				
				if ($this->options()->xaScAutoFeatureViews > -1)
				{
					if (
						$this->options()->xaScAutoFeatureViews > -1
						&& $this->options()->xaScAutoFeatureReactionScore > -1
						&& $this->options()->xaScAutoFeatureRating > -1 
						&& $this->options()->xaScAutoFeatureComments > -1
					)
					{
						$itemFinder->whereOr(
							['view_count', '>=', $this->options()->xaScAutoFeatureViews],
							['reaction_score', '>=', $this->options()->xaScAutoFeatureReactionScore],
							['rating_avg', '>=', $this->options()->xaScAutoFeatureRating],
							['comment_count', '>=', $this->options()->xaScAutoFeatureComments]
						);	
					}
					else if (
						$this->options()->xaScAutoFeatureViews > -1
						&& $this->options()->xaScAutoFeatureReactionScore > -1
						&& $this->options()->xaScAutoFeatureRating > -1
					)
					{
						$itemFinder->whereOr(
							['view_count', '>=', $this->options()->xaScAutoFeatureViews],
							['reaction_score', '>=', $this->options()->xaScAutoFeatureReactionScore],
							['rating_avg', '>=', $this->options()->xaScAutoFeatureRating]
						);
					}
					else if (
						$this->options()->xaScAutoFeatureViews > -1 
						&& $this->options()->xaScAutoFeatureReactionScore > -1
						&& $this->options()->xaScAutoFeatureComments > -1
					)
					{
						$itemFinder->whereOr(
							['view_count', '>=', $this->options()->xaScAutoFeatureViews],
							['reaction_score', '>=', $this->options()->xaScAutoFeatureReactionScore],
							['comment_count', '>=', $this->options()->xaScAutoFeatureComments]
						);
					}
					else if (
						$this->options()->xaScAutoFeatureViews > -1
						&& $this->options()->xaScAutoFeatureRating > -1
						&& $this->options()->xaScAutoFeatureComments > -1
					)
					{
						$itemFinder->whereOr(
							['view_count', '>=', $this->options()->xaScAutoFeatureViews],
							['rating_avg', '>=', $this->options()->xaScAutoFeatureRating],
							['comment_count', '>=', $this->options()->xaScAutoFeatureComments]
						);
					}
					else if (
						$this->options()->xaScAutoFeatureViews > -1
						&& $this->options()->xaScAutoFeatureReactionScore > -1
					)
					{
						$itemFinder->whereOr(
							['view_count', '>=', $this->options()->xaScAutoFeatureViews],
							['reaction_score', '>=', $this->options()->xaScAutoFeatureReactionScore]
						);
					}
					else if (
						$this->options()->xaScAutoFeatureViews > -1
						&& $this->options()->xaScAutoFeatureRating > -1
					)
					{
						$itemFinder->whereOr(
							['view_count', '>=', $this->options()->xaScAutoFeatureViews],
							['rating_avg', '>=', $this->options()->xaScAutoFeatureRating]
						);
					}
					else if (
						$this->options()->xaScAutoFeatureViews > -1
						&& $this->options()->xaScAutoFeatureComments > -1
					)
					{
						$itemFinder->whereOr(
							['view_count', '>=', $this->options()->xaScAutoFeatureViews],
							['comment_count', '>=', $this->options()->xaScAutoFeatureComments]
						);
					}
					else
					{
						$itemFinder->where('view_count', '>=', $this->options()->xaScAutoFeatureViews);
					}
				}
				else if ($this->options()->xaScAutoFeatureReactionScore > -1)
				{
					if (
						$this->options()->xaScAutoFeatureReactionScore > -1
						&& $this->options()->xaScAutoFeatureRating > -1 
						&& $this->options()->xaScAutoFeatureComments > -1
					)
					{
						$itemFinder->whereOr(
							['reaction_score', '>=', $this->options()->xaScAutoFeatureReactionScore],
							['rating_avg', '>=', $this->options()->xaScAutoFeatureRating],
							['comment_count', '>=', $this->options()->xaScAutoFeatureComments]
						);
					}
					else if (
						$this->options()->xaScAutoFeatureReactionScore > -1
						&& $this->options()->xaScAutoFeatureRating > -1 
					)
					{
						$itemFinder->whereOr(
							['reaction_score', '>=', $this->options()->xaScAutoFeatureReactionScore],
							['rating_avg', '>=', $this->options()->xaScAutoFeatureRating]
						);
					}
					else if (
						$this->options()->xaScAutoFeatureReactionScore > -1 
						&& $this->options()->xaScAutoFeatureComments > -1
					)
					{
						$itemFinder->whereOr(
							['reaction_score', '>=', $this->options()->xaScAutoFeatureReactionScore],
							['comment_count', '>=', $this->options()->xaScAutoFeatureComments]
						);
					}
					else 
					{
						$itemFinder->where('reaction_score', '>=', $this->options()->xaScAutoFeatureReactionScore);
					}
				}
				else if ($this->options()->xaScAutoFeatureRating > -1)
				{
					if (
						$this->options()->xaScAutoFeatureRating > -1 
						&& $this->options()->xaScAutoFeatureComments > -1
					)
					{
						$itemFinder->whereOr(
							['rating_avg', '>=', $this->options()->xaScAutoFeatureRating],
							['comment_count', '>=', $this->options()->xaScAutoFeatureComments]
						);
					}
					else 
					{
						$itemFinder->where('rating_avg', '>=', $this->options()->xaScAutoFeatureRating);
					}
				}
				else if ($this->options()->xaScAutoFeatureComments > -1)
				{
					$itemFinder->where('comment_count', '>=', $this->options()->xaScAutoFeatureComments);
				}
			}
			else // inclusive (W AND x AND y AND Z)
			{
				if ($this->options()->xaScAutoFeatureViews > -1)
				{
					$itemFinder->where('view_count', '>=', $this->options()->xaScAutoFeatureViews);
				}
				
				if ($this->options()->xaScAutoFeatureReactionScore > -1)
				{
					$itemFinder->where('reaction_score', '>=', $this->options()->xaScAutoFeatureReactionScore);
				}
	
				if ($this->options()->xaScAutoFeatureRating > -1)
				{
					$itemFinder->where('rating_avg', '>=', $this->options()->xaScAutoFeatureRating);
				}
	
				if ($this->options()->xaScAutoFeatureComments > -1)
				{
					$itemFinder->where('comment_count', '>=', $this->options()->xaScAutoFeatureComments);
				}
			}
			
			if ($this->options()->xaScAutoFeatureCreated['enabled'])
			{
				if ($this->options()->xaScAutoFeatureCreated['days'] > -1)
				{
					$itemFinder->where('create_date', '>=', \XF::$time - ($this->options()->xaScAutoFeatureCreated['days'] * 86400));
				}
			}
			
			if ($this->options()->xaScAutoFeatureUpdated['enabled'])
			{
				if ($this->options()->xaScAutoFeatureUpdated['days'] > -1)
				{
					$itemFinder->where('last_update', '>=', \XF::$time - ($this->options()->xaScAutoFeatureUpdated['days'] * 86400));
				}
			}
			
			$items = $itemFinder->fetch();
			
			foreach ($items AS $item)
			{
				/** @var \XenAddons\Showcase\Service\Item\Feature $featurer */
				$featurer = $this->app()->service('XenAddons\Showcase:Item\Feature', $item);
			
				$featurer->feature();
			}			
		}
	}	
	
	public function autoUnfeatureItems()
	{
		// do not allow this to run unless the auto unfeature option is enabled!
	
		if ($this->options()->xaScAutoUnfeatureItems['enabled'])
		{
			$cutOffDays = $this->options()->xaScAutoUnfeatureItems['days'];
			$cutOffDate = \XF::$time - ($cutOffDays * 86400);
				
			/** @var \XenAddons\Showcase\Finder\Item $itemFinder */
			$itemFinder = $this->finder('XenAddons\Showcase:Item');
	
			$itemFinder
				->with('Featured', true)
				->where('Featured.feature_date', '<', $cutOffDate);
				
			$featuredItems = $itemFinder->fetch();
				
			foreach ($featuredItems AS $item)
			{
				/** @var \XenAddons\Showcase\Service\Item\Feature $featurer */
				$featurer = $this->app()->service('XenAddons\Showcase:Item\Feature', $item);
	
				$featurer->unfeature();
			}
		}
	}
	
	public function publishScheduledItems()
	{
		/** @var \XenAddons\Showcase\Finder\Item $itemFinder */
		$itemFinder = $this->finder('XenAddons\Showcase:Item');
	
		$itemFinder
			->where('item_state', 'awaiting')
			->where('create_date', '<=', \XF::$time);
	
		$awaitingItems = $itemFinder->fetch();
	
		foreach ($awaitingItems AS $item)
		{
			/** @var \XenAddons\Showcase\Service\Item\PublishDraft $draftPublisher */
			$draftPublisher = \XF::service('XenAddons\Showcase:Item\PublishDraft', $item);
			$draftPublisher->setNotifyRunTime(1); // may be a lot happening
			$draftPublisher->publishDraft(true);
		}
	}

	public function getItemAttachmentConstraints()
	{
		$options = $this->options();
	
		return [
			'extensions' => Arr::stringToArray($options->xaScAllowedFileExtensions),
			'size' => $options->xaScItemAttachmentMaxFileSize * 1024,
			'width' => $options->attachmentMaxDimensions['width'],
			'height' => $options->attachmentMaxDimensions['height']
		];
	}	

	public function sendModeratorActionAlert(
		\XenAddons\Showcase\Entity\Item $item, $action, $reason = '', array $extra = [], \XF\Entity\User $forceUser = null
	)
	{
		if (!$forceUser)
		{
			if (!$item->user_id || !$item->User)
			{
				return false;
			}

			$forceUser = $item->User;
		}

		$extra = array_merge([
			'title' => $item->title,
			'prefix_id' => $item->prefix_id,
			'link' => $this->app()->router('public')->buildLink('nopath:showcase', $item),
			'reason' => $reason
		], $extra);

		/** @var \XF\Repository\UserAlert $alertRepo */
		$alertRepo = $this->repository('XF:UserAlert');
		$alertRepo->alert(
			$forceUser,
			0, '',
			'user', $forceUser->user_id,
			"sc_item_{$action}", $extra,
			['dependsOnAddOnId' => 'XenAddons/Showcase']
		);

		return true;
	}

	public function addItemEmbedsToContent($content, $metadataKey = 'embed_metadata', $itemGetterKey = 'ShowcaseItems', $seriesGetterKey = 'ShowcaseSeries')
	{
		if (!$content)
		{
			return;
		}
	
		$itemIds = [];
		$seriesIds = [];
		foreach ($content AS $item)
		{
			$metadata = $item->{$metadataKey};
			if (isset($metadata['showcaseEmbeds']['item']))
			{
				$itemIds = array_merge($itemIds, $metadata['showcaseEmbeds']['item']);
			}
			if (isset($metadata['showcaseEmbeds']['series']))
			{
				$seriesIds = array_merge($seriesIds, $metadata['showcaseEmbeds']['series']);
			}
		}
	
		$visitor = \XF::visitor();
	
		$items = [];
		$seriesIds = [];
		
		if ($itemIds)
		{
			$items = $this->finder('XenAddons\Showcase:Item')
				->with('Category.Permissions|' . $visitor->permission_combination_id)
				->whereIds(array_unique($itemIds))
				->orderByDate()
				->fetch();
		}
	
		foreach ($content AS $item)
		{
			$metadata = $item->{$metadataKey};
			if (isset($metadata['showcaseEmbeds']['item']))
			{
				$showcaseItems = [];
				foreach ($metadata['showcaseEmbeds']['item'] AS $id)
				{
					if (!isset($items[$id]))
					{
						continue;
					}
					$showcaseItems[$id] = $items[$id];
				}
	
				$item->{"set$itemGetterKey"}($showcaseItems);
			}
			
			if (isset($metadata['showcaseEmbeds']['series']))
			{
				$imsSeries = [];
				foreach ($metadata['showcaseEmbeds']['series'] AS $id)
				{
					if (!isset($series[$id]))
					{
						continue;
					}
					$imsSeries[$id] = $series[$id];
				}
					
				$item->{"set$seriesGetterKey"}($showcaseSeries);
			}			
		}
	}
			
	public function getUserItemCount($userId)
	{
		return $this->db()->fetchOne("
			SELECT COUNT(*)
			FROM xf_xa_sc_item
			WHERE user_id = ?
				AND item_state = 'visible'
		", $userId);
	}
	
	/**
	 * @param $url
	 * @param null $type
	 * @param null $error
	 *
	 * @return null|\XF\Mvc\Entity\Entity
	 */
	public function getItemFromUrl($url, $type = null, &$error = null)
	{
		$routePath = $this->app()->request()->getRoutePathFromUrl($url);
		$routeMatch = $this->app()->router($type)->routeToController($routePath);
		$params = $routeMatch->getParameterBag();
	
		if (!$params->item_id)
		{
			$error = \XF::phrase('xa_sc_no_item_id_could_be_found_from_that_url');
			return null;
		}
	
		$item = $this->app()->find('XenAddons\Showcase:Item', $params->item_id);
		if (!$item)
		{
			$error = \XF::phrase('xa_sc_no_item_could_be_found_with_id_x', ['item_id' => $params->item_id]);
			return null;
		}
	
		return $item;
	}	
	
	/**
	 * @return int[]
	 */
	public function getItemContributorCache(
		\XenAddons\Showcase\Entity\Item $item
	): array
	{
		return $this->db()->fetchAllColumn(
			'SELECT user_id
			FROM xf_xa_sc_item_contributor
			WHERE item_id = ?',
			$item->item_id
		);
	}
	
	/**
	 * @return int[]
	 */
	public function rebuildItemContributorCache(
		\XenAddons\Showcase\Entity\Item $item
	): array
	{
		$cache = $this->getItemContributorCache($item);
	
		$item->fastUpdate('contributor_user_ids', $cache);
		$item->clearCache('Contributors');
	
		return $cache;
	}
	
	
	// Google Maps stuff...
	
	/**
	 * @param $location
	 * @param string $action
	 *
	 * @return array
	 */
	public function getLocationDataFromGoogleMapsGeocodingApi($location = null, $action = '')
	{
		$apiKey = \XF::app()->options()->xaScGoogleMapsGeocodingApiKey;
	
		$location_data = [];
	
		if ($location && $apiKey)
		{
			$urlEncodedAddr = urlencode($location);
			$apiUrl = 'https://maps.google.com/maps/api/geocode/json?address='.$urlEncodedAddr.'&key='.$apiKey;
	
			$client = \XF::app()->http()->client();
	
			$geocodeResponse = $client->get($apiUrl)->getBody();
	
			$geocodeData = \GuzzleHttp\json_decode($geocodeResponse);
	
			if (!empty($geocodeData)
				&& $geocodeData->status == 'OK'
				&& isset($geocodeData->results)
				&& isset($geocodeData->results[0])
			)
			{
				$city = '';
				$state = '';
				$state_short = '';
				$country = '';
				$country_short = '';
	
				foreach ($geocodeData->results[0]->address_components AS $address_component)
				{
					if ($address_component->types)
					{
						foreach ($address_component->types AS $address_component_type)
						{
							// Basically, this is setting the "CITY", however, Google Maps does not have a CITY Address Component Type,
							// so have to do some additional checking to set a "city" for use witihin Showcase
							if ($address_component_type == 'locality'
								|| $address_component_type == 'sublocality'
								|| $address_component_type == 'postal_town')
							{
								$city = $address_component->long_name;
							}
	
							// Basically, this is setting the "STATE", however, Google Maps does not have a STATE Component Type
							if ($address_component_type == 'administrative_area_level_1')
							{
								$state = $address_component->long_name;
								$state_short = $address_component->short_name;
							}
	
							// This one is much more obvious lol :P
							if ($address_component_type == 'country')
							{
								$country = $address_component->long_name;
								$country_short = $address_component->short_name;
							}
						}
					}
				}
	
				// For what ever reason, Google Maps sets the country_short to GB instead of UK, so lets fix that!
				if ($country_short == 'GB')
				{
					$country_short = 'UK';
				}
	
				$location_data = [
					'address_components' => $geocodeData->results[0]->address_components,
					'formatted_address' => $geocodeData->results[0]->formatted_address,
					'city' => $city,
					'state' => $state,
					'state_short' => $state_short,
					'country' => $country,
					'country_short' => $country_short,
					'geometry' => $geocodeData->results[0]->geometry,
					'latitude' => $geocodeData->results[0]->geometry->location->lat,
					'longitude' => $geocodeData->results[0]->geometry->location->lng,
					'place_id' => $geocodeData->results[0]->place_id,
					'plus_code' => isset($geocodeData->results[0]->plus_code) ? $geocodeData->results[0]->plus_code : '',
				];
			}
	
			// TODO log invalid status responses in showcase maps log table.
				
			// TODO $action is the type of action 'create', 'edit', 'rebuild'
	
			//// invalid status responses:
			// "ZERO_RESULTS" indicates that the geocode was successful but returned no results. This may occur if the geocoder was passed a non-existent address.
			// "OVER_QUERY_LIMIT" indicates that you are over your quota.
			// "REQUEST_DENIED" indicates that your request was denied. The web page is not allowed to use the geocoder.
			// "INVALID_REQUEST" generally indicates that the query (address, components or latlng) is missing.
			// "UNKNOWN_ERROR" indicates that the request could not be processed due to a server error. The request may succeed if you try again.
			// "ERROR" indicates that the request timed out or there was a problem contacting the Google servers. The request may succeed if you try again.
		}
	
		return $location_data;
	}

	
	// NOTE: this is an experiment (BETA) to include image attachments from posts in the item gallery
	// UPDATE: so far this is working as expected, so probably remove the BETA status in the XF 2.3 version!
	
	public function getPostsImagesForItemGallery(\XenAddons\Showcase\Entity\Item $item, $fetchType = 'owners')
	{
		$db = $this->db();
		
		$ids = null;

		if ($fetchType == 'owners')
		{
			// only fetch from posts that the item owner or co-owners posted
			
			$ownerIds = [];
			$ownerIds[] = $item->user_id;
			
			if ($item->Contributors)
			{
				foreach ($item->Contributors AS $contributorID => $contributor)
				{
					if ($contributor->is_co_owner)
					{
						$ownerIds[] = $contributorID;
					}
				}
			}
			
			$ids = $db->fetchAllColumn("
					SELECT post_id
					FROM xf_post
					WHERE thread_id = ?
					AND user_id IN (" . $db->quote($ownerIds) . ")
					AND message_state = 'visible'
					AND attach_count > 0
					ORDER BY post_id
				", $item->Discussion->thread_id
			);
		}
		else if ($fetchType == 'contributors')
		{
			// only fetch from posts that any contributors (owner, co-owners, contributors) posted
				
			$contributorIds = $item->contributor_user_ids;
			array_push($contributorIds, $item->user_id); // this adds the item owner user_id
			
			$ids = $db->fetchAllColumn("
					SELECT post_id
					FROM xf_post
					WHERE thread_id = ?
					AND user_id IN (" . $db->quote($contributorIds) . ")
					AND message_state = 'visible'
					AND attach_count > 0
					ORDER BY post_id
				", $item->Discussion->thread_id
			);
		}
		else if ($fetchType == 'all')
		{
			// fetch image attachments from all posts
				
			$ids = $db->fetchAllColumn("
					SELECT post_id
					FROM xf_post
					WHERE thread_id = ?
					AND message_state = 'visible'
					AND attach_count > 0
					ORDER BY post_id
				", $item->Discussion->thread_id
			);
		}
		
		if ($ids)
		{
			$attachments = $this->finder('XF:Attachment')
				->where([
					'content_type' => 'post',
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
}