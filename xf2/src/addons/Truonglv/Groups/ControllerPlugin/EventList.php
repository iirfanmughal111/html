<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\ControllerPlugin;

use Closure;
use Truonglv\Groups\App;
use function call_user_func;
use Truonglv\Groups\Entity\Group;

class EventList extends AbstractList
{
    /**
     * @param Group|null $group
     * @param Closure|null $prepareFinder
     * @return array
     */
    public function getEventListData(Group $group = null, Closure $prepareFinder = null)
    {
        $page = $this->filterPage();
        $perPage = App::getOption('eventsPerPage');

        $finder = App::eventFinder();
        $finder->with('full');
        $finder->order('begin_date');

        if ($group !== null) {
            $finder->inGroup($group);
        } else {
            $finder->with('Group', true);
        }

        if ($prepareFinder !== null) {
            call_user_func($prepareFinder, $finder);
        }

        $events = $finder
            ->limitByPage($page, $perPage)
            ->fetch()
            ->filterViewable();
        $total = $finder->total();

        return [
            'page' => $page,
            'perPage' => $perPage,
            'events' => $events,
            'group' => $group,
            'total' => $total
        ];
    }
}
