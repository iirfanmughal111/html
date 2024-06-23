<?php

namespace Truonglv\Groups\XF\Entity;

use Truonglv\Groups\App;

class Post extends XFCP_Post
{
    /**
     * @param mixed $error
     * @return bool
     */
    public function canView(& $error = null)
    {
        if ($this->Thread !== null) {
            if ($this->message_state === 'moderated') {
                if (App::hasThreadPostPermission('viewModerated', $this->Thread)) {
                    return true;
                }
            } elseif ($this->message_state === 'deleted') {
                if (App::hasThreadPostPermission('viewDeleted', $this->Thread)) {
                    return true;
                }
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
        if ($this->Thread !== null) {
            if (App::hasThreadPostPermission('editPostAny', $this->Thread)) {
                if (!$this->Thread->discussion_open && !$this->Thread->canLockUnlock()) {
                    return false;
                }

                return true;
            }
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
        if ($type === 'soft' && $this->Thread !== null) {
            if (App::hasThreadPostPermission('deletePostAny', $this->Thread)) {
                return true;
            }
        }

        return parent::canDelete($type, $error);
    }

    /**
     * @param mixed $error
     * @return bool
     */
    public function canUndelete(& $error = null)
    {
        if ($this->Thread !== null && App::hasThreadPostPermission('undelete', $this->Thread)) {
            return true;
        }

        return parent::canUndelete($error);
    }

    /**
     * @param mixed $error
     * @return bool
     */
    public function canApproveUnapprove(& $error = null)
    {
        if ($this->Thread !== null && App::hasThreadPostPermission('approveUnapprove', $this->Thread)) {
            return true;
        }

        return parent::canApproveUnapprove($error);
    }

    /**
     * @param mixed $error
     * @return bool
     */
    public function canViewHistory(& $error = null)
    {
        if ($this->Thread !== null && App::hasThreadPostPermission('viewEditPostHistory', $this->Thread)) {
            return true;
        }

        return parent::canViewHistory($error);
    }

    protected function _postSave()
    {
        parent::_postSave();

        if (App::isEnabledForums() && $this->isInsert()) {
            $groupId = App::getGroupIdFromEntity($this->Thread);
            if ($groupId > 0) {
                App::groupRepo()->logGroupActivity($groupId);
            }
        }
    }
}
