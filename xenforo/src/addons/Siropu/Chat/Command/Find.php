<?php

namespace Siropu\Chat\Command;

class Find
{
     public static function run(\XF\Mvc\Controller $controller, \Siropu\Chat\Entity\Command $command, $messageEntity, $input)
     {
          $visitor = \XF::visitor();
          $options = \XF::options();

          $input   = \XF::app()->stringFormatter()->stripBbCode($input);

          switch ($controller->channel)
          {
               case 'room':
                    if (!($visitor->hasJoinedRoomSiropuChat($controller->roomId) && $visitor->canSearchSiropuChatArchive()))
                    {
                         return $controller->noPermission();
                    }

                    $messages = \XF::app()->repository('Siropu\Chat:Message')
                         ->findMessages()
                         ->fromRoom($controller->roomId)
                         ->havingText($input)
                         ->defaultLimit()
                         ->fetch()
                         ->filter(function(\Siropu\Chat\Entity\Message $message)
                         {
                              return ($message->canView());
                         });

                    $reply = $controller->view('Siropu\Chat:Message\Find');
                    break;
               case 'conversation':
                    if (!$visitor->hasConversationSiropuChat($controller->convId))
                    {
                         return $controller->noPermission();
                    }

                    $messages = \XF::app()->repository('Siropu\Chat:ConversationMessage')
                         ->findMessages()
                         ->forConversation($controller->convId)
                         ->havingText($input)
                         ->fetch();

                    $reply = $controller->view('Siropu\Chat:Conversation\LoadMessages');
                    break;
          }

          if ($messages->count())
          {
               if (!$visitor->getSiropuChatSetting('inverse'))
               {
                    $messages = $messages->reverse();
               }

               $reply->setParams([
                    'messages' => $messages,
                    'find'     => $input,
                    'hasMore'  => $messages->count() == $options->siropuChatMessageDisplayLimit
               ]);

               return $reply;
          }
          else
          {
               return $controller->message(\XF::phrase('siropu_chat_no_results_have_been_found_for_x', ['term' => $input]));
          }
     }
}
