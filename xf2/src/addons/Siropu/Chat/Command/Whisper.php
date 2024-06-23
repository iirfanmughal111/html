<?php

namespace Siropu\Chat\Command;

class Whisper
{
     public static function run(\XF\Mvc\Controller $controller, \Siropu\Chat\Entity\Command $command, $messageEntity, $input)
     {
          if ($controller->isConvChannel())
          {
               return $controller->message(\XF::phrase('siropu_chat_command_cannot_be_used_in_private_conversations'));
          }

          $visitor = \XF::visitor();

          if (!$visitor->canWhisperSiropuChat())
          {
               return $controller->error(\XF::phrase('siropu_chat_no_permission_to_use_command'));
          }

          preg_match('/\[(.*)\](.*)$/Uis', $input, $match);

          if (empty($match))
          {
               return $controller->error(\XF::phrase('siropu_chat_whisper_command_invalid_arguments'));
          }

          $usernames = array_filter(array_map('trim', explode(',', $match[1])));

          if (in_array(strtolower($visitor->username), $usernames))
          {
               return $controller->error(\XF::phrase('siropu_chat_cannot_whisper_to_self'));
          }

          $users = \XF::finder('XF:User')
               ->where('username', $usernames)
               ->fetch();

          if (!$users->count())
          {
               return $controller->error(\XF::phrase('siropu_chat_whisper_command_no_recipients'));
          }

          if (empty($match[2]))
          {
               return $controller->error(\XF::phrase('siropu_chat_whisper_command_no_message'));
          }

          $recipients = [$visitor->user_id => $visitor->username];

          foreach ($users as $user)
          {
               $recipients[$user->user_id] = $user->username;
          }

          $messageEntity->message_text = trim($match[2]);
          $messageEntity->message_recipients = $recipients;
          $messageEntity->message_type = 'whisper';
     }
}
