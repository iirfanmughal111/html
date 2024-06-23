<?php

namespace Siropu\Chat\Finder;

use XF\Mvc\Entity\Finder;

class Sanction extends Finder
{
     public function forUser($userId)
     {
          $this->where('sanction_user_id', $userId);
          return $this;
     }
     public function forRoom($roomId)
     {
          $this->where('sanction_room_id', $roomId);
          return $this;
     }
     public function forType($type)
     {
          $this->where('sanction_type', $type);
          return $this;
     }
}
