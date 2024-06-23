<?php

namespace Siropu\Chat\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

class BotMessage extends Entity
{
     public static function getStructure(Structure $structure)
	{
          $structure->table      = 'xf_siropu_chat_bot_message';
          $structure->shortName  = 'Chat:BotMessage';
          $structure->primaryKey = 'message_id';

          $structure->columns = [
               'message_id'       => ['type' => self::UINT, 'autoIncrement' => true],
               'message_bot_name' => ['type' => self::STR, 'maxLength' => 50, 'default' => ''],
               'message_title'    => ['type' => self::STR, 'maxLength' => 100, 'required' => true],
               'message_text'     => ['type' => self::STR, 'required' => true],
               'message_rooms'    => ['type' => self::JSON_ARRAY, 'default' => []],
               'message_rules'    => ['type' => self::JSON_ARRAY, 'default' => []],
               'message_enabled'  => ['type' => self::UINT, 'default' => 1]
          ];

          $structure->getters   = [
               'run_time'  => false,
               'next_run'  => false
          ];

		$structure->relations = [];

          return $structure;
     }
     public function getRunTime()
     {
          return $this->app()->service('XF:CronEntry\CalculateNextRun')
               ->calculateNextRunTimeCustom($this->message_rules, $this->getUseTime() - 60);
     }
     public function getNextRun()
     {
          return $this->app()->service('XF:CronEntry\CalculateNextRun')
               ->calculateNextRunTimeCustom($this->message_rules, $this->getUseTime());
     }
     public function getUseTime()
     {
          return !empty($this->message_rules['date']) ? strtotime($this->message_rules['date']) : \XF::$time;
     }
     protected function _postSave()
	{
          if ($this->isInsert() || $this->isChanged('message_enabled'))
          {
               $this->getBotMessageRepo()->rebuildBotMessageCache();
          }
	}
	protected function _postDelete()
	{
          $this->getBotMessageRepo()->rebuildBotMessageCache();
	}
     protected function getBotMessageRepo()
     {
          return $this->app()->repository('Siropu\Chat:BotMessage');
     }
     protected function verifyMessageRules(array &$rules)
	{
		$filterTypes = ['dom', 'dow', 'hours', 'minutes'];

		foreach ($filterTypes AS $type)
		{
			if (!isset($rules[$type]))
			{
				continue;
			}

			$typeRules = $rules[$type];
			if (!is_array($typeRules))
			{
				$typeRules = [];
			}

			$typeRules = array_map('intval', $typeRules);
			$typeRules = array_unique($typeRules);
			sort($typeRules, SORT_NUMERIC);

			$rules[$type] = $typeRules;
		}

		return true;
	}
}
