<?php

namespace Siropu\Chat\Command;

class Slap
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
               return $controller->message(\XF::phrase('siropu_chat_cannot_slap_yourself'));
          }

          $phrase = 'siropu_chat_slap_message';
          $object = '';

          if ($slapObjects = $command->getOption('slap_objects'))
          {
               $phrase  = 'siropu_chat_slap_with_x_message';
               $objects = \Siropu\Chat\Util\Arr::getItemArray($slapObjects, "\n");

               shuffle($objects);

               $object = $objects[0];
          }

          $messageEntity->message_type = 'slap';
          $messageEntity->message_text = \XF::phrase($phrase, [
               'fromUser' => new \XF\PreEscaped($visitor->siropuChatGetUserWrapper()),
               'toUser'   => new \XF\PreEscaped($user->siropuChatGetUserWrapper()),
               'object'   => $object
          ]);
     }
}
