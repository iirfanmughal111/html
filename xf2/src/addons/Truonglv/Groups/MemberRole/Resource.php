<?php

namespace Truonglv\Groups\MemberRole;

use XF;
use Truonglv\Groups\App;

class Resource extends AbstractMemberRole
{
    public function getRoleGroupId()
    {
        return App::MEMBER_ROLE_PERM_KEY_RESOURCE;
    }

    public function getRoleGroupTitle()
    {
        return XF::phrase('tlg_resources');
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        return App::isEnabledResources();
    }

    /**
     * @return void
     */
    protected function setupDefaults()
    {
        parent::setupDefaults();

        $this->addRole('add', XF::phrase('tlg_add_resources'));
        $this->addRole('editResourceAny', XF::phrase('tlg_edit_resources_any'));
        $this->addRole('deleteResourceAny', XF::phrase('tlg_delete_resources_any'));
    }
}
