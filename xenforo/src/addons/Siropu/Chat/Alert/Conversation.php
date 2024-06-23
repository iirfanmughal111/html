<?php

namespace Siropu\Chat\Alert;

use XF\Mvc\Entity\Entity;

class Conversation extends \XF\Alert\AbstractHandler
{
     public function canViewContent(Entity $entity, &$error = null)
     {
          return true;
     }
}
