<?php

namespace Siropu\Chat\Finder;

use XF\Mvc\Entity\Finder;

class BotResponse extends Finder
{
     public function isEnabled()
     {
          $this->where('response_enabled', 1);
          return $this;
     }
}
