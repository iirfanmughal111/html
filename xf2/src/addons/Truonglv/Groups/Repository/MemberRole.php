<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Repository;

use function array_diff;
use Truonglv\Groups\App;
use function json_decode;
use function json_encode;
use XF\Mvc\Entity\Repository;

class MemberRole extends Repository
{
    /**
     * @return \Truonglv\Groups\MemberRole\AbstractMemberRole[]
     */
    public function getMemberRoleHandlers()
    {
        $classes = [
            'Truonglv\Groups:Group',
            'Truonglv\Groups:Member',
            'Truonglv\Groups:Event',
            'Truonglv\Groups:Comment',
            'Truonglv\Groups:Forum',
            'Truonglv\Groups:Thread',
            'Truonglv\Groups:Resource',
            'Truonglv\Groups:Media',
        ];

        $this->app()->fire('tlg_member_role_handler_class', [&$classes]);
        $handlers = [];

        foreach ($classes as $shortName) {
            /** @var \Truonglv\Groups\MemberRole\AbstractMemberRole $handler */
            $handler = $this->app()->container()->create(App::CONTAINER_KEY_MEMBER_ROLE, $shortName);
            $handlers[$handler->getRoleGroupId()] = $handler;
        }

        return $handlers;
    }

    /**
     * @return \XF\Mvc\Entity\AbstractCollection
     */
    public function rebuildCache()
    {
        $memberRoles = $this->finder('Truonglv\Groups:MemberRole')
                ->order('display_order')
                ->fetch();

        $cache = [];
        /** @var \Truonglv\Groups\Entity\MemberRole $memberRole */
        foreach ($memberRoles as $memberRole) {
            $cache[$memberRole->member_role_id] = $this->getDataForCache($memberRole);
        }

        // save to simple cache
        $this->app()
            ->simpleCache()
            ->setValue(App::ADDON_ID, 'memberRoles', json_encode($cache));

        return $memberRoles;
    }

    /**
     * @return \XF\Mvc\Entity\AbstractCollection
     */
    public function getAllMemberRoles()
    {
        $cache = $this->app()
            ->simpleCache()
            ->getValue(App::ADDON_ID, 'memberRoles');

        if ($cache === null) {
            return $this->rebuildCache();
        }

        $cache = json_decode($cache, true);
        $memberRoles = $this->em->getEmptyCollection();

        foreach ($cache as $item) {
            $memberRoles[$item['member_role_id']] = $this->em->instantiateEntity(
                'Truonglv\Groups:MemberRole',
                $item
            );
        }

        return $memberRoles;
    }

    /**
     * @return array
     */
    public function getStaffRoleIds()
    {
        $roleIds = [];

        foreach ($this->getAllMemberRoles() as $memberRole) {
            if ($memberRole->is_staff) {
                $roleIds[] = $memberRole->member_role_id;
            }
        }

        return $roleIds;
    }

    /**
     * @return array
     */
    public function getNonStaffRoleIds()
    {
        return array_diff($this->getAllMemberRoles()->keys(), $this->getStaffRoleIds());
    }

    /**
     * @param string $group
     * @param string $name
     * @return array
     */
    public function getMemberRoleIdsWithPermission($group, $name)
    {
        $memberRoleIds = [];
        foreach ($this->getAllMemberRoles() as $memberRole) {
            if ($memberRole->hasRole($group, $name)) {
                $memberRoleIds[] = $memberRole->member_role_id;
            }
        }

        return $memberRoleIds;
    }

    /**
     * @return \Truonglv\Groups\Entity\MemberRole
     */
    public function getCreatorRole()
    {
        $memberRoles = $this->getAllMemberRoles();
        /** @var \Truonglv\Groups\Entity\MemberRole $memberRole */
        $memberRole = $memberRoles[App::MEMBER_ROLE_ID_ADMIN];

        return $memberRole;
    }

    /**
     * @param \Truonglv\Groups\Entity\MemberRole $memberRole
     * @return array
     */
    protected function getDataForCache(\Truonglv\Groups\Entity\MemberRole $memberRole)
    {
        return [
            'member_role_id' => $memberRole->member_role_id,
            'role_permissions' => json_encode($memberRole->role_permissions),
            'display_order' => $memberRole->display_order,
            'user_group_ids' => json_encode($memberRole->user_group_ids)
        ];
    }
}
