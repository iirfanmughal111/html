<?php

namespace Siropu\Chat\Job;

use XF\Job\AbstractRebuildJob;

class ConvWhosTyping extends AbstractRebuildJob
{
	protected function getNextIds($start, $batch)
	{
		$db = $this->app->db();

		return $db->fetchAllColumn($db->limit(
			"
				SELECT conversation_id
				FROM xf_siropu_chat_conversation
				WHERE conversation_id > ?
                    ORDER BY conversation_id
			", $batch
		), $start);
	}
     protected function rebuildById($id)
	{
          /** @var \Siropu\Chat\Entity\Conversation $conversation */
		$conversation = $this->app->em()->find('Siropu\Chat:Conversation', $id);
          $conversation->writeWhosTypingJsonFile();
     }
	protected function getStatusType()
	{
		return \XF::phrase('siropu_chat_create_conversation_whos_typing_json_files');
	}
}
