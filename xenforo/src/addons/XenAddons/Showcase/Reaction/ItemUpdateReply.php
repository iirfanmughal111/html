<?php

namespace XenAddons\Showcase\Reaction;

use XF\Reaction\AbstractHandler;
use XF\Mvc\Entity\Entity;

class ItemUpdateReply extends AbstractHandler
{
	public function reactionsCounted(Entity $entity)
	{
		return ($entity->reply_state == 'visible');
	}
}