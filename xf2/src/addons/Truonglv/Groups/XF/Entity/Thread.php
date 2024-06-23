<?php

namespace Truonglv\Groups\XF\Entity;

use Truonglv\Groups\App;

class Thread extends XFCP_Thread
{
    /**
     * @param mixed $error
     * @return bool
     */
    public function canView(& $error = null)
    {
        if ($this->discussion_state === 'moderated') {
            if (App::hasThreadPostPermission('viewModerated', $this)) {
                return true;
            }
        } elseif ($this->discussion_state === 'deleted') {
            if (App::hasThreadPostPermission('viewDeleted', $this)) {
                return true;
            }
        }

        return parent::canView($error);
    }

    /**
     * @param mixed $error
     * @return bool
     */
    public function canEdit(& $error = null)
    {
        if (App::hasThreadPostPermission('editThreadAny', $this)) {
            if (!$this->discussion_open && !$this->canLockUnlock()) {
                return false;
            }

            return true;
        }

        return parent::canEdit($error);
    }

    /**
     * @param mixed $type
     * @param mixed $error
     * @return bool
     */
    public function canDelete($type = 'soft', & $error = null)
    {
        if ($type === 'soft' && App::hasThreadPostPermission('deleteThreadAny', $this)) {
            return true;
        }

        return parent::canDelete($type, $error);
    }

    /**
     * @param mixed $error
     * @return bool
     */
    public function canStickUnstick(& $error = null)
    {
        if (App::hasThreadPostPermission('stickUnstick', $this)) {
            return true;
        }

        return parent::canStickUnstick($error);
    }

    /**
     * @return bool
     */
    public function canViewModeratedPosts()
    {
        if (App::hasThreadPostPermission('viewModerated', $this)) {
            return true;
        }

        return parent::canViewModeratedPosts();
    }

    /**
     * @return bool
     */
    public function canViewDeletedPosts()
    {
        if (App::hasThreadPostPermission('viewDeleted', $this)) {
            return true;
        }

        return parent::canViewDeletedPosts();
    }

    /**
     * @param mixed $error
     * @return bool
     */
    public function canApproveUnapprove(& $error = null)
    {
        if (App::hasThreadPostPermission('approveUnapprove', $this)) {
            return true;
        }

        return parent::canApproveUnapprove($error);
    }

    /**
     * @param mixed $error
     * @return bool
     */
    public function canLockUnlock(& $error = null)
    {
        if (App::hasThreadPostPermission('lockUnlock', $this)) {
            return true;
        }

        return parent::canLockUnlock($error);
    }

    /**
     * @param mixed $error
     * @return bool
     */
    public function canUndelete(& $error = null)
    {
        if (App::hasThreadPostPermission('undelete', $this)) {
            return true;
        }

        return parent::canUndelete($error);
    }

    /**
     * @param mixed $error
     * @return bool
     */
    public function canUseInlineModeration(& $error = null)
    {
        if (App::hasThreadPostPermission('inlineMod', $this)) {
            return true;
        }

        return parent::canUseInlineModeration($error);
    }

    /**
     * @param mixed $error
     * @return bool
     */
    public function canMove(& $error = null)
    {
        if (App::hasThreadPostPermission('move', $this)) {
            return true;
        }

        return parent::canMove($error);
    }
}
