<?php

namespace Siropu\Chat\Alert;

use XF\Mvc\Entity\Entity;

class Room extends \XF\Alert\AbstractHandler
{
     public function canViewContent(Entity $entity, &$error = null)
	{
          return true;
     }
}
