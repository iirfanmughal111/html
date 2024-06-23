<?php

namespace Siropu\Chat\Command;

class Export
{
     public static function run(\XF\Mvc\Controller $controller, \Siropu\Chat\Entity\Command $command, $messageEntity, $input)
     {
          if (preg_match('/([0-9]+)\s([0-9]+)\s([0-9]+)/i', $input, $match))
          {
               $threadId = $match[1];
               $authorId = $match[2];
               $limit    = $match[3];

               $thread = \XF::em()->find('XF:Thread', $threadId);
               $author = \XF::em()->find('XF:User', $authorId);

               if (!$thread)
               {
                    return $controller->message(\XF::phrase('siropu_chat_export_thread_not_found'));
               }

               if (!$author)
               {
                    return $controller->message(\XF::phrase('siropu_chat_export_user_not_found'));
               }

               $room = $messageEntity->Room;

               $page = 1;

               while ($messages = \XF::finder('Siropu\Chat:Message')->forExport($room->room_export_last_id)->limitByPage($page++, $limit)->fetch())
               {
                    if (!$messages->count())
                    {
                         break;
                    }

                    $text = '';

                    foreach ($messages as $message)
                    {
                         $date = \XF::language()->dateTime($message->message_date, 'absolute');
                         $text .= "{$date} - [USER={$message->message_user_id}]{$message->message_username}[/USER]: {$message->message_text}\n";
                    }

                    \XF::asVisitor($author, function() use ($thread, $text)
                    {
                         print_r($text);
                         $replier = \XF::service('XF:Thread\Replier', $thread);
                         $replier->setMessage($text);
                         $replier->setIsAutomated();
                         $replier->save();
                    });

                    $room->room_export_last_id = $message->message_id;
               }

               $room->save();

               return $controller->message(\XF::phrase('siropu_chat_export_complete'));
          }
          else
          {
               return $controller->message(\XF::phrase('siropu_chat_export_invalid_parameters'));
          }
     }
}
