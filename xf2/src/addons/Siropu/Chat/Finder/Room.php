<?php

namespace Siropu\Chat\Finder;

use XF\Mvc\Entity\Finder;

class Room extends Finder
{
     public function fromRoom($roomId)
     {
          $this->where('room_id', $roomId);
          return $this;
     }
     public function notFromRoom($roomId)
     {
          $this->where('room_id', '<>', $roomId);
          return $this;
     }
     public function fromUser($userId)
     {
          $this->where('room_user_id', $userId);
          return $this;
     }
     public function readOnly()
     {
          $this->where('room_readonly', 1);
          return $this;
     }
     public function locked()
     {
          $this->where('room_locked', 1);
          return $this;
     }
     public function visible()
     {
          $this->where('room_state', 'visible');
          return $this;
     }
     public function deleted()
     {
          $this->where('room_state', 'deleted');
          return $this;
     }
     public function havingName($name)
     {
          $this->where('room_name', 'LIKE', $this->escapeLike($name, '%?%'));
          return $this;
     }
}
