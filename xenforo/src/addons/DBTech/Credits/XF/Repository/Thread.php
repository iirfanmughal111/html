<?php /** @noinspection PhpMissingReturnTypeInspection */

namespace DBTech\Credits\XF\Repository;

class Thread extends XFCP_Thread
{
	/**
	 * @param \XF\Entity\Thread $thread
	 *
	 * @throws \Exception
	 */
	public function logThreadView(\XF\Entity\Thread $thread)
	{
		/** @var \DBTech\Credits\XF\Entity\User $visitor */
		$visitor = \XF::visitor();
		
		/** @var \DBTech\Credits\Repository\EventTrigger $eventTriggerRepo */
		$eventTriggerRepo = $this->repository('DBTech\Credits:EventTrigger');
		
		$eventTriggerRepo->getHandler('read')
			->apply($thread->thread_id, [
				'node_id' => $thread->node_id,
				'owner_id' => $thread->user_id,
				'content_type' => 'thread',
				'content_id' => $thread->thread_id
			], $visitor)
		;
		
		if ($visitor->user_id != $thread->user_id)
		{
			$eventTriggerRepo->getHandler('view')
				->apply($thread->thread_id, [
					'node_id' => $thread->node_id,
					'source_user_id' => $visitor->user_id,
					'content_type' => 'thread',
					'content_id' => $thread->thread_id
				], $thread->User)
			;
		}

		return parent::logThreadView($thread);
	}
}