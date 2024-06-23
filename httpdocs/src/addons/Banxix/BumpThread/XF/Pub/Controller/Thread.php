<?php

namespace Banxix\BumpThread\XF\Pub\Controller;

use XF\Mvc\ParameterBag;

class Thread extends XFCP_Thread
{
	public function actionBump(ParameterBag $params)
	{
		/** @var \Banxix\BumpThread\XF\Entity\Thread $thread */
		$thread = $this->assertViewableThread($params->thread_id);
		$visitor = \XF::visitor();

		if (!$this->options()->bump_thread_enabled || !$thread->canBump())
		{
			return $this->error(\XF::phrase('bump_thread_not_allow'));
		}

		$bumpMaxPerDay = $visitor->hasNodePermission($thread->Forum->node_id, 'bumpMaxPerDay');
		if ($bumpMaxPerDay != -1
			&& ($this->bumpThreadRepo()->userTodayBumpCount($visitor->user_id) >= $bumpMaxPerDay)
		)
		{
			return $this->error(\XF::phrase('bump_thread_max_reached_today'));
		}

		if ($thread->getTimeToNextBump() > 0)
		{
			return $this->error(\XF::phrase('bump_thread_need_to_wait', [
				'time' => $thread->getTimeToNextBump()
			]));
		}

		$this->bumpThreadRepo()->bump($thread);
		$this->bumpThreadRepo()->log($thread->thread_id, $visitor->user_id);

		$reply = $this->view('XF:Thread\Bump');
		$reply->setJsonParams([
			'text' => \XF::phrase('bump_thread_bumped'),
			'message' => \XF::phrase('bump_thread_successful'),
		]);

		return $reply;
	}

	public function actionBumpDisable(ParameterBag $params)
	{
		$this->assertPostOnly();

		/** @var \Banxix\BumpThread\XF\Entity\Thread $thread */
		$thread = $this->assertViewableThread($params->thread_id);
		if (!$thread->canDisableBump())
		{
			return $this->noPermission();
		}

		if ($thread->bump_thread_disabled)
		{
			$thread->bump_thread_disabled = false;
			$text = \XF::phrase('bump_thread_disallow');
		}
		else
		{
			$thread->bump_thread_disabled = true;
			$text = \XF::phrase('bump_thread_allow');
		}

		$thread->save();

		$reply = $this->redirect($this->getDynamicRedirect());
		$reply->setJsonParams([
			'text' => $text,
			'bump_thread_disabled' => $thread->bump_thread_disabled
		]);
		return $reply;
	}

	/**
	 * @return \XF\Mvc\Entity\Repository|\Banxix\BumpThread\Repository\BumpThread
	 */
	private function bumpThreadRepo()
	{
		return $this->repository('Banxix\BumpThread:BumpThread');
	}
}