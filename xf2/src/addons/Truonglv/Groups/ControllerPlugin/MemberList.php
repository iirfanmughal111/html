<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\ControllerPlugin;

use XF\Entity\User;
use function in_array;
use Truonglv\Groups\App;
use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Finder;
use Truonglv\Groups\Entity\Group;
use Truonglv\Groups\Finder\Member;

class MemberList extends AbstractList
{
    const MEMBER_STATE_ANY = 'any';

    /**
     * @param Group $group
     * @return array
     */
    public function getMemberListData(Group $group)
    {
        $page = $this->filterPage();
        $perPage = $this->getMembersPerPage();

        $finder = App::memberFinder();

        $finder->inGroup($group);
        $finder->with('full');
        $finder->useDefaultOrder();

        $filters = $this->getFilterInput();
        $this->applyFilters($finder, $filters);

        $members = $finder->limitByPage($page, $perPage)->fetch();
        $total = $finder->total();

        $filterUser = null;
        if (isset($filters['user_id']) && $filters['user_id'] > 0) {
            $filterUser = $this->em()->find('XF:User', $filters['user_id']);
        }

        $filters['group_id'] = $group->group_id;

        return [
            'group' => $group,
            'members' => $members,
            'page' => $page,
            'perPage' => $perPage,
            'total' => $total,
            'filters' => $filters,
            'filterUser' => $filterUser
        ];
    }

    /**
     * @return int
     */
    protected function getMembersPerPage()
    {
        return 20;
    }

    /**
     * @param array $filters
     * @param Entity|null $entity
     * @return \XF\Mvc\Reply\Redirect
     */
    protected function apply(array $filters, Entity $entity = null)
    {
        return $this->redirect($this->buildLink('groups/members', $entity, $filters));
    }

    /**
     * @param array $filters
     * @param Entity|null $entity
     * @return mixed
     */
    protected function getFilterForm(array $filters, Entity $entity = null)
    {
        $filterUser = null;
        if (isset($filters['user_id'])) {
            $filterUser = $this->em()->find('XF:User', $filters['user_id']);
        }

        $viewParams = [
            'group' => $entity,
            'filters' => $filters,
            'filterUser' => $filterUser
        ];

        return $this->view(
            'Truonglv\Groups:Member\Filters',
            'tlg_member_filters',
            $viewParams
        );
    }

    /**
     * @param Finder $finder
     * @param array $filters
     * @return void
     */
    protected function applyFilters(Finder $finder, array $filters)
    {
        parent::applyFilters($finder, $filters);
        /** @var Member $mixed */
        $mixed = $finder;

        if (isset($filters['is_staff']) && $filters['is_staff'] === true) {
            $mixed->staffOnly();
        }

        if (isset($filters['member_state'])
            && $filters['member_state'] !== self::MEMBER_STATE_ANY
        ) {
            $mixed->memberState($filters['member_state']);
        }

        if (isset($filters['user_id']) && $filters['user_id'] > 0) {
            $mixed->where('user_id', $filters['user_id']);
        }
    }

    /**
     * @return array
     */
    protected function getAvailableSorts()
    {
        return [
            'joined_date' => 'joined_date',
            'username' => 'username'
        ];
    }

    /**
     * @return array
     */
    protected function getFilterInput()
    {
        $filters = [];

        $input = $this->filter([
            'member_state' => 'str',
            'user' => 'str',
            'user_id' => 'uint',
            'order' => 'str',
            'direction' => 'str',
            'is_staff' => 'bool'
        ]);

        if ($input['member_state'] === ''
            && !$this->request->exists('member_state')
        ) {
            $input['member_state'] = App::MEMBER_STATE_VALID;
        }

        if ($input['user_id'] > 0) {
            $filters['user_id'] = $input['user_id'];
        } elseif ($input['user'] !== '') {
            /** @var User|null $user */
            $user = $this->em()->findOne('XF:User', ['username' => $input['user']]);
            if ($user !== null) {
                $filters['user_id'] = $user->user_id;
            }
        }

        $sorts = $this->getAvailableSorts();

        if ($input['order'] !== '' && isset($sorts[$input['order']])) {
            if (!in_array($input['direction'], ['asc', 'desc'], true)) {
                $input['direction'] = 'desc';
            }

            $defaultOrder = 'joined_date';
            if ($input['order'] != $defaultOrder || $input['direction'] != 'desc') {
                $filters['order'] = $input['order'];
                $filters['direction'] = $input['direction'];
            }
        }

        $states = App::memberRepo()->getAllowedMemberStates();
        $states[] = self::MEMBER_STATE_ANY;

        if ($input['member_state'] !== ''
            && in_array($input['member_state'], $states, true)
        ) {
            $filters['member_state'] = $input['member_state'];
        }

        if ($input['is_staff'] === true) {
            $filters['is_staff'] = $input['is_staff'];
        }

        return $filters;
    }
}
