<?php

namespace Siropu\Chat\Finder;

use XF\Mvc\Entity\Finder;

class Command extends Finder
{
     public function enabled()
     {
          $this->where('command_enabled', 1);
          return $this;
     }
}
