<?php

namespace Siropu\Chat\Finder;

use XF\Mvc\Entity\Finder;

class ConversationMessage extends Finder
{
     public function forConversation($conversationId)
     {
          $this->where('message_conversation_id', $conversationId);
          return $this;
     }
     public function havingText($text)
     {
          $this->where('message_text', 'LIKE', $this->escapeLike($text, '%?%'));
          return $this;
     }
     public function unread()
     {
          $visitor = \XF::visitor();

          $this->where('message_read', 0);
          $this->where('message_user_id', '<>', $visitor->user_id);
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
}
