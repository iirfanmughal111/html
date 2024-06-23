<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Api\Controller;

use XF;
use function min;
use function count;
use function explode;
use function array_map;
use Truonglv\Groups\App;
use XF\Mvc\ParameterBag;
use Truonglv\Groups\Entity\Category;
use XF\Api\Controller\AbstractController;
use Truonglv\Groups\Service\Group\Creator;

class Groups extends AbstractController
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
        $page = $this->filterPage();
        $defaultPerPage = App::getOption('groupsPerPage');

        $limit = $this->filter('limit', '?uint');
        if ($limit !== null) {
            $perPage = min($limit, $defaultPerPage);
        } else {
            $perPage = $defaultPerPage;
        }

        $groupFinder = $this->getGroupFinderForList();
        $this->applyFilters($groupFinder);

        $loadedIds = $this->getFilterLoadedIds();
        $total = null;

        if (count($loadedIds) === 0) {
            $groupFinder->limitByPage($page, $perPage);
            $total = $groupFinder->total();

            $this->assertValidApiPage($page, $perPage, $total);
        } else {
            $groupFinder->limit($perPage);
        }

        $groups = $groupFinder->fetch();
        if (XF::isApiCheckingPermissions()) {
            // only filtered to the forums we could view -- could still be other conditions
            $groups = $groups->filterViewable();
        }

        $apiResult = [
            'groups' => $groups->toApiResults()
        ];
        if ($total === null) {
            $apiResult['hasMore'] = $groups->count() > 0;
        } else {
            $apiResult['pagination'] = $this->getPaginationData($groups, $page, $perPage, $total);
        }

        return $this->apiResult($apiResult);
    }

    public function actionPost()
    {
        $this->assertRequiredApiInput([
            'name',
            'short_description',
            'description',
            'category_id'
        ]);

        $filtered = $this->filter([
            'name' => 'str',
            'short_description' => 'str',
            'description' => 'str',
            'category_id' => 'uint'
        ]);

        /** @var Category $category */
        $category = $this->assertViewableApiRecord(
            'Truonglv\Groups:Category',
            $filtered['category_id']
        );
        $error = null;
        if (XF::isApiCheckingPermissions() && !$category->canAddGroup($error)) {
            return $this->noPermission($error);
        }

        /** @var Creator $creator */
        $creator = $this->service('Truonglv\Groups:Group\Creator', $category);
        $creator->setDescription($filtered['description']);

        $creator->getGroup()->bulkSet([
            'name' => $filtered['name'],
            'short_description' => $filtered['short_description']
        ]);

        if (!$creator->validate($errors)) {
            return $this->error($errors);
        }

        /** @var \Truonglv\Groups\Entity\Group $group */
        $group = $creator->save();

        return $this->apiSuccess([
            'group' => $group->toApiResult()
        ]);
    }

    /**
     * @param Creator $creator
     * @return Creator
     */
    protected function setupGroupCreate(Creator $creator)
    {
        $filtered = $this->filter([
            'name' => 'str',
            'short_description' => 'str',
            'description' => 'str',
            'category_id' => 'uint',
            // optional inputs
            'privacy' => '?str',
            'language_code' => '?str'
        ]);

        $creator->getGroup()->bulkSet([
            'name' => $filtered['name'],
            'short_description' => $filtered['short_description']
        ]);
        $creator->setDescription($filtered['description']);

        $group = $creator->getGroup();
        if ($filtered['privacy'] !== null) {
            $group->privacy = $filtered['privacy'];
        }
        if ($filtered['language_code'] !== null) {
            $group->language_code = $filtered['language_code'];
        }

        return $creator;
    }

    /**
     * @param \Truonglv\Groups\Finder\Group $finder
     * @throws \XF\Mvc\Reply\Exception
     * @return void
     */
    protected function applyFilters(\Truonglv\Groups\Finder\Group $finder)
    {
        $input = $this->filter([
            'category_id' => '?uint',
            'privacy' => '?str',
            'user_id' => '?uint'
        ]);

        if ($input['category_id'] !== null) {
            /** @var Category $category */
            $category = $this->assertViewableApiRecord('Truonglv\Groups:Category', $input['category_id']);
            $finder->inCategory($category);
        }
        if ($input['privacy'] !== null) {
            $finder->where('privacy', $input['privacy']);
        }
        if ($input['user_id'] !== null) {
            $finder->where(
                'Members|' . $input['user_id'] . '.member_state',
                App::memberRepo()->getValidMemberStates()
            );
        }

        $loadedIds = $this->getFilterLoadedIds();
        if (count($loadedIds) > 0) {
            $finder->where('group_id', '<>', $loadedIds);
        }
    }

    /**
     * @return \Truonglv\Groups\Finder\Group
     */
    protected function getGroupFinderForList()
    {
        $groupFinder = App::groupFinder();
        $groupFinder->with('api');

        return $groupFinder;
    }

    /**
     * @return array
     */
    protected function getFilterLoadedIds()
    {
        $loadedIds = $this->filter('loaded_ids', '?str');
        if ($loadedIds === null) {
            return [];
        }

        $loadedIds = explode(',', $loadedIds);
        $loadedIds = array_map('intval', $loadedIds);

        return $loadedIds;
    }
}
