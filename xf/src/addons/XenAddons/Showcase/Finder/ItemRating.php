<?php

namespace XenAddons\Showcase\Finder;

use XF\Mvc\Entity\Finder;

class ItemRating extends Finder
{
	public function inItem(\XenAddons\Showcase\Entity\Item $item, array $limits = [])
	{
		$limits = array_replace([
			'visibility' => true
		], $limits);

		$this->where('item_id', $item->item_id);

		if ($limits['visibility'])
		{
			$this->applyVisibilityChecksInItem($item, true);
		}

		return $this;
	}
	
	public function byUser(\XF\Entity\User $user)
	{
		$this->where('user_id', $user->user_id);
	
		return $this;
	}

	public function applyVisibilityChecksInItem(\XenAddons\Showcase\Entity\Item $item, $allowOwnPending = false)
	{
		$visitor = \XF::visitor();
		$conditions = [];
		$viewableStates = ['visible'];

		if ($item->canViewDeletedReviews())
		{
			$viewableStates[] = 'deleted';

			$this->with('DeletionLog');
		}

		if ($item->canViewModeratedReviews())
		{
			$viewableStates[] = 'moderated';
		}
		else if ($visitor->user_id && $allowOwnPending)
		{
			$conditions[] = [
				'rating_state' => 'moderated',
				'user_id' => $visitor->user_id
			];
		}		

		$conditions[] = ['rating_state', $viewableStates];

		$this->whereOr($conditions);

		return $this;
	}
}