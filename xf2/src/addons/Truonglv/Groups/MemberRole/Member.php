<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\MemberRole;

use XF;
use Truonglv\Groups\App;

class Member extends AbstractMemberRole
{
    public function getRoleGroupId()
    {
        return App::MEMBER_ROLE_PERM_KEY_MEMBER;
    }

    public function getRoleGroupTitle()
    {
        return XF::phrase('tlg_manage_members');
    }

    protected function setupDefaults()
    {
        $this->addRole('promote', XF::phrase('tlg_promote_members'));
        $this->addRole('remove', XF::phrase('tlg_remove_members'));
        $this->addRole('ban', XF::phrase('tlg_ban_members'));
        $this->addRole('approve', XF::phrase('tlg_approve_members'));
        $this->addRole('invite', XF::phrase('tlg_invite_people'));
    }
}
