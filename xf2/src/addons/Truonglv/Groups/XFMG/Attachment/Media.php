<?php

namespace Truonglv\Groups\XFMG\Attachment;

use XF;
use Truonglv\Groups\App;
use Truonglv\Groups\Entity\Group;

class Media extends XFCP_Media
{
    public function canManageAttachments(array $context, & $error = null)
    {
        if (isset($context['tlg_group_id']) && App::isEnabledXenMediaAddOn()) {
            /** @var Group|null $group */
            $group = XF::em()->find('Truonglv\Groups:Group', $context['tlg_group_id'], 'full');
            App::$createAlbumInGroup = $group;
        }

        return parent::canManageAttachments($context, $error);
    }
}
