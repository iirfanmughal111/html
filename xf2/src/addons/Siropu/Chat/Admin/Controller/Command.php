<?php

namespace Siropu\Chat\Admin\Controller;

use XF\Mvc\ParameterBag;
use XF\Mvc\FormAction;

class Command extends \XF\Admin\Controller\AbstractController
{
	protected function preDispatchController($action, ParameterBag $params)
	{
		$this->assertAdminPermission('siropuChat');
	}
	public function actionIndex()
	{
		$viewParams = [
			'commands' => $this->getCommandRepo()->findCommandsForList()->fetch()
		];

		return $this->view('Siropu\Chat:Command\List', 'siropu_chat_command_list', $viewParams);
	}
	public function commandAddEdit(\Siropu\Chat\Entity\Command $command)
	{
		$viewParams = [
			'command'    => $command,
			'rooms'      => \XF::finder('Siropu\Chat:Room')->order('room_name', 'ASC')->fetch(),
			'userGroups' => $this->app->repository('XF:UserGroup')->getUserGroupTitlePairs()
		];

		return $this->view('Siropu\Chat:Command\Edit', 'siropu_chat_command_edit', $viewParams);
	}
	public function actionAdd()
	{
		$command = $this->em()->create('Siropu\Chat:Command');
		return $this->commandAddEdit($command);
	}
	public function actionEdit(ParameterBag $params)
	{
		$command = $this->assertCommandExists($params->command_name);
		return $this->commandAddEdit($command);
	}
	public function actionSave(ParameterBag $params)
	{
		$this->assertPostOnly();

		if ($params->command_name)
		{
			$command = $this->assertCommandExists($params->command_name);
		}
		else
		{
			$command = $this->em()->create('Siropu\Chat:Command');
		}

		$input = $this->filter([
			'command_name'             => 'str',
			'command_description'      => 'str',
			'command_callback_class'   => 'str',
			'command_callback_method'  => 'str',
			'command_rooms'            => 'array-uint',
			'command_user_groups'      => 'array-uint',
			'command_options_template' => 'str',
			'command_options'          => 'array',
			'command_enabled'          => 'uint'
		]);

		$command->bulkSet($input);
		$command->save();

		return $this->redirect($this->buildLink('chat/commands') . $this->buildLinkHash($command->command_name));
	}
	public function actionDelete(ParameterBag $params)
	{
		$command = $this->assertCommandExists($params->command_name);
          $plugin  = $this->plugin('XF:Delete');

          return $plugin->actionDelete(
               $command,
               $this->buildLink('chat/commands/delete', $command),
               $this->buildLink('chat/commands/edit', $command),
               $this->buildLink('chat/commands'),
               $command->command_name
          );
	}
	public function actionToggle()
	{
		$plugin = $this->plugin('XF:Toggle');
		return $plugin->actionToggle('Siropu\Chat:Command', 'command_enabled');
	}
	protected function assertCommandExists($id, $with = null, $phraseKey = null)
	{
		return $this->assertRecordExists('Siropu\Chat:Command', $id, $with, $phraseKey);
	}
	protected function getCommandRepo()
	{
		return $this->repository('Siropu\Chat:Command');
	}
}
