<?php

namespace FS\EncryptIp\XF\Entity;

use XF\Mvc\Entity\Structure;

class Ip extends XFCP_Ip
{
    protected function verifyIp(&$ip)
	{
        return true;
    }
    public static function getStructure(Structure $structure)
    {
        $structure = parent::getStructure($structure);
   
        $structure->columns['ip'] =  ['type' => self::STR ,'required' => true];
        $structure->columns['ip_backup'] =  ['type' => self::STR ,'required' => true];

      
        return $structure;
    }


}