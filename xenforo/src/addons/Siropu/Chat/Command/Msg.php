<?php

namespace Siropu\Chat\Command;

class Msg
{
     public static function run(\XF\Mvc\Controller $controller, \Siropu\Chat\Entity\Command $command, $messageEntity, $input)
     {
          if (!$controller->isLoggedIn())
          {
               return $controller->view();
          }

          $visitor = \XF::visitor();

          if (!$visitor->canChatInPrivateSiropuChat())
          {
               return $controller->message(\XF::phrase('siropu_chat_no_permission_to_use_command'));
          }

          $params = array_filter(array_map('trim', preg_split('/,/', $input, 2, PREG_SPLIT_NO_EMPTY)));

          if (!isset($params[0], $params[1]))
          {
               return $controller->error(\XF::phrase('siropu_chat_msg_command_invalid_arguments'));
          }

          $user = \XF::em()->findOne('XF:User', ['username' => $params[0]]);

          if (!$user)
          {
               return $controller->message(\XF::phrase('requested_user_not_found'));
          }

          return \XF::service('Siropu\Chat:Conversation\Manager', $user, null, $params[1])->startConversation();
     }
}
