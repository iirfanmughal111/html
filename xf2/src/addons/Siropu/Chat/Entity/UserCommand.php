<?php

namespace Siropu\Chat\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

class UserCommand extends Entity
{
     public static function getStructure(Structure $structure)
	{
          $structure->table      = 'xf_siropu_chat_user_command';
          $structure->shortName  = 'Chat:UserCommand';
          $structure->primaryKey = 'command_id';

          $structure->columns = [
               'command_id'      => ['type' => self::UINT, 'autoIncrement' => true],
               'command_user_id' => ['type' => self::UINT, 'default' => \XF::visitor()->user_id],
               'command_name'    => ['type' => self::STR, 'maxLength' => 50, 'required' => true],
               'command_value'   => ['type' => self::STR, 'required' => true]
          ];

          $structure->getters   = [];
          $structure->relations = [];

          return $structure;
     }
}
