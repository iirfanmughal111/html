<?php

namespace XenAddons\Showcase\Finder;

use XF\Mvc\Entity\Finder;

class ItemUpdateReply extends Finder
{

	public function forItemUpdate(\XenAddons\Showcase\Entity\ItemUpdate $itemUpdate, array $limits = [])
	{
		$limits = array_replace([
			'visibility' => true,
			'allowOwnPending' => true
		], $limits);

		$this->where('item_update_id', $itemUpdate->item_update_id);

		if ($limits['visibility'])
		{
			$this->applyVisibilityChecksForItemUpdate($itemUpdate, $limits['allowOwnPending']);
		}

		return $this;
	}

	public function applyVisibilityChecksForItemUpdate(\XenAddons\Showcase\Entity\ItemUpdate $itemUpdate, $allowOwnPending = true)
	{
		$conditions = [];
		$viewableStates = ['visible'];

		if ($itemUpdate->canViewDeletedReplies())
		{
			$viewableStates[] = 'deleted';
			$this->with('DeletionLog');
		}

		$visitor = \XF::visitor();
		if ($itemUpdate->canViewModeratedReplies())
		{
			$viewableStates[] = 'moderated';
		}
		else if ($visitor->user_id && $allowOwnPending)
		{
			$conditions[] = [
				'reply_state' => 'moderated',
				'user_id' => $visitor->user_id
			];
		}

		$conditions[] = ['reply_state', $viewableStates];

		$this->whereOr($conditions);

		return $this;
	}

	public function newerThan($date)
	{
		$this->where('reply_date', '>', $date);

		return $this;
	}
}