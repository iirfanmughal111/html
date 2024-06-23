<?php

namespace XenAddons\Showcase\Like;

use XF\Like\AbstractHandler;
use XF\Mvc\Entity\Entity;

class RatingReply extends AbstractHandler
{
	public function likesCounted(Entity $entity)
	{
		return ($entity->reply_state == 'visible');
	}
}