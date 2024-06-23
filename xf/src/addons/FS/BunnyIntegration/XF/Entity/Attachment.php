<?php

namespace FS\BunnyIntegration\XF\Entity;

use XF\Mvc\Entity\Structure;

class Attachment extends XFCP_Attachment
{

    public static function getStructure(Structure $structure)
    {
        $structure = parent::getStructure($structure);

        $structure->columns['bunny_vid_id'] =  ['type' => self::STR, 'default' => null];
        $structure->columns['is_bunny'] =  ['type' => self::UINT, 'default' => 0];

        return $structure;
    }
}
