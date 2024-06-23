<?php

namespace XenAddons\Showcase\Admin\Controller;

use XF\Admin\Controller\AbstractController;
use XF\Mvc\FormAction;
use XF\Mvc\ParameterBag;

class Permission extends AbstractController
{
	protected function preDispatchController($action, ParameterBag $params)
	{
		$this->assertAdminPermission('showcase');
	}

	/**
	 * @return \XenAddons\Showcase\ControllerPlugin\CategoryPermission
	 */
	protected function getCategoryPermissionPlugin()
	{
		/** @var \XenAddons\Shwocase\ControllerPlugin\CategoryPermission $plugin */
		$plugin = $this->plugin('XenAddons\Showcase:CategoryPermission');
		$plugin->setFormatters('XenAddons\Showcase\Permission\Category%s', 'xa_sc_permission_category_%s');
		$plugin->setRoutePrefix('permissions/xa-sc-categories');

		return $plugin;
	}

	public function actionCategory(ParameterBag $params)
	{
		if ($params->category_id)
		{
			return $this->getCategoryPermissionPlugin()->actionList($params);
		}
		else
		{
			$categoryRepo = $this->repository('XenAddons\Showcase:Category');
			$categories = $categoryRepo->findCategoryList()->fetch();
			$categoryTree = $categoryRepo->createCategoryTree($categories);

			$customPermissions = $this->repository('XF:PermissionEntry')->getContentWithCustomPermissions('sc_category');

			$viewParams = [
				'categoryTree' => $categoryTree,
				'customPermissions' => $customPermissions
			];
			return $this->view('XenAddons\Showcase:Permission\CategoryOverview', 'xa_sc_permission_category_overview', $viewParams);
		}
	}

	public function actionCategoryEdit(ParameterBag $params)
	{
		return $this->getCategoryPermissionPlugin()->actionEdit($params);
	}

	public function actionCategorySave(ParameterBag $params)
	{
		return $this->getCategoryPermissionPlugin()->actionSave($params);
	}
}