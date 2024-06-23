<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Admin\Controller;

use Truonglv\Groups\App;
use XF\Mvc\ParameterBag;
use function array_search;
use XF\Repository\UserGroup;
use XF\ControllerPlugin\Sort;
use XF\Admin\Controller\AbstractController;

class Category extends AbstractController
{
    /**
     * @param mixed $action
     * @param ParameterBag $params
     * @throws \XF\Mvc\Reply\Exception
     * @return void
     */
    protected function preDispatchController($action, ParameterBag $params)
    {
        $this->assertAdminPermission(App::PERMISSION_ADMIN_MANAGE_GROUPS);
    }

    public function actionIndex()
    {
        return $this->view('Truonglv\Groups:Category\List', 'tlg_category_list', [
            'nodeTree' => $this->categoryRepo()->createCategoryTree()
        ]);
    }

    public function actionSort()
    {
        $nodeTree = $this->categoryRepo()->createCategoryTree();

        if ($this->isPost()) {
            /** @var Sort $sorter */
            $sorter = $this->plugin('XF:Sort');
            $sortTree = $sorter->buildSortTree($this->filter('categories', 'json-array'));
            $sorter->sortTree($sortTree, $nodeTree->getAllData(), 'parent_category_id');

            return $this->redirect($this->buildLink('group-categories'));
        }

        return $this->view('Truonglv\Groups:Category\Sort', 'tlg_category_sort', [
            'nodeTree' => $nodeTree
        ]);
    }

    public function actionAdd()
    {
        $category = $this->getEmptyCategory();
        $category->parent_category_id = $this->filter('parent_category_id', 'uint');

        return $this->getCategoryForm($category);
    }

    public function actionEdit(ParameterBag $params)
    {
        return $this->getCategoryForm($this->assertCategoryValid($params->category_id));
    }

    public function actionSave(ParameterBag $params)
    {
        $this->assertPostOnly();

        if ($params['category_id'] <= 0) {
            $category = $this->getEmptyCategory();
        } else {
            $category = $this->assertCategoryValid($params->category_id);
        }

        $this->categorySaveProcess($category)->run();

        return $this->redirect($this->buildLink('group-categories') . $this->buildLinkHash($category->category_id));
    }

    public function actionDelete(ParameterBag $params)
    {
        $category = $this->assertCategoryValid($params['category_id']);

        if (!$category->preDelete()) {
            return $this->error($category->getErrors());
        }

        if ($this->isPost()) {
            $childAction = $this->filter('child_nodes_action', 'str');
            $category->getBehavior('XF:TreeStructured')->setOption('deleteChildAction', $childAction);
            $category->delete();

            return $this->redirect($this->buildLink('group-categories'));
        }

        $categoryRepo = $this->categoryRepo();

        $nodeTree = $categoryRepo->createCategoryTree();
        $nodeTree = $nodeTree->filter(function ($categoryId) use ($category) {
            // Filter out the current node from the node tree.
            return ($categoryId == $category->category_id ? false : true);
        });

        $viewParams = [
            'category' => $category,
            'nodeTree' => $nodeTree
        ];

        return $this->view('Truonglv\Groups:Category\Delete', 'tlg_category_delete', $viewParams);
    }

    /**
     * @param \Truonglv\Groups\Entity\Category $category
     * @return \XF\Mvc\FormAction
     */
    protected function categorySaveProcess(\Truonglv\Groups\Entity\Category $category)
    {
        $inputData = $this->filter([
            'category_title' => 'str',
            'description' => 'str',
            'parent_category_id' => 'uint',
            'display_order' => 'uint',
            'always_moderate' => 'bool',
            'min_tags' => 'uint',
            'default_privacy' => 'str',
            'disabled_navigation_tabs' => 'array-str',
            'default_tab' => 'str',
        ]);

        $getUserGroupsInput = function ($name) {
            $type = (bool) $this->filter($name . '_type', 'bool');
            if ($type) {
                return [-1];
            }

            return $this->filter($name, 'array-uint');
        };

        $inputData['allow_view_user_group_ids'] = $getUserGroupsInput('allow_view_user_group_ids');
        $inputData['allow_create_user_group_ids'] = $getUserGroupsInput('allow_create_user_group_ids');

        $form = $this->formAction();
        $form->basicEntitySave($category, $inputData);

        return $form;
    }

    /**
     * @param int $id
     * @return \Truonglv\Groups\Entity\Category
     * @throws \XF\Mvc\Reply\Exception
     */
    protected function assertCategoryValid($id)
    {
        /** @var \Truonglv\Groups\Entity\Category $category */
        $category = $this->assertRecordExists('Truonglv\Groups:Category', $id);

        return $category;
    }

    /**
     * @return \Truonglv\Groups\Entity\Category
     */
    protected function getEmptyCategory()
    {
        /** @var \Truonglv\Groups\Entity\Category $category */
        $category = $this->em()->create('Truonglv\Groups:Category');

        return $category;
    }

    /**
     * @return \Truonglv\Groups\Repository\Category
     */
    protected function categoryRepo()
    {
        return App::categoryRepo();
    }

    /**
     * @param \Truonglv\Groups\Entity\Category $category
     * @return \XF\Mvc\Reply\View
     */
    protected function getCategoryForm(\Truonglv\Groups\Entity\Category $category)
    {
        $selViewUserGroups = -1;
        if ($category->exists() && array_search(-1, $category->allow_view_user_group_ids, true) === false) {
            $selViewUserGroups = $category->allow_view_user_group_ids;
        }

        $selCreateUserGroups = -1;
        if ($category->exists() && array_search(-1, $category->allow_create_user_group_ids, true) === false) {
            $selCreateUserGroups = $category->allow_create_user_group_ids;
        }

        /** @var UserGroup $userGroupRepo */
        $userGroupRepo = $this->repository('XF:UserGroup');

        return $this->view('Truonglv\Groups:Category\Edit', 'tlg_category_edit', [
            'category' => $category,
            'nodeTree' => $this->categoryRepo()->createCategoryTree(),
            'userGroups' => $userGroupRepo->getUserGroupTitlePairs(),
            'selViewUserGroups' => $selViewUserGroups,
            'selCreateUserGroups' => $selCreateUserGroups,
            'navigationTabs' => App::navigationData()->getNavigationTabOptions(),
        ]);
    }
}
