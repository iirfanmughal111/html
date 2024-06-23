<?php

namespace Truonglv\Groups\XF\Entity;

use XF\Mvc\Entity\Structure;

/**
 * @inheritDoc
 * @property bool $tlg_show_badge
 */
class UserOption extends XFCP_UserOption
{
    public static function getStructure(Structure $structure)
    {
        $structure = parent::getStructure($structure);

        $structure->columns['tlg_show_badge'] = ['type' => self::BOOL, 'default' => true];

        return $structure;
    }
}
