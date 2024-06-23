<?php

namespace Truonglv\Groups\XF\Entity;

use XF\Mvc\Entity\Structure;

class UserPrivacy extends XFCP_UserPrivacy
{
    public static function getStructure(Structure $structure)
    {
        $structure = parent::getStructure($structure);

        $structure->columns['tlg_allow_view_groups'] = [
            'type' => self::STR,
            'default' => 'everyone',
            'allowedValues' => ['everyone', 'members', 'followed', 'none'],
            'verify' => 'verifyPrivacyChoice'
        ];

        return $structure;
    }
}
