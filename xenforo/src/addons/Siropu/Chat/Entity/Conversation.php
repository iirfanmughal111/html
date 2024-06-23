<?php

namespace Siropu\Chat\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;
use XF\Util\File;

class Conversation extends Entity
{
     public static function getStructure(Structure $structure)
	{
          $structure->table      = 'xf_siropu_chat_conversation';
          $structure->shortName  = 'Chat:Conversation';
          $structure->primaryKey = 'conversation_id';

          $structure->columns = [
               'conversation_id' => ['type' => self::UINT, 'autoIncrement' => true],
               'user_1'          => ['type' => self::UINT, 'required' => true],
               'user_2'          => ['type' => self::UINT, 'required' => true],
               'start_date'      => ['type' => self::UINT, 'default' => \XF::$time],
               'user_left'       => ['type' => self::UINT, 'default' => 0]
          ];

          $structure->getters   = [
               'Contact'       => false,
               'last_activity' => false
          ];

		$structure->relations = [
               'User1' => [
				'entity' => 'XF:User',
				'type' => self::TO_ONE,
				'conditions' => [
					['user_id', '=', '$user_1']
				],
                    'with' => 'Activity'
			],
               'User2' => [
				'entity' => 'XF:User',
				'type' => self::TO_ONE,
				'conditions' => [
					['user_id', '=', '$user_2']
				],
                    'with' => 'Activity'
			]
          ];

          $structure->defaultWith = ['User1', 'User2'];

          return $structure;
     }
     public function isActive()
     {
          $visitor = \XF::visitor();
          return $this->user_left != $visitor->user_id;
     }
     public function isContact()
     {
          $visitor = \XF::visitor();
          return isset($visitor->siropu_chat_conversations[$this->conversation_id]);
     }
     public function isOnline()
     {
          if (!$this->Contact)
          {
               return false;
          }

          return $this->Contact->isOnline();
     }
     public function getLastActivity()
     {
          if (!$this->Contact)
          {
               return false;
          }

          return $this->Contact->Activity->view_date;
     }
     public function getContact()
     {
          $visitor = \XF::visitor();

          if ($this->user_1 == $visitor->user_id)
          {
               return $this->User2;
          }

          if ($this->user_2 == $visitor->user_id)
          {
               return $this->User1;
          }
     }
     public function writeWhosTypingJsonFile(array $whosTyping = [])
     {
          try
          {
               File::writeToAbstractedPath("data://siropu/chat/conversation/{$this->conversation_id}.json", json_encode($whosTyping));
          }
          catch (\Exception $e)
          {
               \XF::logException($e, false, 'Error creating Chat conversation JSON file');
          }
     }
     protected function _postSave()
	{
          if ($this->isInsert())
          {
               $this->writeWhosTypingJsonFile();
          }
	}
	protected function _postDelete()
	{
          \XF::repository('Siropu\Chat:Conversation')->deleteConversationMessages($this->conversation_id);

          File::deleteFromAbstractedPath("data://siropu/chat/conversation/{$this->conversation_id}.json");
	}
}
