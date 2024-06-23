<?php

namespace Siropu\Chat\Finder;

use XF\Mvc\Entity\Finder;

class BotMessage extends Finder
{
     public function isEnabled()
     {
          $this->where('message_enabled', 1);
          return $this;
     }
}
