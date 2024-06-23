<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Pub\Controller;

use XF;
use XF\Mvc\Reply\View;
use function array_keys;
use Truonglv\Groups\App;
use XF\Mvc\ParameterBag;
use XF\ControllerPlugin\Editor;
use XF\Mvc\Reply\AbstractReply;
use XF\Pub\Controller\AbstractController;
use Truonglv\Groups\Service\Group\Creator;

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
        parent::preDispatchController($action, $params);

        if (!App::hasPermission('view')) {
            throw $this->exception($this->noPermission());
        }
    }

    /**
     * @param mixed $action
     * @param ParameterBag $params
     * @param AbstractReply $reply
     * @return void
     */
    protected function postDispatchController($action, ParameterBag $params, AbstractReply & $reply)
    {
        parent::postDispatchController($action, $params, $reply);

        if (!$reply instanceof View) {
            return;
        }

        /** @var \Truonglv\Groups\Entity\Category|null $category */
        $category = $reply->getParam('category');
        if ($category !== null) {
            $reply->setContainerKey('tlg-group-category-' . $category->category_id);
        }
    }

    public function actionIndex(ParameterBag $params)
    {
        $category = App::assertionPlugin($this)
            ->assertCategoryViewable($params->category_id);

        $dataListPlugin = App::groupListPlugin($this);
        $categoryParams = $dataListPlugin->getCategoryListData($category);

        /** @var \XF\Tree $categoryTree */
        $categoryTree = $categoryParams['categoryTree'];
        $descendants = $categoryTree->getDescendants($category->category_id);

        $sourceCategoryIds = array_keys($descendants);
        $sourceCategoryIds[] = $category->category_id;

        // for any contextual widget
        $category->cacheViewableDescendents($descendants);

        $listParams = $dataListPlugin->getGroupListData(
            $sourceCategoryIds,
            $category,
            function (\Truonglv\Groups\Finder\Group $finder) {
                $defaultSort = App::getOption('defaultSort');
                $finder->order($defaultSort['order'], $defaultSort['direction']);
            }
        );

        $this->assertValidPage($listParams['page'], $listParams['perPage'], $listParams['total'], 'group-categories', $category);
        $this->assertCanonicalUrl($this->buildLink('group-categories', $category, ['page' => $listParams['page']]));

        $viewParams = $categoryParams + $listParams;
        $viewParams['category'] = $category;

        return $this->view('Truonglv\Groups:Category\View', 'tlg_category_view', $viewParams);
    }

    public function actionFilters(ParameterBag $params)
    {
        $category = App::assertionPlugin($this)->assertCategoryViewable($params->category_id);

        return App::groupListPlugin($this)->actionFilters($category);
    }

    public function actionAdd(ParameterBag $params)
    {
        $category = App::assertionPlugin($this)->assertCategoryViewable($params->category_id);

        if (!$category->canAddGroup($error)) {
            return $this->noPermission($error);
        }

        $dataList = App::groupListPlugin($this);
        if ($this->isPost()) {
            $creator = $this->setupGroupAddService($category);

            if (!$creator->validate($errors)) {
                return $this->error($errors);
            }

            $group = $creator->save();
            $this->finalizeGroupCreate($creator);

            return $this->redirect($this->buildLink('groups', $group));
        }

        $group = $category->getNewGroup();

        $languages = $this->app()->container('language.all');
        if (isset($languages[XF::visitor()->language_id])) {
            /** @var \XF\Entity\Language $language */
            $language = $languages[XF::visitor()->language_id];
            $group->language_code = $language->language_code;
        }

        $viewParams = [
            'category' => $category,
            'group' => $group,
            'canEditTags' => $category->canEditTags()
        ];

        return $dataList->getGroupForm('Truonglv\Groups:Group\Add', $viewParams);
    }

    /**
     * @param \Truonglv\Groups\Entity\Category $category
     * @return Creator
     */
    protected function setupGroupAddService(\Truonglv\Groups\Entity\Category $category)
    {
        /** @var \Truonglv\Groups\Service\Group\Creator $creator */
        $creator = $this->service('Truonglv\Groups:Group\Creator', $category);

        $input = $this->filter([
            'name' => 'str',
            'short_description' => 'str',
            'privacy' => 'str',
            'always_moderate_join' => 'bool',
            'allow_guest_posting' => 'bool',
        ]);
        if (App::isEnabledLanguage()) {
            $input['language_code'] = $this->filter('language_code', 'str');
        }
        if (!$category->canAddGroupType($input['privacy'], $error)) {
            throw $this->exception($this->noPermission($error));
        }

        if ($category->canEditTags()) {
            $creator->setTags($this->filter('tags', 'str'));
        }

        /** @var Editor $editorPlugin */
        $editorPlugin = $this->plugin('XF:Editor');
        $description = $editorPlugin->fromInput('description');
        $creator->setDescription($description);

        $creator->getGroup()->bulkSet($input);

        $customFields = $this->filter('custom_fields', 'array');
        $creator->setCustomFields($customFields);

        return $creator;
    }

    protected function finalizeGroupCreate(Creator $creator): void
    {
    }

    /**
     * @param array $activities
     * @return array|bool
     */
    public static function getActivityDetails(array $activities)
    {
        return \Truonglv\Groups\ControllerPlugin\Assistant::getActivityDetails($activities);
    }
}
