<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\MemberRole;

use XF;
use Truonglv\Groups\App;

class Forum extends AbstractMemberRole
{
    public function getRoleGroupId()
    {
        return App::MEMBER_ROLE_PERM_KEY_FORUM;
    }

    public function getRoleGroupTitle()
    {
        return XF::phrase('tlg_manage_forums');
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        return App::isEnabledForums();
    }

    protected function setupDefaults()
    {
        $this->addRole('add', XF::phrase('tlg_add_forum'));
        $this->addRole('edit', XF::phrase('tlg_edit_forum'));
        $this->addRole('delete', XF::phrase('tlg_delete_forum'));
    }
}
