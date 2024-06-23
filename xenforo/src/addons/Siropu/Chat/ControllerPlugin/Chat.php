<?php

namespace Siropu\Chat\ControllerPlugin;

class Chat extends \XF\ControllerPlugin\AbstractPlugin
{
	public function loadChat(array $params = [])
	{
          return $this->view('Siropu\Chat:Chat', 'siropu_chat_page', ['chat' => array_merge(['isChatPage' => true], $params)]);
	}
	public function help()
	{
		$commands = $this->repository('Siropu\Chat:Command')
			->findActiveCommands()
			->fetch()
			->filter(function(\Siropu\Chat\Entity\Command $command)
			{
				return ($command->hasPermission());
			});

		$viewParams = [
			'commands' => $commands
		];

		return $this->view('Siropu\Chat:Help', 'siropu_chat_help', $viewParams);
	}
	public function getChatData()
	{
		$class = $this->app()->extendClass('Siropu\Chat\Data');
		return new $class();
	}
	public function getHtmlMessage()
	{
		$messageHtml = $this->filter('message_html', 'str,no-clean');

		if (!\XF::options()->siropuChatMultiLineMessages)
          {
               $messageHtml = str_replace('<br>', '', $messageHtml);
          }

		return $messageHtml;
	}
}
