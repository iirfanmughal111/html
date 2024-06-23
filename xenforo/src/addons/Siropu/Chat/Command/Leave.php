<?php

namespace Siropu\Chat\Command;

class Leave
{
     public static function run(\XF\Mvc\Controller $controller, \Siropu\Chat\Entity\Command $command, $messageEntity, $input)
     {
          $visitor = \XF::visitor();
          $options = \XF::options();

          if (!$visitor->user_id)
          {
               $session = \XF::app()->session();

               if ($session->get('siropuChatGuestNickname'))
               {
                    $guestServiceManager = \XF::service('Siropu\Chat:Guest\Manager');
                    $guestServiceManager->leaveRoom($controller->roomId, $input);

                    $session->set('siropuChatGuestNickname', null);

                    $phrase = \XF::phrase('siropu_chat_you_have_left_the_room');
               }
               else
               {
                    $phrase = \XF::phrase('siropu_chat_you_are_not_logged_in');
               }

               return $controller->message($phrase);
          }

          if ($controller->isRoomChannel())
          {
               if (!$messageEntity->Room->canLeave($error))
               {
                    return $controller->message($error);
               }

               if ($visitor->isInShoutboxModeSiropuChat())
               {
                    return $controller->message(\XF::phrase('siropu_chat_cannot_leave_room'));
               }

               $visitor->siropuChatLeaveRoom($controller->roomId, true, $input);
               $visitor->save();

               $reply = $controller->view();
               $reply->setJsonParams(['leaveRoom' => $controller->roomId]);

               return $reply;
          }
          else
          {
               if (!$visitor->hasConversationSiropuChat($controller->convId))
               {
                    return $controller->noPermission();
               }

               $conversation = \XF::em()->find('Siropu\Chat:Conversation', $controller->convId);

               $reply = \XF::service('Siropu\Chat:Conversation\Manager', null, $conversation, null)->leaveConversation();
               $reply->setJsonParams(['leaveConv' => $controller->convId]);

               return $reply;
          }
     }
}
