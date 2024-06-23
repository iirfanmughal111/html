<?php

namespace Truonglv\Groups\MemberRole;

use XF;
use Truonglv\Groups\App;

class Media extends AbstractMemberRole
{
    /**
     * @return string
     */
    public function getRoleGroupId()
    {
        return App::MEMBER_ROLE_PERM_KEY_MEDIA;
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        return App::isEnabledXenMediaAddOn();
    }

    /**
     * @return void
     */
    protected function setupDefaults()
    {
        parent::setupDefaults();

        $this->addRole('createAlbum', XF::phrase('tlg_create_albums'));
        $this->addRole('editAlbumAny', XF::phrase('tlg_edit_albums_any'));
        $this->addRole('deleteAlbumAny', XF::phrase('tlg_delete_albums_any'));
    }

    public function getRoleGroupTitle()
    {
        return XF::phraseDeferred('tlg_manage_media');
    }
}
