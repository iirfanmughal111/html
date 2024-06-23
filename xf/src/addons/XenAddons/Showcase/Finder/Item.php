<?php

namespace XenAddons\Showcase\Finder;

use XF\Mvc\Entity\Finder;

class Item extends Finder
{
	public function inCategory($categoryId)
	{
		$this->where('category_id', $categoryId);
	
		return $this;
	}
	
	public function byUser(\XF\Entity\User $user)
	{
		$this->where('user_id', $user->user_id);
	
		return $this;
	}
	
	public function applyGlobalVisibilityChecks($allowOwnPending = false)
	{
		$visitor = \XF::visitor();
		$conditions = [];
		$viewableStates = ['visible'];

		if ($visitor->hasPermission('xa_showcase', 'viewDeleted'))
		{
			$viewableStates[] = 'deleted';

			$this->with('DeletionLog');
		}

		if ($visitor->hasPermission('xa_showcase', 'viewModerated'))
		{
			$viewableStates[] = 'moderated';
		}
		else if ($visitor->user_id && $allowOwnPending)
		{
			$conditions[] = [
				'item_state' => 'moderated',
				'user_id' => $visitor->user_id
			];
		}

		$conditions[] = ['item_state', $viewableStates];

		$this->whereOr($conditions);

		return $this;
	}

	public function applyVisibilityChecksInCategory(\XenAddons\Showcase\Entity\Category $category, $allowOwnPending = false)
	{
		$conditions = [];
		$viewableStates = ['visible'];

		if ($category->canViewDeletedItems())
		{
			$viewableStates[] = 'deleted';

			$this->with('DeletionLog');
		}
		
		$visitor = \XF::visitor();
		if ($category->canViewModeratedItems())
		{
			$viewableStates[] = 'moderated';
		}
		else if ($visitor->user_id && $allowOwnPending)
		{
			$conditions[] = [
				'item_state' => 'moderated',
				'user_id' => $visitor->user_id
			];
		}

		$conditions[] = ['item_state', $viewableStates];

		$this->whereOr($conditions);

		return $this;
	}
	
	public function withReadData($userId = null)
	{
		if ($userId === null)
		{
			$userId = \XF::visitor()->user_id;
		}
	
		if ($userId)
		{
			$this->with([
				'Read|' . $userId,
			]);
		}
	
		return $this;
	}
	
	public function unreadOnly($userId = null)
	{
		if ($userId === null)
		{
			$userId = \XF::visitor()->user_id;
		}
		if (!$userId)
		{
			// no user, no read tracking
			return $this;
		}
	
		$itemReadExpression = $this->expression(
			'%s > COALESCE(%s, 0)',
			'last_update',
			'Read|' . $userId . '.item_read_date'
		);
	
		$this
		->where('last_update', '>', (
			\XF::$time - $this->app()->options()->readMarkingDataLifetime * 86400)
		)
		->where($itemReadExpression);
	
		return $this;
	}	

	public function watchedOnly($userId = null)
	{
		if ($userId === null)
		{
			$userId = \XF::visitor()->user_id;
		}
		if (!$userId)
		{
			// no user, just ignore
			return $this;
		}

		$this->whereOr(
			['Watch|' . $userId . '.user_id', '!=', null],
			['Category.Watch|' . $userId . '.user_id', '!=', null]
		);

		return $this;
	}
	
	/**
	 * @param string $direction
	 *
	 * @return Finder
	 */
	public function orderByDate($order = 'create_date', $direction = 'DESC')
	{
		$this->setDefaultOrder([
			[$order, $direction],
			['item_id', $direction]
		]);
	
		return $this;
	}	

	public function useDefaultOrder(\XenAddons\Showcase\Entity\Category $category = null)
	{
		// Check to see if a Category Entity is being passed in and check if there is a Category Override for using a category specific default sort order.
		if ($category && isset($category->item_list_order) && $category->item_list_order)
		{
			$defaultOrder = $category->item_list_order ?: 'create_date';
		}
		else
		{
			$defaultOrder = $this->app()->options()->xaScListDefaultOrder ?: 'create_date';
		}
		
		$defaultDir = $defaultOrder == 'title' ? 'asc' : 'desc';

		$this->setDefaultOrder($defaultOrder, $defaultDir);

		return $this;
	}
	
	public function withUnreadCommentsOnly($userId = null)
	{
		if ($userId === null)
		{
			$userId = \XF::visitor()->user_id;
		}
		if (!$userId)
		{
			// no user, no read tracking
			return $this;
		}
	
		$itemCommentReadExpression = $this->expression(
			'%s > COALESCE(%s, 0)',
			'last_comment_date',
			'CommentRead|' . $userId . '.comment_read_date'
		);
	
		$this
		->where('last_comment_date', '>', (
			\XF::$time - $this->app()->options()->readMarkingDataLifetime * 86400)
		)
		->where($itemCommentReadExpression);
	
		return $this;
	}	
}