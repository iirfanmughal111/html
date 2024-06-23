<?php

namespace Siropu\Chat\Admin\Controller;

use XF\Mvc\ParameterBag;
use XF\Mvc\FormAction;

class Chat extends \XF\Admin\Controller\AbstractController
{
	protected function preDispatchController($action, ParameterBag $params)
	{
		$this->assertAdminPermission('siropuChat');
	}
	public function actionIndex()
	{
		$viewParams = [];

		return $this->view('Siropu\Chat:Chat', 'siropu_chat', $viewParams);
	}
}
