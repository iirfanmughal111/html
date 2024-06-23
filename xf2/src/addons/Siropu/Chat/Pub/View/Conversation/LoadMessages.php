<?php

namespace Siropu\Chat\Pub\View\Conversation;

class LoadMessages extends \XF\Mvc\View
{
	public function renderJson()
	{
          $params   = $this->getParams();
		$messages = \XF::app()->templater()->renderMacro('public:siropu_chat_message_list', 'conversation', ['messages' => $params['messages']]);

		return [
			'messages' => $messages ?: '',
			'hasMore'  => $params['hasMore'],
			'find'     => $params['find']
		];
	}
}
