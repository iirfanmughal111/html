<?php

namespace FS\PostCounter\XF\Entity;

class Post extends XFCP_Post
{
	protected function _postDelete()
	{
		parent::_postDelete();

		$thread = $this->Thread;
		$nodeId = $thread->node_id;

		$visitor = \XF::visitor();
		$db = \XF::db();
		$pcTableName = \XF::em()->getEntityStructure('FS\PostCounter:PostCounter')->table;
		$userId = $visitor->user_id;
		$qry = "UPDATE $pcTableName SET post_count = post_count - 1 WHERE user_id = $userId and node_id=$nodeId";
		$db->query($qry);
	}
}
