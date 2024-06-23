<?php

namespace FS\UpdateUrl\XF\Entity;

use XF\Mvc\Entity\Structure;

class Thread extends XFCP_Thread
{

    public static function getStructure(Structure $structure)
    {
        $structure = parent::getStructure($structure);
        
        $structure->columns['url_string'] =  ['type' => self::STR, 'maxLength' => 255, 'default' => 'null'];
        
        $options = \XF::options();  
        $structure->columns['title'] =  ['type' => self::STR, 'maxLength' => $options->fs_updateUrl_title_limit,
                'required' => 'please_enter_valid_title',
                'censor' => true,
                'api' => true
        ];

        return $structure;
    }

}