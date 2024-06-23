<?php

namespace Siropu\Chat\Command;

class Logout
{
     public static function run(\XF\Mvc\Controller $controller, \Siropu\Chat\Entity\Command $command, $messageEntity, $input)
     {
          if (!$controller->isLoggedIn())
          {
               return $controller->message(\XF::phrase('siropu_chat_you_are_not_logged_in'));
          }

          $visitor = \XF::visitor();
          $options = \XF::options();

          if (!$options->siropuChatRooms)
          {
               return $controller->message(\XF::phrase('siropu_chat_cannot_leave_room'));
          }

          if ($visitor->isInShoutboxModeSiropuChat())
          {
               return $controller->message(\XF::phrase('siropu_chat_cannot_leave_room'));
          }

          foreach ($visitor->siropuChatGetJoinedRoomsEntities() as $room)
          {
               if ($room->canLeave())
               {
                    $visitor->siropuChatLeaveRoom($room->room_id, true, $input);
               }
          }

          $visitor->save();

          $reply = $controller->view();
          $reply->setJsonParams(['logout' => true]);

          return $reply;
     }
}
