<?php

namespace Siropu\Chat\Command;

class Hug
{
     public static function run(\XF\Mvc\Controller $controller, \Siropu\Chat\Entity\Command $command, $messageEntity, $input)
     {
          if (!$controller->isLoggedIn())
          {
               return $controller->view();
          }

          $user = \XF::em()->findOne('XF:User', ['username' => ltrim($input, '@')]);

          if (!$user)
          {
               return $controller->message(\XF::phrase('requested_user_not_found'));
          }

          $visitor = \XF::visitor();

          if ($user->user_id == $visitor->user_id)
          {
               return $controller->message(\XF::phrase('siropu_chat_cannot_hug_yourself'));
          }

          $messageEntity->message_type = 'hug';
          $messageEntity->message_text = \XF::phrase('siropu_chat_hug_message', [
               'fromUser' => new \XF\PreEscaped($visitor->siropuChatGetUserWrapper()),
               'toUser'   => new \XF\PreEscaped($user->siropuChatGetUserWrapper()),
          ]);
     }
}
