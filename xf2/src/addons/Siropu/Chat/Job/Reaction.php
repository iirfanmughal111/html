<?php

namespace Siropu\Chat\Job;

use XF\Job\AbstractRebuildJob;

class Reaction extends AbstractRebuildJob
{
	protected function getNextIds($start, $batch)
	{
		$db = $this->app->db();

		return $db->fetchAllColumn($db->limit(
			"
				SELECT content_id
				FROM xf_reaction_content
                    WHERE content_type = 'siropu_chat_room_message'
				AND content_id > ?
                    ORDER BY content_id
			", $batch
		), $start);
	}
     protected function rebuildById($id)
	{
          /** @var \Siropu\Chat\Entity\Message $message */
		$message = $this->app->em()->find('Siropu\Chat:Message', $id);

          if (!$message)
          {
               \XF::db()->delete('xf_reaction_content', 'content_type = ? AND content_id = ?', ['siropu_chat_room_message', $id]);
          }
     }
	protected function getStatusType()
	{
		return \XF::phrase('siropu_chat_removing_reactions_for_deleted_messages');
	}
}
