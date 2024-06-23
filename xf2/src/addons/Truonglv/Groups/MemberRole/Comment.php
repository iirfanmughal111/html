<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\MemberRole;

use XF;
use Truonglv\Groups\App;

class Comment extends AbstractMemberRole
{
    /**
     * @var array
     */
    protected $defaultPermissions = [
        'post' => true,
    ];

    public function getRoleGroupId()
    {
        return App::MEMBER_ROLE_PERM_KEY_COMMENT;
    }

    public function getRoleGroupTitle()
    {
        return XF::phrase('tlg_manage_comments');
    }

    protected function setupDefaults()
    {
        $this->addRole('post', XF::phraseDeferred('tlg_post_to_newsfeed'));
        $this->addRole('stickUnstickPost', XF::phrase('tlg_stick_unstick_posts'));
        $this->addRole('editOwn', XF::phrase('tlg_edit_own_comment'));
        $this->addRole('editAny', XF::phrase('tlg_edit_any_comment'));
        $this->addRole('deleteOwn', XF::phrase('tlg_delete_own_comment'));
        $this->addRole('deleteAny', XF::phrase('tlg_delete_any_comment'));
        $this->addRole('uploadAttachment', XF::phrase('tlg_upload_attachments_to_comments'));
    }
}
