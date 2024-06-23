<?php

namespace XenAddons\Showcase\Finder;

use XF\Mvc\Entity\Finder;

class ItemUpdate extends Finder
{
	public function inItem(\XenAddons\Showcase\Entity\Item $item, array $limits = [])
	{
		$limits = array_replace([
			'visibility' => true
		], $limits);
		
		$this->where('item_id', $item->item_id);
		
		if ($limits['visibility'])
		{
			$this->applyVisibilityChecksInItem($item);
		}

		return $this;
	}
	
	public function byUser(\XF\Entity\User $user)
	{
		$this->where('user_id', $user->user_id);
	
		return $this;
	}

	public function applyVisibilityChecksInItem(\XenAddons\Showcase\Entity\Item $item)
	{
		$conditions = [];
		$viewableStates = ['visible'];

		if ($item->canViewDeletedUpdates())
		{
			$viewableStates[] = 'deleted';

			$this->with('DeletionLog');
		}

		if ($item->canViewModeratedUpdates())
		{
			$viewableStates[] = 'moderated';
		}

		$conditions[] = ['update_state', $viewableStates];

		$this->whereOr($conditions);

		return $this;
	}
}