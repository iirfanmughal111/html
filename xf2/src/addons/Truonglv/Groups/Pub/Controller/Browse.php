<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Pub\Controller;

use XF;
use XF\Entity\User;
use function array_keys;
use Truonglv\Groups\App;
use XF\Mvc\ParameterBag;
use XF\Mvc\Entity\Finder;
use function array_filter;
use Truonglv\Groups\Entity\UserCache;
use Truonglv\Groups\Entity\MemberRole;
use XF\Pub\Controller\AbstractController;
use Truonglv\Groups\Option\GroupNodeCache;

class Browse extends AbstractController
{
    const FILTER_ADMIN = 'admin';
    const FILTER_ALL = 'all';
    const FILTER_INVITED = 'invited';

    const FROM_PROFILE_LIMIT_ITEMS = 6;

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

        $this->assertRegistrationRequired();
    }

    public function actionAdmin()
    {
        $this->request()->set('user_id', XF::visitor()->user_id);
        $this->request()->set('type', self::FILTER_ADMIN);

        return $this->rerouteController(__CLASS__, 'user');
    }

    public function actionJoined()
    {
        $this->request()->set('user_id', XF::visitor()->user_id);

        return $this->rerouteController(__CLASS__, 'user');
    }

    public function actionEvents()
    {
        $viewParams = App::eventListPlugin($this)->getEventListData(null, function (\Truonglv\Groups\Finder\Event $finder) {
            $finder->upcoming();
        });

        $this->assertValidPage($viewParams['page'], $viewParams['perPage'], $viewParams['total'], 'groups/browse/events');

        return $this->view(
            'Truonglv\Groups:Browse\Events',
            'tlg_browse_events',
            $viewParams
        );
    }

    public function actionUser()
    {
        $userId = $this->filter('user_id', 'uint');
        if ($userId === XF::visitor()->user_id) {
            /** @var \Truonglv\Groups\XF\Entity\User $user */
            $user = XF::visitor();
        } else {
            /** @var \Truonglv\Groups\XF\Entity\User|null $user */
            $user = $this->em()->find('XF:User', $userId, ['Privacy', 'Profile', 'Option']);
            if ($user === null) {
                throw $this->exception($this->notFound(XF::phrase('requested_user_not_found')));
            }
        }

        if (!$user->canTLGViewGroups($error)) {
            return $this->noPermission($error);
        }

        $filters = $this->getUserFilterInput($user);

        $finder = App::groupFinder();
        $finder->with('full');

        $dataListPlugin = App::groupListPlugin($this);

        $categoryParams = $dataListPlugin->getCategoryListData();
        $viewableCategoryIds = $categoryParams['categories']->keys();

        $finder->where('category_id', $viewableCategoryIds);

        if (isset($filters['privacy'])) {
            $finder->where('privacy', $filters['privacy']);
        }
        if (isset($filters['order'])) {
            $finder->order($filters['order'], $filters['direction']);
        }
        if (isset($filters['type'])) {
            if ($filters['type'] === self::FILTER_INVITED) {
                $finder->where('Members|' . $user->user_id . '.member_state', App::MEMBER_STATE_INVITED);
            } elseif ($filters['type'] === self::FILTER_ADMIN) {
                $finder->where('Members|' . $user->user_id . '.member_state', App::MEMBER_STATE_VALID);
                $finder->where(
                    'Members|' . $user->user_id . '.member_role_id',
                    App::memberRoleRepo()->getStaffRoleIds()
                );
            } else {
                $finder->whereImpossible();
            }
        } else {
            $finder->with('Members|' . $user->user_id, true);
        }

        $page = $this->filterPage();
        $perPage = App::getOption('groupsPerPage');

        $total = $finder->total();
        $this->assertValidPage($page, $perPage, $total, 'groups/browse/user');

        $groups = $total > 0
            ? $finder->limitByPage($page, $perPage)->fetch()->filterViewable()
            : $this->em()->getEmptyCollection();
        App::groupRepo()->addMembersIntoGroups($groups);

        return $this->view(
            'Truonglv\Groups:Browse\User',
            'tlg_browse_user',
            [
                'page' => $page,
                'perPage' => $perPage,
                'total' => $total,
                'groups' => $groups,
                'user' => $user,
                'filters' => $filters,
            ]
        );
    }

    public function actionUserFilters()
    {
        $userId = $this->filter('user_id', 'uint');
        if ($userId === XF::visitor()->user_id
            && $userId > 0
        ) {
            $user = XF::visitor();
        } else {
            /** @var \XF\Entity\User|null $user */
            $user = $this->em()->find('XF:User', $userId);
            if ($user === null) {
                throw $this->exception($this->notFound(XF::phrase('requested_user_not_found')));
            }

            if (!$user->canViewFullProfile($error)) {
                return $this->noPermission($error);
            }
        }

        $filters = $this->getUserFilterInput($user);
        if ($this->filter('apply', 'bool') === true) {
            return $this->redirect($this->buildLink('groups/browse/user', null, $filters));
        }

        return $this->view(
            'Truonglv\Groups:Browse\UserFilter',
            'tlg_browse_user_filters',
            [
                'user' => $user,
                'filters' => $filters,
            ]
        );
    }

    protected function getUserFilterInput(User $user): array
    {
        $filters = [
            'user_id' => $user->user_id,
        ];

        $input = $this->filter([
            'type' => 'str',
            'privacy' => 'str',
            'order' => 'str',
            'direction' => 'str',
        ]);

        if ($input['type'] !== '' && in_array($input['type'], [self::FILTER_ADMIN, self::FILTER_INVITED], true)) {
            if ($input['type'] === self::FILTER_INVITED && $user->user_id === XF::visitor()->user_id) {
                $filters['type'] = self::FILTER_INVITED;
            } elseif ($input['type'] === self::FILTER_ADMIN) {
                $filters['type'] = self::FILTER_ADMIN;
            }
        }

        $availableSorts = App::groupRepo()->getAvailableGroupSorts();
        if (isset($availableSorts[$input['order']])) {
            $filters['order'] = $input['order'];
            if (!in_array($input['direction'], ['asc', 'desc'], true)) {
                $input['direction'] = 'desc';
            }

            $filters['direction'] = $input['direction'];
        }

        if (in_array($input['privacy'], App::getAllowedPrivacy(), true)) {
            $filters['privacy'] = $input['privacy'];
        }

        return $filters;
    }

    public function actionFeeds()
    {
        $visitor = XF::visitor();
        /** @var UserCache|null $userCache */
        $userCache = $visitor->getRelation('TLGUserCache');
        if ($userCache === null) {
            return $this->error(XF::phrase('tlg_you_did_not_joined_any_groups_yet'));
        }

        $data = array_filter($userCache->cache_data, function ($item) {
            return isset($item[UserCache::KEY_MEMBER_STATE])
                && $item[UserCache::KEY_MEMBER_STATE] === App::MEMBER_STATE_VALID;
        });

        $groupIds = array_keys($data);
        if (\count($groupIds) === 0) {
            return $this->error(XF::phrase('tlg_you_did_not_joined_any_groups_yet'));
        }

        $page = $this->filterPage();
        $perPage = 20;

        $tabSelected = $this->filter('tab', 'str');
        $allowedTabs = ['events', 'posts'];
        $supportThreads = App::isEnabledForums();
        if ($supportThreads) {
            $allowedTabs[] = 'threads';
        }

        if (!in_array($tabSelected, $allowedTabs, true)) {
            $tabSelected = 'posts';
        }

        /** @var Finder|null $finder */
        $finder = null;

        switch ($tabSelected) {
            case 'events':
                $finder = $this->finder('Truonglv\Groups:Event');
                $finder->with('full');
                $finder->where('group_id', $groupIds);
                $finder->order('last_comment_date', 'DESC');

                break;
            case 'posts':
                $finder = $this->finder('Truonglv\Groups:Post');
                $finder->with('full');
                $finder->where('group_id', $groupIds);
                $finder->order('last_comment_date', 'DESC');

                break;
            case 'threads':
                $finder = $this->finder('XF:Thread');
                $finder->with('fullForum');
                $finder->with('FirstPost');

                $nodeIds = GroupNodeCache::getNodeIds($groupIds);
                $finder->where('node_id', $nodeIds);
                $finder->where('discussion_state', 'visible');
                $finder->order('last_post_date', 'DESC');

                break;
        }

        if ($finder === null) {
            return $this->noPermission();
        }

        $finder->limitByPage($page, $perPage);
        $total = $finder->total();
        $records = $total > 0 ? $finder->fetch() : $this->em()->getEmptyCollection();

        $this->prepareFeedEntries($records, $tabSelected);

        $viewParams = [
            'page' => $page,
            'perPage' => $perPage,
            'tabSelected' => $tabSelected,
            'total' => $total,
            'records' => $records,
            'supportThreads' => $supportThreads,
        ];

        return $this->view(
            'Truonglv\Groups:Browse\Feeds',
            'tlg_group_feeds',
            $viewParams
        );
    }

    public function actionInvited()
    {
        $this->request()->set('user_id', XF::visitor()->user_id);
        $this->request()->set('type', self::FILTER_INVITED);

        return $this->rerouteController(__CLASS__, 'user');
    }

    public function actionRoles()
    {
        $memberRoleRepo = App::memberRoleRepo();
        $memberRoles = App::memberRoleRepo()->getAllMemberRoles();
        $data = [];

        foreach ($memberRoleRepo->getMemberRoleHandlers() as $handler) {
            if (!$handler->isEnabled()) {
                continue;
            }

            $data[$handler->getRoleGroupId()] = [
                'title' => $handler->getRoleGroupTitle(),
                'perms' => []
            ];
            foreach ($handler->getRoles() as $roleId => $roleDef) {
                $data[$handler->getRoleGroupId()]['perms'][$roleId] = [
                    'title' => $roleDef['title'],
                    'perms' => []
                ];
                /** @var MemberRole $memberRole */
                foreach ($memberRoles as $memberRole) {
                    $data[$handler->getRoleGroupId()]['perms'][$roleId]['perms'][$memberRole->member_role_id] =
                        $memberRole->hasRole($handler->getRoleGroupId(), $roleId);
                }
            }
        }

        return $this->view(
            'Truonglv\Groups:Browse\Roles',
            'tlg_browse_roles',
            [
                'memberRoles' => $memberRoles,
                'data' => $data,
                'totalRoles' => count($memberRoles),
            ]
        );
    }

    /**
     * @param mixed $entities
     * @param string $tabSelected
     * @return void
     */
    protected function prepareFeedEntries($entities, $tabSelected)
    {
        if ($tabSelected === 'posts') {
            App::postRepo()->addLatestCommentsIntoPosts($entities);
        }
    }
}
