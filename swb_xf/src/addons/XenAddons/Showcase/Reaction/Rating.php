<?php

namespace XenAddons\Showcase\Reaction;

use XF\Reaction\AbstractHandler;
use XF\Mvc\Entity\Entity;

class Rating extends AbstractHandler
{
	public function reactionsCounted(Entity $entity)
	{
		return ($entity->rating_state == 'visible');
	}
}