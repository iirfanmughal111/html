<?php

namespace Z61\Classifieds\Admin\Controller;

use XF\Admin\Controller\AbstractController;
use XF\Mvc\ParameterBag;

class Permission extends AbstractController
{
    protected function preDispatchController($action, ParameterBag $params)
    {
        $this->assertAdminPermission('classifieds');
    }

    /**
     * @return \Z61\Classifieds\ControllerPlugin\CategoryPermission
     */
    protected function getCategoryPermissionPlugin()
    {
        /** @var \Z61\Classifieds\ControllerPlugin\CategoryPermission $plugin */
        $plugin = $this->plugin('Z61\Classifieds:CategoryPermission');
        $plugin->setFormatters('Z61\Classifieds\Permission\Category%s', 'z61_classifieds_permission_category_%s');
        $plugin->setRoutePrefix('permissions/classifieds-categories');

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
            /** @var \Z61\Classifieds\Repository\Category $categoryRepo */
            $categoryRepo = $this->repository('Z61\Classifieds:Category');
            $categories = $categoryRepo->findCategoryList()->fetch();
            $categoryTree = $categoryRepo->createCategoryTree($categories);

            $customPermissions = $this->repository('XF:PermissionEntry')->getContentWithCustomPermissions('classifieds_category');

            $viewParams = [
                'categoryTree' => $categoryTree,
                'customPermissions' => $customPermissions
            ];
            return $this->view('Z61\Classifieds:Permission\CategoryOverview', 'z61_classifieds_permission_category_overview', $viewParams);
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