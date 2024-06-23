<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Finder;

use XF;
use Truonglv\Groups\App;
use XF\Mvc\Entity\Finder;
use function array_replace;

class Member extends Finder
{
    /**
     * @param array $filters
     * @return $this
     */
    public function applyFilters(array $filters)
    {
        $filters = array_replace([
            'is_staff' => false,
            'order' => '',
            'user_id' => 0,
            'member_state' => ''
        ], $filters);

        if ($filters['is_staff'] === true) {
            $this->staffOnly();
        }

        if ($filters['member_state'] !== '') {
            switch ($filters['member_state']) {
                case App::MEMBER_STATE_VALID:
                    $this->validOnly();

                    break;
                case App::MEMBER_STATE_BANNED:
                    $this->banned();

                    break;
                case App::MEMBER_STATE_INVITED:
                    $this->invited();

                    break;
                default:
                    $this->memberState($filters['member_state']);
            }
        }

        if ($filters['order'] === 'joined_date') {
            $this->order('joined_date', isset($filters['direction']) ? $filters['direction'] : 'asc');
        } elseif ($filters['order'] === 'username') {
            $this->order('username', isset($filters['direction']) ? $filters['direction'] : 'asc');
        } else {
            // TODO: Support more sort ordering
        }

        if ($filters['user_id'] > 0) {
            $this->where('user_id', $filters['user_id']);
        }

        return $this;
    }

    /**
     * @param \XF\Entity\User $user
     * @param \Truonglv\Groups\Entity\Group $group
     * @return $this
     */
    public function whereKeys(\XF\Entity\User $user, \Truonglv\Groups\Entity\Group $group)
    {
        $this->where('user_id', $user->user_id)
            ->inGroup($group);

        return $this;
    }

    /**
     * @param \Truonglv\Groups\Entity\Group $group
     * @return $this
     */
    public function inGroup(\Truonglv\Groups\Entity\Group $group)
    {
        $this->where('group_id', $group->group_id);

        return $this;
    }

    /**
     * @param string $state
     * @return $this
     */
    public function memberState(string $state)
    {
        $this->where('member_state', $state);

        return $this;
    }

    /**
     * @return $this
     */
    public function validOnly()
    {
        $this->memberState(App::MEMBER_STATE_VALID);

        return $this;
    }

    public function activeOnly(): self
    {
        $this->with('GroupView');

        $activeLimit = App::getOption('watchAlertActiveOnly');
        if ($activeLimit['enabled'] == 1) {
            $this->where('GroupView.view_date', '>=', XF::$time - $activeLimit['days'] * 86400);
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function staffOnly()
    {
        $memberRoleIds = App::memberRoleRepo()->getStaffRoleIds();
        if (count($memberRoleIds) === 0) {
            $this->whereImpossible();
        } else {
            $this->where('member_role_id', $memberRoleIds);
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function memberOnly()
    {
        $memberRoleIds = App::memberRoleRepo()->getNonStaffRoleIds();
        $this->where('member_role_id', $memberRoleIds);

        return $this;
    }

    /**
     * @return $this
     */
    public function alertable()
    {
        $this->where('alert', '!=', App::MEMBER_ALERT_OPT_OFF);
        $this->activeOnly();

        return $this;
    }

    /**
     * @return Member
     */
    public function invited()
    {
        return $this->memberState(App::MEMBER_STATE_INVITED);
    }

    /**
     * @param int $timePeriod
     * @return $this
     */
    public function banned($timePeriod = 0)
    {
        $this->memberState(App::MEMBER_STATE_BANNED)
            ->where('ban_end_date', '>=', $timePeriod);

        return $this;
    }

    /**
     * @return $this
     */
    public function random()
    {
        $this->order('RAND()');

        return $this;
    }

    /**
     * @return $this
     */
    public function useDefaultOrder()
    {
        $this->order('joined_date', 'DESC');

        return $this;
    }
}
