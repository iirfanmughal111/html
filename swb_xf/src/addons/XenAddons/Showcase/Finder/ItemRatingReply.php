<?php

namespace XenAddons\Showcase\Finder;

use XF\Mvc\Entity\Finder;

class ItemRatingReply extends Finder
{

	public function forItemRating(\XenAddons\Showcase\Entity\ItemRating $rating, array $limits = [])
	{
		$limits = array_replace([
			'visibility' => true,
			'allowOwnPending' => true
		], $limits);

		$this->where('rating_id', $rating->rating_id);

		if ($limits['visibility'])
		{
			$this->applyVisibilityChecksForItemRating($rating, $limits['allowOwnPending']);
		}

		return $this;
	}

	public function applyVisibilityChecksForItemRating(\XenAddons\Showcase\Entity\ItemRating $rating, $allowOwnPending = true)
	{
		$conditions = [];
		$viewableStates = ['visible'];

		if ($rating->canViewDeletedReplies())
		{
			$viewableStates[] = 'deleted';
			$this->with('DeletionLog');
		}

		$visitor = \XF::visitor();
		if ($rating->canViewModeratedReplies())
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