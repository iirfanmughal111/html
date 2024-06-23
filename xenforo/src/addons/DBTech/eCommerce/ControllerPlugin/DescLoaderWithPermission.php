<?php


namespace DBTech\eCommerce\ControllerPlugin;

use XF\ControllerPlugin\AbstractPlugin;

class DescLoaderWithPermission extends AbstractPlugin
{
	public function actionLoadDescription($shortName, $column = 'description')
	{
		$this->assertPostOnly();

		if (!$this->filter('id', 'str'))
		{
			$view = $this->view('XF:DescLoader');
			$view->setJsonParam('description', '');
			return $view;
		}

		$entity = $this->assertRecordExists($shortName, $this->filter('id', 'str'));
		if (!$entity->canView())
		{
			return $this->noPermission();
		}

		$description = $entity->{$column};

		$view = $this->view('XF:DescLoader');
		$view->setJsonParam('description', $description);
		return $view;
	}
}