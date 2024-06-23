<?php

namespace Siropu\Chat\Repository;

use XF\Mvc\Entity\Finder;
use XF\Mvc\Entity\Repository;

class Conversation extends Repository
{
     public function findConversations()
     {
          return $this->finder('Siropu\Chat:Conversation');
     }
     public function findConversationsForUser($userId)
     {
          return $this->findConversations()->whereOr([['user_1', $userId], ['user_2', $userId]]);
     }
     public function findConversationWithUser($userId)
     {
          $visitor = \XF::visitor();

          return $this->findConversations()
               ->whereOr([
                    [
                         ['user_1', $visitor->user_id],
                         ['user_2', $userId]
                    ],
                    [
                         ['user_2', $visitor->user_id],
                         ['user_1', $userId]
                    ]
               ])
               ->fetchOne();
     }
     public function deleteConversationMessages($id)
     {
          $this->db()->delete('xf_siropu_chat_conversation_message', 'message_conversation_id = ?', $id);
     }
     public function getUserConversations()
     {
          $visitor = \XF::visitor();

          return $this->findConversations()
			->withId($visitor->siropuChatGetConvIds())
			->fetch();
     }
     public function getConversationData($conversations)
     {
          $convItems  = \Siropu\Chat\Util\Arr::getItemArray(\XF::app()->request()->filter('conv_items', 'str'));
          $hideStatus = \XF::visitor()->getSiropuChatSetting('hide_status');

          $convData  = [];

          foreach ($conversations as $conversation)
          {
               $user = $conversation->Contact;

               if (!$user)
               {
                    continue;
               }

               $data = [
                    'activity' => $user->isOnline() ? 'active' : 'idle',
                    'status'   => $hideStatus ? '' :  strip_tags($user->siropu_chat_status),
                    'html'     => ''
               ];

               if (!in_array($conversation->conversation_id, $convItems))
               {
                    $data['html'] = $this->app()->templater()->renderMacro('public:siropu_chat_user_list', 'conv_user', [
                         'conversation' => $conversation,
                         'user'         => $user,
                         'unread'       => []
                    ]);
               }

               $convData[$conversation->conversation_id] = $data;
          }

          return $convData;
     }
}
