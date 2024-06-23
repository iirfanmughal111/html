<?php

namespace Siropu\Chat\Command;

class Ban
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

          $args = array_map('trim', explode(',', $input));

          if (!isset($args[0], $args[1]))
          {
               return $controller->error(\XF::phrase('siropu_chat_sanction_command_invalid_arguments'));
          }

          $user = \XF::em()->findOne('XF:User', ['username' => $args[0]]);

          if (!$user)
          {
               return $controller->error(\XF::phrase('requested_user_not_found'));
          }

          $length = strtolower($args[1]);
          $reason = isset($args[2]) ? $args[2] : '';

          if (!preg_match('/([1-9]([0-9]+)?\s+(hour|day|week|month|year)s?|permanent)/', $length))
          {
               return $controller->error(\XF::phrase('siropu_chat_sanction_command_invalid_length'));
          }

          $data = [
               'room_id'  => $messageEntity->message_room_id,
               'end_date' => $length == 'permanent' ? 0 : strtotime($length),
               'reason'   => $reason
          ];

          $sanction = \XF::service('Siropu\Chat:Sanction\Manager', $user, null, $data);
          $sanction->applySanction('ban', $error);

          if ($error)
          {
               return $controller->error($error);
          }

          $reply = $controller->message(\XF::phrase('siropu_chat_x_has_been_successfully_banned', ['user' => $user->username]));
          $reply->setJsonParams(['sanctioned' => $user->user_id]);

          return $reply;
     }
}
