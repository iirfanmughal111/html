<?php

namespace Siropu\Chat\Finder;

use XF\Mvc\Entity\Finder;

class Message extends Finder
{
     public function fromRoom($roomId)
     {
          $this->where('message_room_id', $roomId);
          return $this;
     }
     public function fromUser($userId)
     {
          $this->where('message_user_id', $userId);
          return $this;
     }
     public function fromType($type)
     {
          $this->where('message_type', $type);
          return $this;
     }
     public function notFromType($type)
     {
          $this->where('message_type', '<>', $type);
          return $this;
     }
     public function havingText($text)
     {
          $this->where('message_text', 'LIKE', $this->escapeLike($text, '%?%'));
          return $this;
     }
     public function dateBetween($start, $end)
     {
          $this->where('message_date', 'BETWEEN', [$start, $end]);
          return $this;
     }
     public function dateOlderThan($date)
     {
          $this->where('message_date', '<', $date);
          return $this;
     }
     public function dateNewerThan($date)
     {
          $this->where('message_date', '>', $date);
          return $this;
     }
     public function idBiggerThan($id)
     {
          $this->where('message_id', '>', $id);
          return $this;
     }
     public function idSmallerThan($id)
     {
          $this->where('message_id', '<', $id);
          return $this;
     }
     public function notIgnored()
     {
          $visitor = \XF::visitor();
          $this->where('message_is_ignored', [0, $visitor->user_id]);
          return $this;
     }
     public function notFromIgnoredUsers()
     {
          $visitor = \XF::visitor();

          if (!empty($visitor->Profile->ignored))
          {
               $this->where('message_user_id', '<>', array_keys($visitor->Profile->ignored));
          }

          return $this;
     }
     public function forExport($lastMessageId)
     {
          $this->where('message_id', '>', $lastMessageId);
          $this->where('message_type', 'chat');

          return $this;
     }
     public function defaultLimit()
     {
          $this->limit(\XF::options()->siropuChatMessageDisplayLimit);
          return $this;
     }
}
