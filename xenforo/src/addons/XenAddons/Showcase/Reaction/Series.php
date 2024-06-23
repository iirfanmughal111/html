<?php

namespace XenAddons\Showcase\Reaction;

use XF\Reaction\AbstractHandler;
use XF\Mvc\Entity\Entity;

class Series extends AbstractHandler
{
	public function reactionsCounted(Entity $entity)
	{
		return true;
	}
}