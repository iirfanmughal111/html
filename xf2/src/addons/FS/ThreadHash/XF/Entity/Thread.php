<?php

namespace FS\ThreadHash\XF\Entity;

use XF\Mvc\Entity\Structure;

class Thread extends XFCP_Thread
{

    public static function getStructure(Structure $structure)
    {
        $structure = parent::getStructure($structure);
        
        $structure->columns['thread_hash'] =  ['type' => self::STR, 'maxLength' => 255, 'default' => NULL];

        return $structure;
    }
    protected function _postSave()
	{
        $parent = parent::_postSave();
        $HashGenerator = \XF::app()->service('FS\ThreadHash:HashGenerator');
        $this->fastUpdate('thread_hash',$HashGenerator->getHash($this->thread_id));
    }
}