<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Repository;

use Truonglv\Groups\App;
use XF\Mvc\Entity\Repository;

class Member extends Repository
{
    /**
     * @return array
     */
    public function getAllowedMemberStates()
    {
        return App::getAllowedMemberStates();
    }

    /**
     * @return array
     */
    public function getValidMemberStates()
    {
        return [App::MEMBER_STATE_VALID];
    }

    /**
     * @param \Truonglv\Groups\Entity\Group $group
     * @param bool $validOnly
     * @return \Truonglv\Groups\Finder\Member
     */
    public function findMembersForList(\Truonglv\Groups\Entity\Group $group, $validOnly = true)
    {
        $memberFinder = App::memberFinder();
        $memberFinder->with('User');
        $memberFinder->inGroup($group);

        if ($validOnly) {
            $memberFinder->validOnly();
        }

        return $memberFinder;
    }

    /**
     * @param \Truonglv\Groups\Entity\Group $group
     * @return \XF\Mvc\Entity\AbstractCollection
     */
    public function getManagers(\Truonglv\Groups\Entity\Group $group)
    {
        return $this->findMembersForList($group)
            ->where('member_role_id', [
                App::MEMBER_ROLE_ID_ADMIN,
                App::MEMBER_ROLE_ID_MODERATOR
            ])->fetch();
    }

    /**
     * @param string $data
     * @param string $alert
     * @return bool
     */
    public function isEnableAlertFor($data, $alert)
    {
        if ($data === App::MEMBER_ALERT_OPT_ALL) {
            return true;
        }

        return $data === $alert;
    }

    /**
     * @throws \XF\PrintableException
     * @return void
     */
    public function pruneExpiredBanningRecords()
    {
        $members = App::memberFinder()
            ->with('Group')
            ->banned(1)
            ->fetch();

        /** @var \Truonglv\Groups\Entity\Member $member */
        foreach ($members as $member) {
            $member->ban_end_date = 0;
            $member->member_state = App::MEMBER_STATE_VALID;
            $member->save();
        }
    }
}
