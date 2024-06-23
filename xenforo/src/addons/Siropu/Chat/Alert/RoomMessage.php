<?php

namespace Siropu\Chat\Alert;

use XF\Mvc\Entity\Entity;

class RoomMessage extends \XF\Alert\AbstractHandler
{
     public function getEntityWith()
	{
		return ['Room'];
	}
     public function canViewContent(Entity $entity, &$error = null)
	{
          return true;
     }
     public function getOptOutActions()
     {
          return ['reaction'];
     }
     public function getOptOutDisplayOrder()
     {
          return 100000;
     }
}
