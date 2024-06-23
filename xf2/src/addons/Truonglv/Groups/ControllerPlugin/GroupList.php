<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\ControllerPlugin;

use Closure;
use XF\Entity\User;
use XF\Data\Language;
use function in_array;
use Truonglv\Groups\App;
use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Finder;
use function array_replace;
use function call_user_func;
use Truonglv\Groups\Entity\Category;

class GroupList extends AbstractList
{
    /**
     * @param Category|null $category
     * @return array
     */
    public function getCategoryListData(Category $category = null)
    {
        $categoryRepo = App::categoryRepo();
        $categories = $categoryRepo->getViewableCategories();
        $categoryTree = $categoryRepo->createCategoryTree($categories);
        $categoryExtras = $categoryRepo->getCategoryListExtras($categoryTree);

        return [
            'categories' => $categories,
            'categoryTree' => $categoryTree,
            'categoryExtras' => $categoryExtras
        ];
    }

    /**
     * @param array $sourceCategoryIds
     * @param Category|null $category
     * @param Closure|null $prepareFinder
     * @return array
     */
    public function getGroupListData(
        array $sourceCategoryIds,
        Category $category = null,
        Closure $prepareFinder = null
    ) {
        $groupRepo = App::groupRepo();

        $groupFinder = $groupRepo->findGroupsForOverviewList($sourceCategoryIds, [
            'allowOwnPending' => true
        ]);

        if ($prepareFinder !== null) {
            call_user_func($prepareFinder, $groupFinder);
        }

        $filters = $this->getFilterInput();
        $this->applyFilters($groupFinder, $filters);

        $total = $groupFinder->total();

        $page = $this->filterPage();
        $perPage = App::getOption('groupsPerPage');

        $groupFinder->limitByPage($page, $perPage);

        $groups = $groupFinder->fetch()->filterViewable();
        $groupRepo->addMembersIntoGroups($groups);

        if (isset($filters['creator_id'])) {
            $creatorFilter = $this->em()->find('XF:User', $filters['creator_id']);
        } else {
            $creatorFilter = null;
        }

        $canInlineMod = false;
        foreach ($groups as $group) {
            /** @var \Truonglv\Groups\Entity\Group $group */
            if ($group->canUseInlineModeration()) {
                $canInlineMod = true;

                break;
            }
        }

        return [
            'groups' => $groups,
            'filters' => $filters,
            'creatorFilter' => $creatorFilter,
            'canInlineMod' => $canInlineMod,

            'total' => $total,
            'page' => $page,
            'perPage' => $perPage
        ];
    }

    /**
     * @param string $viewName
     * @param array $params
     * @param string $template
     * @return \XF\Mvc\Reply\View
     */
    public function getGroupForm($viewName, array $params, $template = 'tlg_group_add')
    {
        if ($this->options()->tl_groups_enableLanguage > 0) {
            $params = array_replace([
                'languages' => $this->app->data('XF:Language')->getLocaleList()
            ], $params);
        }

        return $this->view($viewName, $template, $params);
    }

    /**
     * @param Finder $finder
     * @param array $filters
     * @return void
     */
    protected function applyFilters(Finder $finder, array $filters)
    {
        parent::applyFilters($finder, $filters);

        if (isset($filters['privacy']) && $filters['privacy'] !== '') {
            $finder->where('privacy', $filters['privacy']);
        }

        if (isset($filters['creator_id']) && $filters['creator_id'] > 0) {
            $finder->where('owner_user_id', intval($filters['creator_id']));
        }

        if (isset($filters['language_code']) && $filters['language_code'] !== '') {
            $finder->where('language_code', $filters['language_code']);
        }
    }

    /**
     * @param array $filters
     * @param Entity|null $entity
     * @return \XF\Mvc\Reply\Redirect
     */
    protected function apply(array $filters, Entity $entity = null)
    {
        return $this->redirect($this->buildLink(
            $entity !== null ? 'group-categories' : 'groups',
            $entity,
            $filters
        ));
    }

    /**
     * @param array $filters
     * @param Entity|null $entity
     * @return mixed
     */
    protected function getFilterForm(array $filters, Entity $entity = null)
    {
        if (isset($filters['creator_id'])) {
            $creatorFilter = $this->em()->find('XF:User', $filters['creator_id']);
        } else {
            $creatorFilter = null;
        }

        $applicableCategories = App::categoryRepo()->getViewableCategories($entity);
        $applicableCategoryIds = $applicableCategories->keys();
        if ($entity instanceof Category) {
            $applicableCategoryIds[] = $entity->category_id;
        }

        $formAction = $entity !== null
            ? $this->buildLink('group-categories/filters', $entity)
            : $this->buildLink('groups/filters');

        $viewParams = [
            'category' => $entity,
            'filters' => $filters,
            'creatorFilter' => $creatorFilter,
            'formAction' => $formAction,
        ];

        if (App::isEnabledLanguage()) {
            $viewParams['languages'] = $this->getLanguages();
        }

        return $this->view('Truonglv\Groups:Filters', 'tlg_filters', $viewParams);
    }

    /**
     * @return array
     */
    protected function getLanguages()
    {
        /** @var Language $language */
        $language = $this->data('XF:Language');

        return $language->getLocaleList();
    }

    /**
     * @return array
     */
    protected function getFilterInput()
    {
        $filters = [];

        $input = $this->filter([
            'privacy' => 'str',
            'creator' => 'str',
            'creator_id' => 'uint',
            'order' => 'str',
            'direction' => 'str',
            'language_code' => 'str',
        ]);

        if ($input['creator_id'] > 0) {
            $filters['creator_id'] = $input['creator_id'];
        } elseif ($input['creator'] !== '') {
            /** @var User|null $user */
            $user = $this->em()->findOne('XF:User', ['username' => $input['creator']]);
            if ($user !== null) {
                $filters['creator_id'] = $user->user_id;
            }
        }

        $sorts = $this->getAvailableSorts();
        $defaultSort = App::getOption('defaultSort');

        if ($input['order'] !== '' && isset($sorts[$input['order']])) {
            $defaultOrder = $defaultSort['order'];
            $defaultDirection = $defaultSort['direction'];

            if (!in_array($input['direction'], ['asc', 'desc'], true)) {
                $input['direction'] = $defaultDirection;
            }

            if ($input['order'] != $defaultOrder || $input['direction'] != 'desc') {
                $filters['order'] = $input['order'];
                $filters['direction'] = $input['direction'];
            }
        }

        if ($input['privacy'] !== '' && in_array($input['privacy'], App::groupRepo()->getAllowedPrivacy(), true)) {
            $filters['privacy'] = $input['privacy'];
        }

        if (App::isEnabledLanguage() && $input['language_code'] !== '') {
            $languages = $this->getLanguages();
            if (isset($languages[$input['language_code']])) {
                $filters['language_code'] = $input['language_code'];
            }
        }

        return $filters;
    }

    /**
     * @return array
     */
    protected function getAvailableSorts()
    {
        return App::groupRepo()->getAvailableGroupSorts();
    }
}
