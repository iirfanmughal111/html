<?php

namespace Siropu\Chat\Command;

class Invite
{
     public static function run(\XF\Mvc\Controller $controller, \Siropu\Chat\Entity\Command $command, $messageEntity, $input)
     {
          if ($controller->isConvChannel())
          {
               return $controller->message(\XF::phrase('siropu_chat_command_cannot_be_used_in_private_conversations'));
          }

          $user = \XF::em()->findOne('XF:User', ['username' => preg_replace('/^@/', '', $controller->stripBbCode($input))]);

          if (!$user)
          {
               return $controller->message(\XF::phrase('requested_user_not_found'));
          }

          if ($user->user_id == \XF::visitor()->user_id)
          {
               return $controller->message(\XF::phrase('siropu_chat_cannot_invite_yourself'));
          }

          $phraseParams = ['name' => $user->username];

          if (\XF::repository('Siropu\Chat:UserAlert')->getUnreadContentAlertCount($user->user_id, 'siropu_chat_room', $controller->roomId))
          {
               return $controller->message(\XF::phrase('siropu_chat_x_has_already_been_invited_to_join_room', $phraseParams));
          }

          if ($user->hasJoinedRoomSiropuChat($controller->roomId))
          {
               return $controller->message(\XF::phrase('siropu_chat_x_already_joined_room', $phraseParams));
          }

          if (!($user->canUseSiropuChat() && $user->canJoinSiropuChatRooms() && !$user->isBannedSiropuChat()))
          {
               return $controller->message(\XF::phrase('siropu_chat_x_cannot_join_room', $phraseParams));
          }

          $sanction = $user->siropuChatGetRoomSanction($controller->roomId);

          if ($sanction && $sanction->sanction_type != 'mute')
          {
               return $controller->message(\XF::phrase('siropu_chat_x_has_an_active_room_sanction', $phraseParams));
          }

          $room = \XF::em()->find('Siropu\Chat:Room', $controller->roomId);

          $alertRepo = \XF::app()->repository('XF:UserAlert');
          $alertRepo->alert(
               $user,
               \XF::visitor()->user_id,
               \XF::visitor()->username,
               'siropu_chat_room',
               $controller->roomId,
               'invite'
          );

          return $controller->message(\XF::phrase('siropu_chat_x_has_been_invited_to_join_room', $phraseParams));
     }
}
