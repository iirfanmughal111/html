<?php

namespace Siropu\Chat\Command;

class Unmute
{
     public static function run(\XF\Mvc\Controller $controller, \Siropu\Chat\Entity\Command $command, $messageEntity, $input)
     {
          if ($controller->isConvChannel())
          {
               return $controller->message(\XF::phrase('siropu_chat_command_cannot_be_used_in_private_conversations'));
          }

          $visitor = \XF::visitor();

          if (!$visitor->canSanctionSiropuChat())
          {
               return $controller->message(\XF::phrase('siropu_chat_no_permission_to_use_command'));
          }

          $user = \XF::em()->findOne('XF:User', ['username' => $input]);

          if (!$user)
          {
               return $controller->message(\XF::phrase('requested_user_not_found'));
          }

          $sanction = $user->siropuChatGetRoomSanction($controller->roomId);

          if (!$sanction)
          {
               return $controller->message(\XF::phrase('siropu_chat_user_x_has_not_been_muted_in_room',
                    ['user' => $user->username]));
          }

          $sanctionService = \XF::service('Siropu\Chat:Sanction\Manager', $user, $sanction);
          $sanctionService->liftSanction();

          return $controller->message(\XF::phrase('siropu_chat_x_has_been_successfully_unmuted', ['user' => $user->username]));
     }
}
