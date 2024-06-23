<?php

namespace FS\PostCounter\XF\Pub\Controller;

class Thread extends XFCP_Thread
{
	/**
	 * @param \XF\Entity\Thread $thread
	 *
	 * @return \XF\Service\Thread\Replier
	 */
	protected function finalizeThreadReply(\XF\Service\Thread\Replier $replier)
	{
		parent::finalizeThreadReply($replier);
		$replier->sendNotifications();

		$thread = $replier->getThread();
		$nodeId = $thread->node_id;

		$visitor = \XF::visitor();
		$db = \XF::db();
		$pcTableName = \XF::em()->getEntityStructure('FS\PostCounter:PostCounter')->table;
		$userId = $visitor->user_id;
		$qry = "UPDATE $pcTableName SET post_count = post_count + 1 WHERE user_id = $userId and node_id=$nodeId";
		$db->query($qry);
	}
}
