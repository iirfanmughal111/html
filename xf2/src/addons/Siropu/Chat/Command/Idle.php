<?php

namespace Siropu\Chat\Command;

class Idle
{
     public static function run(\XF\Mvc\Controller $controller, \Siropu\Chat\Entity\Command $command, $messageEntity, $input)
     {
          $visitor = \XF::visitor();
          $options = \XF::options();

          if (!$visitor->user_id)
          {
               return $controller->noPermission();
          }

          if ($controller->isRoomChannel())
          {
               $visitor->siropuChatUpdateRooms($controller->roomId, false);

               $updateLastActivity = true;

               foreach ($visitor->getSiropuChatRooms() as $roomId => $lastActivity)
               {
                    if ($lastActivity && $visitor->isActiveSiropuChat($lastActivity))
                    {
                         $updateLastActivity = false;
                         break;
                    }
               }

               if ($updateLastActivity)
               {
                    $visitor->siropu_chat_last_activity = 0;
               }

               $visitor->save();

               $reply = $controller->view();
               $reply->setJsonParams(['roomIdle' => $controller->roomId]);

               return $reply;
          }
          else
          {
               return $controller->message(\XF::phrase('siropu_chat_command_cannot_be_used_in_private_conversations'));
          }
     }
}
