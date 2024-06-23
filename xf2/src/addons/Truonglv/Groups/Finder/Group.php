<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Finder;

use XF;
use Truonglv\Groups\App;
use XF\Mvc\Entity\Finder;
use function array_search;
use Truonglv\Groups\Entity\Category;

class Group extends Finder
{
    /**
     * @param bool $allowOwnPending
     * @return $this
     */
    public function applyGlobalVisibilityChecks($allowOwnPending = false)
    {
        $visitor = XF::visitor();
        $conditions = [];

        $viewableStates = ['visible'];

        if (App::hasPermission('viewDeleted')) {
            $viewableStates[] = 'deleted';

            $this->with('DeletionLog');
        }

        if (App::hasPermission('viewModerated')) {
            $viewableStates[] = 'moderated';
        } elseif ($visitor->user_id > 0 && $allowOwnPending) {
            $conditions[] = [
                'group_state' => 'moderated',
                'owner_user_id' => $visitor->user_id
            ];
        }

        $conditions[] = ['group_state', $viewableStates];

        $this->whereOr($conditions);

        return $this;
    }

    /**
     * @return $this
     */
    public function applyGlobalPrivacyChecks()
    {
        $privacyList = App::groupRepo()->getAllowedPrivacy();
        $secretIndex = array_search(App::PRIVACY_SECRET, $privacyList, true);

        if (App::hasPermission('bypassViewPrivacy')) {
            $this->where('privacy', $privacyList);
        } else {
            if ($secretIndex !== false) {
                unset($privacyList[$secretIndex]);
            }

            $visitor = XF::visitor();
            // https://xenforo.com/community/threads/advanced-where-relation.137026/post-1190423
            $this->whereOr(
                ['privacy', $privacyList],
                [
                    'Members|' . $visitor->user_id . '.member_state',
                    App::memberRepo()->getValidMemberStates()
                ]
            );
        }

        return $this;
    }

    /**
     * @param Category $category
     * @return $this
     */
    public function inCategory(Category $category)
    {
        $this->where('category_id', $category->category_id);

        return $this;
    }

    /**
     * @return $this
     */
    public function useDefaultOrder()
    {
        $this->setDefaultOrder('last_activity', 'desc');

        return $this;
    }
}
