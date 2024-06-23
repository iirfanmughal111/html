<?php

namespace Siropu\Chat\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

class Command extends Entity
{
     public static function getStructure(Structure $structure)
	{
          $structure->table      = 'xf_siropu_chat_command';
          $structure->shortName  = 'Chat:Command';
          $structure->primaryKey = 'command_name';

          $structure->columns = [
               'command_name'             => ['type' => self::STR, 'maxLength' => 25, 'unique' => true, 'required' => 'siropu_chat_please_enter_valid_command_name'],
               'command_name_default'     => ['type' => self::STR, 'maxLength' => 25, 'default' => ''],
               'command_description'      => ['type' => self::STR, 'required' => 'siropu_chat_please_enter_valid_command_description'],
               'command_callback_class'   => ['type' => self::STR, 'maxLength' => 100, 'required' => 'please_enter_valid_callback_class'],
               'command_callback_method'  => ['type' => self::STR, 'maxLength' => 75, 'required' => 'please_enter_valid_callback_method'],
               'command_rooms'            => ['type' => self::JSON_ARRAY, 'default' => []],
               'command_user_groups'      => ['type' => self::JSON_ARRAY, 'default' => []],
               'command_options_template' => ['type' => self::STR, 'maxLength' => 50, 'default' => ''],
               'command_options'          => ['type' => self::JSON_ARRAY, 'default' => []],
               'command_enabled'          => ['type' => self::UINT, 'default' => 1]
          ];

          $structure->getters   = [
               'description' => true
          ];

          $structure->relations = [];

          return $structure;
     }
     public function hasPermission()
     {
          $visitor = \XF::visitor();

          if ($this->command_user_groups && !$visitor->isMemberOf($this->command_user_groups))
          {
               return false;
          }

          return true;
     }
     public function canUse($roomId, &$error = null)
     {
          $visitor = \XF::visitor();

          if ($this->command_user_groups && !$visitor->isMemberOf($this->command_user_groups))
          {
               $error = \XF::phraseDeferred('siropu_chat_no_permission_to_use_command');
               return false;
          }

          if ($this->command_rooms && !in_array($roomId, $this->command_rooms))
          {
               $error = \XF::phraseDeferred('siropu_chat_command_not_available_for_room');
               return false;
          }

          return true;
     }
     public function getOption($option, $fallback = '')
     {
          return @$this->command_options[$option] ?: $fallback;
     }
     public function getDescription()
     {
          if (preg_match('/^phrase:([\w]+)/', $this->command_description, $matches))
          {
               return \XF::phrase($matches[1]);
          }

          return $this->command_description;
     }
     protected function _preSave()
	{
		if ($this->command_callback_class || $this->command_callback_method)
		{
			if (!\XF\Util\Php::validateCallbackPhrased($this->command_callback_class, $this->command_callback_method, $error))
			{
				$this->error($error, 'callback_method');
			}
		}
	}
     protected function _postSave()
	{
          \XF::repository('Siropu\Chat:Command')->rebuildCommandCache();
	}
	protected function _postDelete()
	{
          \XF::repository('Siropu\Chat:Command')->rebuildCommandCache();
	}
}
