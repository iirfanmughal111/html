<?php

namespace XenAddons\Showcase\Finder;

use XF\Mvc\Entity\Finder;

class Comment extends Finder
{
	public function forContent(\XF\Mvc\Entity\Entity $content, array $limits = [])
	{
		$limits = array_replace([
			'visibility' => true,
			'allowOwnPending' => true //false
		], $limits);

		$this->where('item_id', $content->item_id);


		if ($limits['visibility'])
		{
			$this->applyVisibilityChecksInContent($content, $limits['allowOwnPending']);
		}

		return $this;
	}

	public function applyVisibilityChecksInContent(\XF\Mvc\Entity\Entity $content, $allowOwnPending = true)
	{
		/** @var \XenAddons\Showcase\Entity\Item $content */

		$conditions = [];
		$viewableStates = ['visible'];

		if ($content->canViewDeletedComments())
		{
			$viewableStates[] = 'deleted';

			$this->with('DeletionLog');
		}

		$visitor = \XF::visitor();
		if ($content->canViewModeratedComments())
		{
			$viewableStates[] = 'moderated';
		}
		else if ($visitor->user_id && $allowOwnPending)
		{
			$conditions[] = [
				'comment_state' => 'moderated',
				'user_id' => $visitor->user_id
			];
		}

		$conditions[] = ['comment_state', $viewableStates];

		$this->whereOr($conditions);

		return $this;
	}

	public function orderByDate($direction = 'ASC')
	{
		$this->order('comment_date', $direction)->order('comment_id', $direction);

		return $this;
	}

	public function newerThan($date)
	{
		$this->where('comment_date', '>', $date);

		return $this;
	}
}