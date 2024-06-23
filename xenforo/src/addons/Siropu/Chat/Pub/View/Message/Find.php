<?php

namespace Siropu\Chat\Pub\View\Message;

class Find extends \XF\Mvc\View
{
	public function renderJson()
	{
		$messages = \XF::app()->templater()->renderMacro('public:siropu_chat_message_list', 'room',
			['messages' => $this->getParams()['messages']]);

		return [
			'messages' => $messages ?: '',
			'hasMore'  => $this->getParams()['hasMore'],
			'find'     => $this->getParams()['find']
		];
	}
}
