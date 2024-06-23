<?php

namespace Siropu\Chat\Reaction;

use XF\Reaction\AbstractHandler;
use XF\Mvc\Entity\Entity;

class RoomMessage extends AbstractHandler
{
	public function reactionsCounted(Entity $entity)
	{
		return (bool) \XF::options()->siropuChatCountRoomReactions;
	}
	public function getContentUserId(Entity $entity)
	{
		return $entity->message_user_id;
	}
	public function getEntityWith()
	{
		$visitor = \XF::visitor();

		return ['User'];
	}
}
