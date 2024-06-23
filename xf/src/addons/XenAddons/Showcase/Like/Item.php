<?php

namespace XenAddons\Showcase\Like;

use XF\Like\AbstractHandler;
use XF\Mvc\Entity\Entity;

class Item extends AbstractHandler
{
	public function likesCounted(Entity $entity)
	{
		return ($entity->item_state == 'visible');
	}
}