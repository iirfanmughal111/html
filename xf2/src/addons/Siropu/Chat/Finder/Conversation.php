<?php

namespace Siropu\Chat\Finder;

use XF\Mvc\Entity\Finder;

class Conversation extends Finder
{
     public function withId($id)
     {
          $this->where('conversation_id', $id);
          return $this;
     }
}
