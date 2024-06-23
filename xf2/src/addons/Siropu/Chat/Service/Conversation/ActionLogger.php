<?php

namespace Siropu\Chat\Service\Conversation;

class ActionLogger extends \Siropu\Chat\Service\AbstractActionLogger
{
     public function __construct(\XF\App $app, $action = '')
	{
		parent::__construct($app, $action);
	}
     public function logMessageAction(\Siropu\Chat\Entity\ConversationMessage $message)
     {
          $params = [
               'action' => [$this->action => \XF::$time]
          ];

          switch ($this->action)
          {
               case 'like':
                    $params['likes'] = $message->message_liked ? ' <a role="button" class="siropuChatMessageLikes">+1</a>' : '';
                    break;
               case 'react':
                    $reactions = '';

                    if ($message->reaction_users)
                    {
                         $templater = $this->app->templater();
                         $templater->addDefaultParam('xf', $this->app->getGlobalTemplateData());
                         $templater->setStyle($this->app->container()->create('style', \XF::options()->defaultStyleId));

                         $reactions = $templater->fn('reactions_summary', [$message->reactions]);
                    }

                    $params['reactions'] = $reactions;
                    break;
          }

          $convId    = $message->message_conversation_id;
          $messageId = $message->message_id;

          if (isset($this->actions['conversations'][$convId][$messageId]))
          {
               $params = array_replace_recursive($this->actions['conversations'][$convId][$messageId], $params);
          }

          $this->actions['conversations'][$convId][$messageId] = $params;

          $this->writeData();
     }
}
