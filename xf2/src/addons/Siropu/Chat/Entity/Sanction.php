<?php

namespace Siropu\Chat\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

class Sanction extends Entity
{
     public static function getStructure(Structure $structure)
	{
          $structure->table      = 'xf_siropu_chat_sanction';
          $structure->shortName  = 'Chat:Sanction';
          $structure->primaryKey = 'sanction_id';

          $structure->columns = [
               'sanction_id'      => ['type' => self::UINT, 'autoIncrement' => true],
               'sanction_user_id' => ['type' => self::UINT, 'required' => true],
               'sanction_room_id' => ['type' => self::UINT, 'required' => true],
               'sanction_type'    => ['type' => self::STR, 'allowedValues' => ['mute', 'kick', 'ban'], 'required' => true],
               'sanction_start'   => ['type' => self::UINT, 'default' => \XF::$time],
               'sanction_end'     => ['type' => self::UINT, 'default' => 0],
               'sanction_author'  => ['type' => self::UINT, 'default' => \XF::visitor()->user_id],
               'sanction_reason'  => ['type' => self::STR, 'default' => '']
          ];

          $structure->getters   = [];
          $structure->relations = [
               'User' => [
                    'entity'     => 'XF:User',
                    'type'       => self::TO_ONE,
                    'conditions' => [['user_id', '=', '$sanction_user_id']]
               ],
               'Author' => [
                    'entity'     => 'XF:User',
                    'type'       => self::TO_ONE,
                    'conditions' => [['user_id', '=', '$sanction_author']]
               ],
               'Room' => [
                    'entity'     => 'Siropu\Chat:Room',
                    'type'       => self::TO_ONE,
                    'conditions' => [['room_id', '=', '$sanction_room_id']]
               ],
          ];

          return $structure;
     }
     public function canRemove()
     {
          $visitor = \XF::visitor();

          return $visitor->canSanctionSiropuChat();
     }
     public function getTypePhrase()
     {
          switch ($this->sanction_type)
          {
               case 'ban':
                    return \XF::phrase('siropu_chat_ban');
                    break;
               case 'kick':
                    return \XF::phrase('siropu_chat_kick');
                    break;
               case 'mute':
                    return \XF::phrase('siropu_chat_mute');
                    break;
          }
     }
     public function getNotice()
     {
          $phraseData = [
               'date'   => $this->sanction_end ? \XF::language()->dateTime($this->sanction_end) : null,
               'reason' => $this->sanction_reason ? $this->sanction_reason : '--'
          ];

          switch ($this->sanction_type)
          {
               case 'mute':
                    return '';
                    break;
               case 'kick':
                    return \XF::phrase('siropu_chat_you_have_been_kicked_until', $phraseData);
                    break;
               case 'ban':
                    if ($this->sanction_end)
                    {
                         return \XF::phrase('siropu_chat_you_have_been_banned_until', $phraseData);
                    }
                    else
                    {
                         return \XF::phrase('siropu_chat_you_have_been_banned_permanently', $phraseData);
                    }
                    break;
          }
     }
     public function isMute()
     {
          return $this->sanction_type == 'mute';
     }
     public function isKick()
     {
          return $this->sanction_type == 'kick';
     }
     public function isBan()
     {
          return $this->sanction_type == 'ban';
     }
     public function isGlobal()
     {
          return $this->sanction_room_id == 0;
     }
     public function getTypeNumber()
     {
          if ($this->isGlobal())
          {
               return $this->isMute() ? 2 : 3;
          }

          return 1;
     }
     protected function _postSave()
	{
          if ($this->canSendAlert())
          {
               $this->getAlertRepo()->alert(
                    $this->User,
                    $this->Author->user_id,
                    $this->Author->username,
                    'siropu_chat_sanction',
                    $this->sanction_id,
                    $this->sanction_type
               );
          }
	}
	protected function _postDelete()
	{
          $sanctionType = "un{$this->sanction_type}";

          if ($this->User && $this->canSendAlert($sanctionType))
          {
               $this->getAlertRepo()->alert(
                    $this->User,
                    $this->Author->user_id,
                    $this->Author->username,
                    'siropu_chat',
                    $this->User->user_id,
                    $sanctionType,
                    [
                         'room_id'   => $this->sanction_room_id,
                         'room_name' => $this->Room ? $this->Room->room_name : ''
                    ]
               );
          }
	}
     private function canSendAlert($type = null)
     {
          $sanctionType = $type ?: $this->sanction_type;
          return !empty(\XF::options()->siropuChatUserSanctionAlert[$sanctionType]);
     }
     private function getAlertRepo()
     {
          return $this->app()->repository('XF:UserAlert');
     }
}
