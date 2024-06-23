<?php

namespace Siropu\Chat\Command;

class My
{
     public static function run(\XF\Mvc\Controller $controller, \Siropu\Chat\Entity\Command $command, $messageEntity, $input)
     {
          if (!$controller->isLoggedIn())
          {
               return $controller->view();
          }

          $userCommands = \XF::app()->repository('Siropu\Chat:UserCommand')
               ->findUserCommands(\XF::visitor()->user_id)
               ->fetch();

          if (!$userCommands->count())
          {
               return $controller->message(\XF::phrase('siropu_chat_no_user_custom_commands'));
          }

          $commandList = '';

          foreach ($userCommands as $userCommand)
          {
               $commandList .= '[*][B]//' . $userCommand->command_name . '[/B]: ' . $userCommand->command_value;
          }

          $messageEntity->message_text = \XF::phrase('siropu_chat_your_custom_commands') . ": [LIST=1]{$commandList}[/LIST]";
          $messageEntity->message_type = 'bot';
          $messageEntity->message_is_ignored = 1;
     }
}
