<?php
namespace XenBulletins\Tournament\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

class Register extends Entity {
    
    
      public static function getStructure(Structure $structure) {
        $structure->table = 'xf_tournament_register';
        $structure->shortName = 'XenBulletins\Tournament:Register';
        $structure->contentType = 'xf_tournament_register';
        $structure->primaryKey = 'reg_id';
        
        $structure->columns = [
            
            'reg_id' => ['type' => self::UINT, 'autoIncrement' => true],
            'user_id' => ['type' => self::INT, 'maxLength' => 10, 'default' => '0'],
            'tourn_id' => ['type' => self::INT, 'maxLength' => 10, 'default' => '0'],
            'current_time' => ['type' => self::INT, 'maxLength' => 10, 'default' => '0'],
        ];

         $structure->relations = [
            'User' => [
                'entity' => 'XF:User',
                'type' => self::TO_ONE,
                'conditions' => 'user_id',
                
            ],
           
            'Tournament' => [
                'entity' => 'XenBulletins\Tournament:Tournament',
                'type' => self::TO_ONE,
                'conditions' => 'tourn_id',
                
            ]
          
             ];
        

        return $structure;
    }
}

