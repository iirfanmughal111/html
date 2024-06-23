<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Api\Controller;

use XF;
use Truonglv\Groups\App;
use XF\Mvc\ParameterBag;
use XF\Mvc\Entity\Entity;
use XF\Api\Controller\AbstractController;

class Categories extends AbstractController
{
    /**
     * @param mixed $action
     * @param ParameterBag $params
     * @return void
     */
    protected function preDispatchController($action, ParameterBag $params)
    {
        $this->assertApiScopeByRequestMethod('tl_groups');
    }

    public function actionGet()
    {
        $finder = App::categoryRepo()->findCategoryList();
        $categories = $finder->fetch();

        if (XF::isApiCheckingPermissions()) {
            $categories = $categories->filterViewable();
        }

        return $this->apiResult([
            'categories' => $categories->toApiResults(Entity::VERBOSITY_VERBOSE)
        ]);
    }
}
