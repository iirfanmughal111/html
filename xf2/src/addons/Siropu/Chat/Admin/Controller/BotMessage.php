<?php

namespace Siropu\Chat\Admin\Controller;

use XF\Mvc\ParameterBag;
use XF\Mvc\FormAction;

class BotMessage extends \XF\Admin\Controller\AbstractController
{
	protected function preDispatchController($action, ParameterBag $params)
     {
          $this->assertAdminPermission('siropuChat');
     }
	public function actionIndex()
	{
		$viewParams = [
			'messages' => $this->getBotMessageRepo()->findBotMessagesForList()->fetch()
		];

		return $this->view('Siropu\Chat:BotMessage\List', 'siropu_chat_bot_message_list', $viewParams);
	}
	public function botMessageAddEdit(\Siropu\Chat\Entity\BotMessage $message)
	{
		$viewParams = [
			'message' => $message,
			'rooms'   => \XF::finder('Siropu\Chat:Room')->order('room_name', 'ASC')->fetch(),
		];

		return $this->view('Siropu\Chat:BotMessage\Edit', 'siropu_chat_bot_message_edit', $viewParams);
	}
	public function actionAdd()
	{
		$message = $this->em()->create('Siropu\Chat:BotMessage');
		return $this->botMessageAddEdit($message);
	}
	public function actionEdit(ParameterBag $params)
	{
		$message = $this->assertBotMessageExists($params->message_id);
		return $this->botMessageAddEdit($message);
	}
	public function actionSave(ParameterBag $params)
	{
		$this->assertPostOnly();

		if ($params->message_id)
		{
			$message = $this->assertBotMessageExists($params->message_id);
		}
		else
		{
			$message = $this->em()->create('Siropu\Chat:BotMessage');
		}

		$input = $this->filter([
			'message_bot_name' => 'str',
			'message_title'    => 'str',
			'message_text'     => 'str',
			'message_rooms'    => 'array-uint',
			'message_rules'    => 'array'
		]);

		$message->bulkSet($input);
		$message->save();

		return $this->redirect($this->buildLink('chat/bot-messages') . $this->buildLinkHash($message->message_id));
	}
	public function actionDelete(ParameterBag $params)
	{
		$message = $this->assertBotMessageExists($params->message_id);
          $plugin  = $this->plugin('XF:Delete');

          return $plugin->actionDelete(
               $message,
               $this->buildLink('chat/bot-messages/delete', $message),
               $this->buildLink('chat/bot-messages/edit', $message),
               $this->buildLink('chat/bot-messages'),
               $message->message_title
          );
	}
	public function actionToggle()
	{
		$plugin = $this->plugin('XF:Toggle');
		return $plugin->actionToggle('Siropu\Chat:BotMessage', 'message_enabled');
	}
	protected function assertBotMessageExists($id, $with = null, $phraseKey = null)
	{
		return $this->assertRecordExists('Siropu\Chat:BotMessage', $id, $with, $phraseKey);
	}
	protected function getBotMessageRepo()
	{
		return $this->repository('Siropu\Chat:BotMessage');
	}
}
