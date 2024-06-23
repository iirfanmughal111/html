<?php

namespace Siropu\Chat\Command;

class Join
{
     public static function run(\XF\Mvc\Controller $controller, \Siropu\Chat\Entity\Command $command, $messageEntity, $input)
     {
          if (!$controller->isLoggedIn())
          {
               return $controller->view();
          }

          $room = \XF::em()->findOne('Siropu\Chat:Room', ['room_name' => $input]);

          if (!$room)
          {
               return $controller->message(\XF::phrase('siropu_chat_requested_room_not_found'));
          }

          return $controller->plugin('Siropu\Chat:Room')->checkPermissionsAndJoin($room);
     }
}
