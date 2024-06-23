<?php

namespace Banxix\BumpThread\XF\Pub\Controller;

class Forum extends XFCP_Forum
{
	protected function getAvailableForumSorts(\XF\Entity\Forum $forum)
	{
		$sorts = parent::getAvailableForumSorts($forum);

		$sorts['bumped_threads'] = true;

		return $sorts;
	}

	protected function getForumFilterInput(\XF\Entity\Forum $forum)
	{
		$filters = parent::getForumFilterInput($forum);

		$sorts = $this->getAvailableForumSorts($forum);

		if (in_array($forum->node_id, $this->options()->bump_thread_default_sort_forums))
		{
			$input = $this->filter([
				'order' => 'str',
				'direction' => 'str',
			]);

			if ($input['order'] && isset($sorts[$input['order']]))
			{
				if (!in_array($input['direction'], ['asc', 'desc']))
				{
					$input['direction'] = 'desc';
				}

				$filters['order'] = $input['order'];
				$filters['direction'] = $input['direction'];
			}
		}

		return $filters;
	}

	protected function applyForumFilters(\XF\Entity\Forum $forum, \XF\Finder\Thread $threadFinder, array $filters)
	{
		$sorts = $this->getAvailableForumSorts($forum);

		if (!empty($filters['order']) && isset($sorts[$filters['order']]))
		{
			$threadFinder->resetOrder();

			if ($filters['order'] == 'bumped_threads')
			{
				if (!in_array($forum->node_id, $this->options()->bump_thread_default_sort_forums))
				{
					$this->bumpThreadRepo()->bumpedThreadsInForum($threadFinder);
				}

				$orderExp = $threadFinder->expression('IFNULL(%s, %s)', 'bump_log.bump_date', 'post_date');
				$threadFinder->order($orderExp, $filters['direction']);

				unset($filters['order']);
			}
		}

		parent::applyForumFilters($forum, $threadFinder, $filters);
	}

	/**
	 * @return \XF\Mvc\Entity\Repository|\Banxix\BumpThread\Repository\BumpThread
	 */
	private function bumpThreadRepo()
	{
		return $this->repository('Banxix\BumpThread:BumpThread');
	}
}