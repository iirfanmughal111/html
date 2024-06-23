<?php

namespace Siropu\Chat\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

class BotResponse extends Entity
{
     public static function getStructure(Structure $structure)
	{
          $structure->table      = 'xf_siropu_chat_bot_response';
          $structure->shortName  = 'Chat:BotResponse';
          $structure->primaryKey = 'response_id';

          $structure->columns = [
               'response_id'          => ['type' => self::UINT, 'autoIncrement' => true],
               'response_bot_name'    => ['type' => self::STR, 'maxLength' => 50, 'default' => ''],
               'response_keyword'     => ['type' => self::STR, 'required' => true],
               'response_message'     => ['type' => self::STR, 'required' => true],
               'response_rooms'       => ['type' => self::JSON_ARRAY, 'default' => []],
               'response_user_groups' => ['type' => self::JSON_ARRAY, 'default' => []],
               'response_settings'    => ['type' => self::JSON_ARRAY, 'default' => []],
               'response_last'        => ['type' => self::JSON_ARRAY, 'default' => []],
               'response_enabled'     => ['type' => self::UINT, 'default' => 1]
          ];

          $structure->getters   = [
               'keyword_list' => true,
               'message_list' => true
          ];

		$structure->relations = [];

          return $structure;
     }
     public function canUse($roomId = 0)
     {
          $visitor = \XF::visitor();

          if (!empty($this->response_user_groups) && !$visitor->isMemberOf($this->response_user_groups))
          {
               return false;
          }

          if (!empty($this->response_rooms) && !in_array($roomId, $this->response_rooms))
          {
               return false;
          }

          if (!empty($this->response_last[$roomId])
               && $this->response_last[$roomId] >= \XF::$time - $this->response_settings['interval'] * 60)
          {
               return false;
          }

          return true;
     }
     public function isMatch($input)
     {
          $found = false;

          foreach ($this->keyword_list as $keyword)
          {
               if (stripos($input, $keyword) !== false
                    && !($this->response_settings['exact_match'] && utf8_strtolower($keyword) != utf8_strtolower($input)))
               {
                    $found = true;
               }
          }

          return $found;
     }
     public function isMatchFull($input)
     {
          $found = false;

          foreach ($this->keyword_list as $keyword)
          {
               if (utf8_strtolower($keyword) == utf8_strtolower($input))
               {
                    $found = true;
               }
          }

          return $found;
     }
     public function isMatchExact($input)
     {
          $found = false;

          foreach ($this->keyword_list as $keyword)
          {
               if ($this->response_settings['exact_match'] && utf8_strtolower($keyword) == utf8_strtolower($input))
               {
                    $found = true;
               }
          }

          return $found;
     }
     public function getKeywordList()
     {
          return array_filter(array_map('trim', explode("\n", $this->response_keyword)));
     }
     public function getMessageList()
     {
          return array_filter(array_map('trim', explode("\n", $this->response_message)));
     }
     public function updateLastResponse($roomId)
     {
          $this->response_last = array_replace($this->response_last, [$roomId => \XF::$time]);
     }
     protected function _postSave()
	{
          if ($this->isInsert() || $this->isChanged('response_enabled'))
          {
               $this->getBotResponseRepo()->rebuildBotResponseCache();
          }
	}
	protected function _postDelete()
	{
          $this->getBotResponseRepo()->rebuildBotResponseCache();
	}
     protected function getBotResponseRepo()
     {
          return $this->app()->repository('Siropu\Chat:BotResponse');
     }
     protected function verifyResponseSettings(&$settings)
     {
          $settings = array_replace(['interval' => 0, 'exact_match' => 0, 'mention' => 0], $settings);
          return true;
     }
}
