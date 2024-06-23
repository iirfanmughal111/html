<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\MemberRole;

use XF;
use Truonglv\Groups\App;

class Event extends AbstractMemberRole
{
    public function getRoleGroupId()
    {
        return App::MEMBER_ROLE_PERM_KEY_EVENT;
    }

    public function getRoleGroupTitle()
    {
        return XF::phrase('tlg_manage_events');
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        return App::isEnabledEvents();
    }

    protected function setupDefaults()
    {
        $this->addRole('add', XF::phrase('tlg_add_event'));
        $this->addRole('editOwn', XF::phrase('tlg_edit_own_event'));
        $this->addRole('editAny', XF::phrase('tlg_edit_any_event'));
        $this->addRole('deleteOwn', XF::phrase('tlg_delete_own_event'));
        $this->addRole('deleteAny', XF::phrase('tlg_delete_any_event'));
        $this->addRole('comment', XF::phrase('tlg_comment_in_events'));
    }
}
