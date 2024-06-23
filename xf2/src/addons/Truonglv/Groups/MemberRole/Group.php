<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\MemberRole;

use XF;
use Truonglv\Groups\App;

class Group extends AbstractMemberRole
{
    public function getRoleGroupId()
    {
        return App::MEMBER_ROLE_PERM_KEY_GROUP;
    }

    public function getRoleGroupTitle()
    {
        return XF::phrase('tlg_manage_group');
    }

    protected function setupDefaults()
    {
        $this->addRole('edit', XF::phrase('tlg_edit_group'));
        $this->addRole('delete', XF::phrase('tlg_delete_group'));
        $this->addRole('privacy', XF::phrase('tlg_update_privacy'));
        $this->addRole('avatar', XF::phrase('tlg_manage_avatar'));
        $this->addRole('cover', XF::phrase('tlg_manager_cover'));
    }
}
