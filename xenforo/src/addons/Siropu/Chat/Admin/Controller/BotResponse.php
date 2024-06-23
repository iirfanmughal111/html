<?php

namespace Siropu\Chat\Admin\Controller;

use XF\Mvc\ParameterBag;
use XF\Mvc\FormAction;

class BotResponse extends \XF\Admin\Controller\AbstractController
{
	protected function preDispatchController($action, ParameterBag $params)
	{
		$this->assertAdminPermission('siropuChat');
	}
	public function actionIndex()
	{
		$viewParams = [
			'responses' => $this->getBotResponseRepo()->findBotResponsesForList()->fetch()
		];

		return $this->view('Siropu\Chat:BotResponse\List', 'siropu_chat_bot_response_list', $viewParams);
	}
	public function botResponseAddEdit(\Siropu\Chat\Entity\BotResponse $response)
	{
		$viewParams = [
			'response'   => $response,
			'rooms'      => \XF::finder('Siropu\Chat:Room')->order('room_name', 'ASC')->fetch(),
			'userGroups' => $this->app->repository('XF:UserGroup')->getUserGroupTitlePairs()
		];

		return $this->view('Siropu\Chat:BotResponse\Edit', 'siropu_chat_bot_response_edit', $viewParams);
	}
	public function actionAdd()
	{
		$response = $this->em()->create('Siropu\Chat:BotResponse');
		return $this->botResponseAddEdit($response);
	}
	public function actionEdit(ParameterBag $params)
	{
		$response = $this->assertBotResponseExists($params->response_id);
		return $this->botResponseAddEdit($response);
	}
	public function actionSave(ParameterBag $params)
	{
		$this->assertPostOnly();

		if ($params->response_id)
		{
			$response = $this->assertBotResponseExists($params->response_id);
		}
		else
		{
			$response = $this->em()->create('Siropu\Chat:BotResponse');
		}

		$input = $this->filter([
			'response_keyword'     => 'str',
			'response_message'     => 'str',
			'response_bot_name'    => 'str',
			'response_rooms'       => 'array-uint',
			'response_user_groups' => 'array-uint',
			'response_settings'    => 'array'
		]);

		$response->bulkSet($input);
		$response->save();

		return $this->redirect($this->buildLink('chat/bot-responses') . $this->buildLinkHash($response->response_id));
	}
	public function actionDelete(ParameterBag $params)
	{
		$response = $this->assertBotResponseExists($params->response_id);
          $plugin   = $this->plugin('XF:Delete');

          return $plugin->actionDelete(
               $response,
               $this->buildLink('chat/bot-responses/delete', $response),
               $this->buildLink('chat/bot-responses/edit', $response),
               $this->buildLink('chat/bot-responses'),
               $response->response_keyword
          );
	}
	public function actionToggle()
	{
		$plugin = $this->plugin('XF:Toggle');
		return $plugin->actionToggle('Siropu\Chat:BotResponse', 'response_enabled');
	}
	protected function assertBotResponseExists($id, $with = null, $phraseKey = null)
	{
		return $this->assertRecordExists('Siropu\Chat:BotResponse', $id, $with, $phraseKey);
	}
	protected function getBotResponseRepo()
	{
		return $this->repository('Siropu\Chat:BotResponse');
	}
}
