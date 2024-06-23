<?php

namespace FS\UpdateUrl\XF\Entity;

use XF\Mvc\Entity\Structure;

class Forum extends XFCP_Forum
{

    public static function getStructure(Structure $structure)
    {
        $structure = parent::getStructure($structure);
        
        
        $options = \XF::options();  
        $structure->columns['last_thread_title'] =  ['type' => self::STR, 'maxLength' => $options->fs_updateUrl_title_limit,'default'=>'null' ];

        return $structure;
    }

}