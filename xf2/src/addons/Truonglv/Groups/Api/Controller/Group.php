<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Api\Controller;

use XF;
use function count;
use XF\Http\Upload;
use function explode;
use function array_map;
use Truonglv\Groups\App;
use XF\Mvc\ParameterBag;
use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Finder;
use Truonglv\Groups\Entity\Member;
use Truonglv\Groups\Service\Deleter;
use Truonglv\Groups\Service\Group\Cover;
use Truonglv\Groups\Service\Group\Avatar;
use Truonglv\Groups\Service\Group\Editor;
use Truonglv\Groups\Service\Group\Joiner;
use XF\Api\Controller\AbstractController;

class Group extends AbstractController
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
        $group = $this->assertViewableGroup($params->group_id);

        if ($this->filter('with_posts', 'bool') === true) {
            $postData = $this->getPostsInGroupPaginated($group);
        } else {
            $postData = [];
        }

        $result = [
            'group' => $group->toApiResult(Entity::VERBOSITY_VERBOSE)
        ];
        $result += $postData;

        return $this->apiResult($result);
    }

    public function actionPost(ParameterBag $params)
    {
        $group = $this->assertViewableGroup($params->group_id);
        $error = null;
        if (XF::isApiCheckingPermissions() && !$group->canEdit($error)) {
            return $this->noPermission($error);
        }

        /** @var Editor $editor */
        $editor = $this->service('Truonglv\Groups:Group\Editor', $group);
        $editor = $this->setupGroupEdit($editor);

        $errors = null;
        if (!$editor->validate($errors)) {
            return $this->error($errors);
        }

        /** @var \Truonglv\Groups\Entity\Group $group */
        $group = $editor->save();

        return $this->apiSuccess([
            'group' => $group->toApiResult()
        ]);
    }

    public function actionDelete(ParameterBag $params)
    {
        $group = $this->assertViewableGroup($params->group_id);
        $error = null;
        if (XF::isApiCheckingPermissions() && !$group->canDelete('soft', $error)) {
            return $this->noPermission($error);
        }

        /** @var Deleter $deleter */
        $deleter = $this->service('Truonglv\Groups:Deleter', $group);
        $deleter->setStateField('group_state');

        $reason = $this->filter('message', 'str');
        $deleter->delete(false, $reason);

        return $this->apiSuccess();
    }

    public function actionGetMembers(ParameterBag $params)
    {
        $group = $this->assertViewableGroup($params->group_id);

        $filtered = $this->filter([
            'member_state' => '?str',
            'member_role_id' => '?str',
            'username' => '?str'
        ]);

        $finder = App::memberFinder()->where('group_id', $group->group_id);
        if ($filtered['member_state'] !== null) {
            $finder->where('member_state', $filtered['member_state']);
        } else {
            $finder->validOnly();
        }

        if ($filtered['member_role_id'] !== null) {
            $finder->where('member_role_id', $filtered['member_role_id']);
        }
        if ($filtered['username'] !== null) {
            $finder->where('username', 'LIKE', $finder->escapeLike($filtered['username'], '?%'));
        }

        $page = $this->filterPage();
        $perPage = 20;

        $total = $finder->total();
        $this->assertValidApiPage($page, $perPage, $total);

        $members = $finder->limitByPage($page, $perPage)->fetch();

        return $this->apiResult([
            'pagination' => $this->getPaginationData($members, $page, $perPage, $total),
            'members' => $members->toApiResults(Entity::VERBOSITY_VERBOSE)
        ]);
    }

    public function actionDeleteMembers(ParameterBag $params)
    {
        $this->assertRequiredApiInput(['user_id']);
        $group = $this->assertViewableGroup($params->group_id);

        $userId = $this->filter('user_id', 'uint');
        /** @var Member|null $member */
        $member = App::memberFinder()
            ->where('group_id', $group->group_id)
            ->where('user_id', $userId)
            ->fetchOne();

        if ($member !== null) {
            if (XF::isApiCheckingPermissions() && !$member->canBeRemove($error)) {
                return $this->noPermission($error);
            }

            $member->delete();
        }

        return $this->apiSuccess();
    }

    public function actionGetPosts(ParameterBag $params)
    {
        $group = $this->assertViewableGroup($params->group_id);

        $postData = $this->getPostsInGroupPaginated($group, $this->filterPage());

        return $this->apiResult($postData);
    }

    public function actionPostJoin(ParameterBag $params)
    {
        $this->assertRegisteredUser();
        $group = $this->assertViewableGroup($params->group_id);

        $error = null;
        if (XF::isApiCheckingPermissions() && !$group->canJoin($error)) {
            return $this->error($error, 400);
        }

        if ($group->Member !== null) {
            return $this->noPermission();
        }

        /** @var Joiner $joiner */
        $joiner = $this->service('Truonglv\Groups:Group\Joiner', $group, XF::visitor());
        if (!$joiner->validate($errors)) {
            return $this->error($errors);
        }

        $member = $joiner->save();
        $joiner->sendNotifications();

        return $this->apiSuccess([
            'member' => $member->toApiResult(),
        ]);
    }

    public function actionPostLeave(ParameterBag $params)
    {
        $group = $this->assertViewableGroup($params->group_id);

        /** @var Member|null $member */
        $member = App::memberFinder()
            ->where('group_id', $group->group_id)
            ->where('user_id', XF::visitor()->user_id)
            ->fetchOne();
        if ($member !== null) {
            $error = null;
            if (XF::isApiCheckingPermissions() && !$member->canLeave($error)) {
                return $this->noPermission($error);
            }

            $member->delete();
        }

        return $this->apiSuccess();
    }

    public function actionPostAvatar(ParameterBag $params)
    {
        $group = $this->assertViewableGroup($params->group_id);
        $error = null;
        if (!$group->canManageAvatar($error)) {
            return $this->noPermission($error);
        }

        $extra = [];
        /** @var Upload|false $file */
        $file = $this->request()->getFile('avatar', false, false);
        if ($file !== false) {
            /** @var Avatar $avatar */
            $avatar = $this->service('Truonglv\Groups:Group\Avatar', $group);
            $avatar->setUpload($file);

            if (!$avatar->validate($errors)) {
                return $this->error($errors);
            }

            $avatar->upload();
            $extra['avatar_url'] = $group->getAvatarUrl(true);
        }

        return $this->apiSuccess($extra);
    }

    public function actionPostCover(ParameterBag $params)
    {
        $group = $this->assertViewableGroup($params->group_id);
        $error = null;
        if (!$group->canManageCover($error)) {
            return $this->noPermission($error);
        }

        $extra = [];
        /** @var Upload|false $file */
        $file = $this->request()->getFile('cover', false, false);
        if ($file !== false) {
            /** @var Cover $cover */
            $cover = $this->service('Truonglv\Groups:Group\Cover', $group);
            $cover->setUpload($file);

            if (!$cover->validate($errors)) {
                return $this->error($errors);
            }

            $cover->upload();
            $extra['cover_url'] = $group->getCoverUrl(true);
        }

        return $this->apiSuccess($extra);
    }

    /**
     * @param \Truonglv\Groups\Entity\Group $group
     * @param int $page
     * @return array
     * @throws \XF\Mvc\Reply\Exception
     */
    protected function getPostsInGroupPaginated(\Truonglv\Groups\Entity\Group $group, $page = 1)
    {
        $defaultPerPage = $this->getDefaultPostsPerPage();
        $limit = $this->filter('limit', '?uint');
        $perPage = $limit === null ? $defaultPerPage : min($defaultPerPage, $limit);

        $finder = $this->setupPostFinder($group);
        $this->applePostFilters($finder);

        $loadedIds = $this->getFilterLoadedIds();
        $total = null;
        if ($loadedIds !== null && count($loadedIds) === 0) {
            $finder->limitByPage($page, $perPage);

            $total = $finder->total();
            $this->assertValidApiPage($page, $perPage, $total);
        } else {
            $finder->limit($perPage);
        }

        $posts = $finder->fetch();

        App::postRepo()->addLatestCommentsIntoPosts($posts, true, false);
        $postResults = $posts->toApiResults(Entity::VERBOSITY_VERBOSE);

        $apiResult = [
            'posts' => $postResults
        ];
        if ($total === null) {
            $apiResult['hasMore'] = $posts->count() > 0;
        } else {
            $apiResult['pagination'] = $this->getPaginationData($postResults, $page, $perPage, $total);
        }

        return $apiResult;
    }

    /**
     * @param Finder $finder
     * @return void
     */
    protected function applePostFilters(Finder $finder)
    {
        $loadedIds = $this->getFilterLoadedIds();
        if ($loadedIds !== null && count($loadedIds) > 0) {
            $finder->where('post_id', '<>', $loadedIds);
        }
    }

    /**
     * @param \Truonglv\Groups\Entity\Group $group
     * @return Finder
     */
    protected function setupPostFinder(\Truonglv\Groups\Entity\Group $group)
    {
        return App::postRepo()->findPostsInGroup($group, 'api');
    }

    /**
     * @param Editor $editor
     * @return Editor
     */
    protected function setupGroupEdit(Editor $editor)
    {
        $filtered = $this->filter([
            'name' => '?str',
            'short_description' => '?str',
            'description' => '?str',
            // optional inputs
            'privacy' => '?str',
            'language_code' => '?str'
        ]);

        $group = $editor->getGroup();
        if ($filtered['name'] !== null) {
            $group->name = $filtered['name'];
        }
        if ($filtered['short_description'] !== null) {
            $group->short_description = $filtered['short_description'];
        }
        if ($filtered['description'] !== null) {
            $editor->setDescription($filtered['description']);
        }
        if ($filtered['privacy'] !== null) {
            $group->privacy = $filtered['privacy'];
        }

        if ($filtered['language_code'] !== null) {
            $group->language_code = $filtered['language_code'];
        }

        return $editor;
    }

    /**
     * @return int
     */
    protected function getDefaultPostsPerPage()
    {
        return 10;
    }

    /**
     * @return array|null
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

    /**
     * @param int $groupId
     * @param string $with
     * @return \Truonglv\Groups\Entity\Group
     * @throws \XF\Mvc\Reply\Exception
     */
    protected function assertViewableGroup($groupId, $with = 'api')
    {
        /** @var \Truonglv\Groups\Entity\Group $group */
        $group = $this->assertViewableApiRecord('Truonglv\Groups:Group', $groupId, $with);

        return $group;
    }
}
