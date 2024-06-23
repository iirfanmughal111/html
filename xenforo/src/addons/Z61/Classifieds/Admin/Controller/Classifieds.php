<?php

namespace Z61\Classifieds\Admin\Controller;

use XF\Admin\Controller\AbstractController;
use XF\Mvc\ParameterBag;

class Classifieds extends AbstractController
{
    protected function preDispatchController($action, ParameterBag $params)
    {
        $this->assertAdminPermission('classifieds');
    }

    public function actionIndex()
    {
        return $this->view('Z61\Classifieds:Classifieds', 'z61_classifieds');
    }

}