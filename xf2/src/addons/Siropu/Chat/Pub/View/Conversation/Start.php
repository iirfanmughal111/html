<?php

namespace Siropu\Chat\Pub\View\Conversation;

class Start extends \XF\Mvc\View
{
	public function renderJson()
	{
		$visitor   = \XF::visitor();
		$options   = \XF::options();

		$templater = \XF::app()->templater();

          $convRepo  = \XF::repository('Siropu\Chat:Conversation');

          $params    = $this->getParams();
		$messages  = [];

		$messages[$params['convId']] = $templater->renderMacro('public:siropu_chat_message_list', 'conversation', [
			'messages' => isset($params['messages']) ? $params['messages'] : []
		]);

		return [
			'convMessages' => $messages,
			'convContacts' => isset($params['contacts']) ? $convRepo->getConversationData($params['contacts']) : [],
			'convId'       => $params['convId'],
               'convItems'    => $visitor->siropuChatGetConvIds(),
			'action'       => 'start'
		];
	}
}
