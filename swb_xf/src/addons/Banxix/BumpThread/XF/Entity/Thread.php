<?php

namespace Banxix\BumpThread\XF\Entity;


/**
 * COLUMNS
 * @property bool $bump_thread_disabled
 *
 * RELATIONS
 * @property \Banxix\BumpThread\Entity\BumpLog BumpLog
 */
class Thread extends XFCP_Thread
{
	public function canBump()
	{
		if (!$this->app()->options()->bump_thread_enabled || $this->bump_thread_disabled)
		{
			return false;
		}

		$visitor = \XF::visitor();

		$canBumpOwn = $this->user_id == $visitor->user_id
			&& $visitor->hasNodePermission($this->node_id, 'bump');
		$canBumpOthers = $visitor->hasNodePermission($this->node_id, 'bumpOthers');

		return $canBumpOwn || $canBumpOthers;
	}

	public function canDisableBump()
	{
		return \XF::visitor()->hasNodePermission($this->node_id, 'bumpDisable');
	}

	public function getTimeToNextBump()
	{
		$visitor = \XF::visitor();

		/** @var \Banxix\BumpThread\Repository\BumpThread $bumpThreadRepo */
		$bumpThreadRepo = $this->repository('Banxix\BumpThread:BumpThread');

		$rawLastBump = $bumpThreadRepo->userLastBump($this->thread_id, $visitor->user_id);
		$userLastBump = $rawLastBump === false
		&& $this->app()->options()->bump_thread_timer_from_thread_post_date
			? $this->post_date : (int) $rawLastBump;

		$bumpFloodRate = $bumpThreadRepo->hasNodePermission($visitor, $this->node_id);
		$timeToNextBump = $userLastBump + $bumpFloodRate - \XF::$time;

		return $timeToNextBump > 0 ? $timeToNextBump : null;
	}
}