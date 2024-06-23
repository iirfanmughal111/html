<?php

namespace Siropu\Chat\Pub\View\Conversation;

class Leave extends \XF\Mvc\View
{
	public function renderJson()
	{
          $params = $this->getParams();

          $jsonParams = [
			'convContacts' => '',
			'convId'       => $params['convId'],
		];

          if ($params['contacts']->count())
          {
               $jsonParams['convContacts'] = \XF::app()->templater()->renderMacro('public:siropu_chat_user_list', 'conversation',
                    ['conversations' => $params['contacts']]);
          }
          else
          {
               $jsonParams['noConversations'] = $this->renderTemplate('public:siropu_chat_conversation_list_empty');
          }

		return $jsonParams;
	}
}
