<?php

namespace Siropu\Chat\Repository;

use XF\Mvc\Entity\Finder;
use XF\Mvc\Entity\Repository;

class ConversationMessage extends Repository
{
     public function findMessages()
     {
          return $this->finder('Siropu\Chat:ConversationMessage')
               ->order('message_id', 'DESC')
               ->limit(\XF::options()->siropuChatMessageDisplayLimit);
     }
     public function markAsRead($convId, $messageIds)
     {
          $db = $this->db();

          $db->update(
               'xf_siropu_chat_conversation_message',
               ['message_read' => 1],
               'message_id IN (' . $db->quote($messageIds) . ') AND message_conversation_id = ' . $db->quote($convId)
          );

          $visitor = \XF::visitor();
          $options = \XF::options();

          if ($options->siropuChatConversationMessageAlert)
          {
               $alertRepo = $this->repository('XF:UserAlert');
               $alertRepo->fastDeleteAlertsForContent('siropu_chat_conv', $convId);
          }
     }
}
