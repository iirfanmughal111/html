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

class Category extends AbstractController
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

    public function actionGet(ParameterBag $params)
    {
        $category = $this->assertViewableCategory($params->category_id);
        $apiResult = [
            'category' => $category->toApiResult()
        ];

        if ($this->filter('with_groups', 'bool') === true) {
            $page = $this->filterPage();
            $perPage = App::getOption('groupsPerPage');

            $groupFinder = App::groupRepo()
                ->findGroupsForOverviewList([$category->category_id]);
            $total = $groupFinder->total();

            $this->assertValidApiPage($page, $perPage, $total);

            $groups = $groupFinder->fetch();
            if (XF::isApiCheckingPermissions()) {
                $groups = $groups->filterViewable();
            }

            $apiResult += [
                'groups' => $groups->toApiResults(Entity::VERBOSITY_VERBOSE),
                'pagination' => $this->getPaginationData($groups, $page, $perPage, $total)
            ];
        }

        return $this->apiResult($apiResult);
    }

    /**
     * @param mixed $categoryId
     * @return \Truonglv\Groups\Entity\Category
     * @throws \XF\Mvc\Reply\Exception
     */
    protected function assertViewableCategory($categoryId)
    {
        /** @var \Truonglv\Groups\Entity\Category $category */
        $category = $this->assertViewableApiRecord('Truonglv\Groups:Category', $categoryId);

        return $category;
    }
}
