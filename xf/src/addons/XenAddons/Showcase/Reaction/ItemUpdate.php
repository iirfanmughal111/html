<?php

namespace XenAddons\Showcase\Reaction;

use XF\Reaction\AbstractHandler;
use XF\Mvc\Entity\Entity;

class ItemUpdate extends AbstractHandler
{
	public function reactionsCounted(Entity $entity)
	{
		return ($entity->update_state == 'visible');
	}
}