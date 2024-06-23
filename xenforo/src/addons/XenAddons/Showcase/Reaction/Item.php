<?php

namespace XenAddons\Showcase\Reaction;

use XF\Reaction\AbstractHandler;
use XF\Mvc\Entity\Entity;

class Item extends AbstractHandler
{
	public function reactionsCounted(Entity $entity)
	{
		return ($entity->item_state == 'visible');
	}
}