<?php

namespace Siropu\Chat\Command;

class Status
{
     public static function run(\XF\Mvc\Controller $controller, \Siropu\Chat\Entity\Command $command, $messageEntity, $input)
     {
          if (!$controller->isLoggedIn())
          {
               return $controller->view();
          }

          $visitor = \XF::visitor();

          if (!$visitor->canSetSiropuChatStatus())
          {
               return $controller->message(\XF::phrase('siropu_chat_no_permission_to_use_command'));
          }

          $visitor->siropuChatSetStatus($input, $reply);
          $visitor->save();

          return $controller->message($reply);
     }
}
