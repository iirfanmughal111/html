<?php

namespace FS\RegistrationSteps\XF\Entity;

use XF\Mvc\Entity\Structure;

class User extends XFCP_User
{

    public static function getStructure(Structure $structure)
    {
        $structure = parent::getStructure($structure);
        
        $structure->columns['account_type'] =  ['type' => self::UINT,'default' => 1];
        
         $structure->columns['is_verify'] =  ['type' => self::UINT,'default' => 1];
         
          $structure->columns['activation_id'] =  ['type' => self::STR,'default' => null];
		$structure->columns['is_featured'] =  ['type' => self::UINT,'default' => 0];
        
        return $structure;
    }
    
}