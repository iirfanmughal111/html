<?php

namespace Banxix\BumpThread\XF\Finder;

use SV\StandardLib\Finder\SqlJoinTrait;

class Thread extends XFCP_Thread
{
	use SqlJoinTrait;

	public function inForum(\XF\Entity\Forum $forum, array $limits = [])
	{
		$finder = parent::inForum($forum, $limits);

		if (in_array($forum->node_id, \XF::options()->bump_thread_default_sort_forums))
		{
			/** @var \Banxix\BumpThread\Repository\BumpThread $bumpThreadRepo */
			$bumpThreadRepo = $this->em->getRepository('Banxix\BumpThread:BumpThread');
			$bumpThreadRepo->bumpedThreadsInForum($finder);

			$orderExp = $finder->expression('IFNULL(%s, %s)', 'bump_log.bump_date', 'post_date');
			$finder->order($orderExp, 'DESC');
		}

		return $finder;
	}
}