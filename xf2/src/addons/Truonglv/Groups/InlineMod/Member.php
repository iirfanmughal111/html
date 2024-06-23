<?php

namespace Truonglv\Groups\InlineMod;

use XF;
use Truonglv\Groups\App;
use XF\Mvc\Entity\Entity;
use XF\InlineMod\AbstractAction;
use XF\InlineMod\AbstractHandler;
use Truonglv\Groups\Service\Member\Approver;
use Truonglv\Groups\InlineMod\Member\Promote;

class Member extends AbstractHandler
{
    /**
     * @return AbstractAction[]
     */
    public function getPossibleActions()
    {
        $actions = [];
        /** @var \Truonglv\Groups\Entity\Group|null $group */
        $group = $this->app()
            ->em()
            ->find('Truonglv\Groups:Group', $this->app()->request()->filter('group_id', 'uint'));
        if ($group === null || $group->Member === null) {
            return $actions;
        }

        $member = $group->Member;
        if ($member->hasRole(App::MEMBER_ROLE_PERM_KEY_MEMBER, 'approve')) {
            $actions['approve'] = $this->getSimpleActionHandler(
                XF::phrase('tlg_approve_members'),
                'canBeApproved',
                function (Entity $entity) {
                    if (!$entity instanceof \Truonglv\Groups\Entity\Member) {
                        return;
                    }

                    /** @var Approver $approver */
                    $approver = $entity->app()->service('Truonglv\Groups:Member\Approver', $entity);
                    $approver->approve();
                }
            );
        }

        if ($member->hasRole(App::MEMBER_ROLE_PERM_KEY_MEMBER, 'remove')) {
            $actions['remove'] = $this->getSimpleActionHandler(
                XF::phrase('tlg_remove_members'),
                'canBeRemove',
                function (Entity $entity) {
                    if (!$entity instanceof \Truonglv\Groups\Entity\Member) {
                        return;
                    }

                    $entity->delete();
                }
            );
        }

        if ($member->hasRole(App::MEMBER_ROLE_PERM_KEY_MEMBER, 'promote')) {
            /** @var Promote $promote */
            $promote = $this->getActionHandler('Truonglv\Groups:Member\Promote');
            $promote->setGroup($group);

            $actions['promote'] = $promote;
        }

        if ($member->hasRole(App::MEMBER_ROLE_PERM_KEY_MEMBER, 'ban')) {
            $actions['ban'] = $this->getActionHandler('Truonglv\Groups:Member\Ban');
        }

        return $actions;
    }

    /**
     * @return array
     */
    public function getEntityWith()
    {
        return ['Group'];
    }

    /**
     * @return string
     */
    public function getCookieName()
    {
        $groupId = XF::app()->request()->filter('group_id', 'uint');

        return $this->baseCookie . '_' . App::CONTENT_TYPE_MEMBER . $groupId;
    }
}
