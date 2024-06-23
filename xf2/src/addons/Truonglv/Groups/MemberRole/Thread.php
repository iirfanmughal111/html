<?php

namespace Truonglv\Groups\MemberRole;

use XF;
use Truonglv\Groups\App;

class Thread extends AbstractMemberRole
{
    /**
     * @return string
     */
    public function getRoleGroupId()
    {
        return App::MEMBER_ROLE_PERM_KEY_THREAD;
    }

    /**
     * @return string
     */
    public function getRoleGroupTitle()
    {
        return XF::phrase('tlg_manage_threads');
    }

    public function isEnabled()
    {
        return App::isEnabledForums();
    }

    /**
     * @return void
     */
    protected function setupDefaults()
    {
        $this->addRole('editThreadAny', XF::phrase('tlg_edit_threads_any'));
        $this->addRole('deleteThreadAny', XF::phrase('tlg_delete_threads_any'));
        $this->addRole('stickUnstick', XF::phrase('tlg_stick_unstick_threads'));
        $this->addRole('lockUnlock', XF::phrase('tlg_lock_unlock_threads'));

        $this->addRole('viewModerated', XF::phrase('tlg_view_moderated_posts'));
        $this->addRole('viewDeleted', XF::phrase('tlg_view_deleted_posts'));
        $this->addRole('approveUnapprove', XF::phrase('tlg_approve_unapprove_posts'));
        $this->addRole('undelete', XF::phrase('tlg_undelete_thread_posts'));

        $this->addRole('editPostAny', XF::phrase('tlg_edit_posts_any'));
        $this->addRole('deletePostAny', XF::phrase('tlg_delete_posts_any'));
        $this->addRole('viewEditPostHistory', XF::phrase('tlg_view_edit_post_history'));

        $this->addRole('inlineMod', XF::phrase('tlg_can_use_inline_moderation'));

        $this->addRole('move', XF::phrase('move_threads'));
    }
}
