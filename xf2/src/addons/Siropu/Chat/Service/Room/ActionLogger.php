<?php

namespace Siropu\Chat\Service\Room;

class ActionLogger extends \Siropu\Chat\Service\AbstractActionLogger
{
     public function __construct(\XF\App $app, $action = '')
	{
		parent::__construct($app, $action);
	}
     public function logMessageAction(\Siropu\Chat\Entity\Message $message)
     {
          $params = [
               'action' => [$this->action => \XF::$time]
          ];

          switch ($this->action)
          {
               case 'edit':
                    $params['html'] = $this->app->bbCode()->render($message->message_text, 'html', 'siropu_chat', $message);
                    break;
               case 'like':
                    $likeCount = $message->message_like_count;

                    $params['likes'] = $likeCount ? ' <a href="' . $this->app->router()->buildLink('chat/message/likes', $message) . '" class="siropuChatMessageLikes" data-xf-click="overlay">+' . $likeCount . '</a>' : '';
                    break;
               case 'react':
                    if ($message->reaction_users)
                    {
                         $templater = $this->app->templater();
                         $templater->addDefaultParam('xf', $this->app->getGlobalTemplateData());
                         $templater->setStyle($this->app->container()->create('style', \XF::options()->defaultStyleId));

                         $reactions = $templater->fn('reactions', [$message, 'chat/message/reactions']);
                         $reactions = preg_replace('@<a (.+?)>.+?</a>@', "<a $1><i class='siropuChatReactionView'></i></a>", $reactions);
                    }
                    else
                    {
                         $reactions = '';
                    }
                    $params['reactions'] = $reactions;
                    break;
               case 'delete':
                    $params['delete'] = $message->message_id;
                    break;
               case 'prune':
                    if ($message->getOption('prune_x'))
                    {
                         $params['prune'] = $message->getOption('prune_x');
                    }
                    else
                    {
                         $params['prune'] = $message->message_type_id;
                    }
                    break;
          }

          $roomId    = $message->message_room_id;
          $messageId = $message->message_id;

          if (isset($this->actions['rooms'][$roomId][$messageId]))
          {
               $params = array_replace_recursive($this->actions['rooms'][$roomId][$messageId], $params);
          }

          $this->actions['rooms'][$roomId][$messageId] = $params;

          $this->writeData();
     }
}
