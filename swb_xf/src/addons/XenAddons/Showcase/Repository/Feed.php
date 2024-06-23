<?php

namespace XenAddons\Showcase\Repository;

use XF\Mvc\Entity\Finder;
use XF\Mvc\Entity\Repository;

class Feed extends Repository
{
	/**
	 * @return Finder
	 */
	public function findFeedsForList()
	{
		return $this->finder('XenAddons\Showcase:Feed')->order('title');
	}

	/**
	 * @return Finder
	 */
	public function findDueFeeds($time = null)
	{
		/** @var \XenAddons\Showcase\Finder\Feed $finder */
		$finder = $this->finder('XenAddons\Showcase:Feed');

		return $finder
			->isDue($time)
			->where('active', true)
			->with(['Category'], true)
			->order('last_fetch');
	}
}