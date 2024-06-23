<?php

namespace Siropu\Chat\XF\Entity;

use XF\Mvc\Entity\Structure;

class User extends XFCP_User
{
     public function canViewSiropuChat()
	{
          return $this->hasPermission('siropuChat', 'viewChat');
     }
     public function canUseSiropuChat()
	{
          return $this->hasPermission('siropuChat', 'useChat');
     }
     public function canJoinAnyRoomSiropuChat()
     {
          return $this->hasPermission('siropuChatModerator', 'joinAnyRoom');
     }
     public function canJoinSiropuChatRooms()
     {
          $options = \XF::options();

          if ($this->user_id == 0 && $options->siropuChatGuestRoom)
          {
               return 1;
          }

          $joinRooms = $this->hasPermission('siropuChat', 'joinRooms');

          if ($joinRooms != 0)
          {
               return $joinRooms;
          }
     }
     public function canJoinMoreSiropuChatRooms(&$error = null)
     {
          $joinLimit = $this->canJoinSiropuChatRooms();

          if ($joinLimit >= 1 && count($this->getSiropuChatRooms()) >= $joinLimit)
          {
               if ($joinLimit > 1)
               {
                    $error = \XF::phraseDeferred('siropu_chat_you_cannot_join_more_than_x_rooms', ['limit' => $joinLimit]);
               }
               else
               {
                    $error = \XF::phraseDeferred('siropu_chat_you_cannot_join_more_than_one_room');
               }

               return false;
          }

          return true;
     }
     public function canBrowseSiropuChatRooms()
     {
          return $this->hasPermission('siropuChat', 'browseRooms');
     }
     public function canBypassSiropuChatRoomPassword()
     {
          return $this->hasPermission('siropuChat', 'bypassRoomPassword');
     }
     public function canViewSiropuChatPrivateRooms()
     {
          return $this->hasPermission('siropuChat', 'viewPrivateRooms');
     }
     public function canCreateSiropuChatRooms()
     {
          return $this->hasPermission('siropuChat', 'createRooms');
     }
     public function canPasswordProtectSiropuChatRooms()
     {
          return $this->hasPermission('siropuChat', 'passwordProtectRooms');
     }
     public function canViewSiropuChatArchive()
     {
          return $this->hasPermission('siropuChat', 'viewArchive');
     }
     public function canSearchSiropuChatArchive()
     {
          return $this->hasPermission('siropuChat', 'searchArchive');
     }
     public function canViewSiropuChatTopChatters()
     {
          return $this->hasPermission('siropuChat', 'viewTopChatters');
     }
     public function canViewSiropuChatSanctions()
     {
          return $this->hasPermission('siropuChat', 'viewSanctioned');
     }
     public function canEditSiropuChatRules()
     {
          return $this->hasPermission('siropuChatAdmin', 'editRules');
     }
     public function canEditSiropuChatNotice()
     {
          return $this->hasPermission('siropuChatAdmin', 'editNotice');
     }
     public function canEditSiropuChatAds()
     {
          return $this->hasPermission('siropuChatAdmin', 'editAds');
     }
     public function canEditSiropuChatRoomSettings()
     {
          return $this->hasPermission('siropuChatAdmin', 'editRoomSettings');
     }
     public function canEditSiropuChatAnyRoom()
     {
          return $this->hasPermission('siropuChatModerator', 'editAnyRoom');
     }
     public function canWhisperSiropuChat()
     {
          return $this->hasPermission('siropuChat', 'whisper');
     }
     public function canChatInPrivateSiropuChat()
     {
          return $this->hasPermission('siropuChat', 'chatInPrivate');
     }
     public function canSetMessageColorSiropuChat()
     {
          return $this->hasPermission('siropuChat', 'setColor');
     }
     public function canChangeSettingsSiropuChat()
     {
          return $this->hasPermission('siropuChat', 'changeSettings');
     }
     public function canChangeDisplayModeSiropuChat()
     {
          return $this->hasPermission('siropuChat', 'changeDisplayMode');
     }
     public function canReportSiropuChatMessages()
     {
          return $this->hasPermission('siropuChat', 'reportMessages');
     }
     public function canBypassSiropuChatFloodCheck()
     {
          return $this->hasPermission('siropuChat', 'bypassFloodCheck');
     }
     public function canSetSiropuChatRoomUsers()
     {
          return $this->hasPermission('siropuChat', 'setRoomUsers');
     }
     public function canRoomAuthorSanctionSiropuChat()
     {
          return $this->hasPermission('siropuChat', 'roomAuthorSanction');
     }
     public function canViewSiropuChatRoomArchiveUponJoin()
     {
          return $this->hasPermission('siropuChat', 'viewRoomArchiveUponJoin');
     }
     public function canPruneSiropuChatMessages()
     {
          return $this->hasPermission('siropuChatModerator', 'prune');
     }
     public function canViewSiropuChatHiddenUsers()
     {
          return $this->hasPermission('siropuChatModerator', 'viewHiddenUsers');
     }
     public function canSanctionSiropuChat()
     {
          return $this->hasPermission('siropuChatModerator', 'sanction');
     }
     public function canPostSiropuChatAnnouncements()
     {
          return $this->hasPermission('siropuChatAdmin', 'postAnnouncements');
     }
     public function canResetSiropuChatUserData()
     {
          return $this->hasPermission('siropuChatAdmin', 'resetUserData');
     }
     public function canSetSiropuChatStatus()
     {
          return \XF::options()->siropuChatStatus && $this->hasPermission('siropuChat', 'setStatus');
     }
     public function canUploadSiropuChatImages()
     {
          $uploadLimit = $this->hasPermission('siropuChat', 'uploadImages');

          if ($uploadLimit == 0)
          {
               return false;
          }

          if ($uploadLimit >= 1)
          {
               return $uploadLimit;
          }

          return 1000;
     }
     public function canAlertMentionSiropuChatUser($user)
     {
          return !$user->isIgnoring($this->user_id)
               && $user->getSiropuChatSetting('mention_alert')
               && !$user->isBannedSiropuChat()
               && $this->user_id != $user->user_id;
     }
     public function canMessageSiropuChatUser($user)
     {
          return $this->canChatInPrivateSiropuChat()
               && $user->canChatInPrivateSiropuChat()
               && !$user->isIgnoring($this->user_id)
               && !$user->isBannedSiropuChat()
               && $this->canStartConversationWith($user)
               && $this->user_id != $user->user_id;
     }
     public function isInConversationWithSiropuChatUser($user)
     {
          return in_array($user->user_id, $this->siropuChatGetConversations());
     }
     public function canSanctionSiropuChatUser($user)
     {
          return $this->canSanctionSiropuChat() && $user->canBeSanctionedSiropuChat() && $this->user_id != $user->user_id;
     }
     public function canBeSanctionedSiropuChat()
     {
          return !($this->is_admin || $this->is_staff);
     }
     public function isSanctionedSiropuChat()
     {
          return $this->siropu_chat_is_sanctioned > 0;
     }
     public function isMutedSiropuChat()
     {
          return $this->siropu_chat_is_sanctioned == 2;
     }
     public function isBannedSiropuChat()
     {
          return $this->siropu_chat_is_sanctioned == 3;
     }
     public function canRoomAuthorSanctionSiropuChatUser($user, $roomUserId)
     {
          return $this->canRoomAuthorSanctionSiropuChat()
               && $user->canBeSanctionedSiropuChat()
               && $this->user_id == $roomUserId
               && $this->user_id != $user->user_id;
     }
     public function hasJoinedRoomsSiropuChat()
     {
          $joinedRooms = $this->siropuChatGetJoinedRooms();
          return !empty($joinedRooms);
     }
     public function hasJoinedRoomSiropuChat($roomId)
     {
          return isset($this->siropuChatGetJoinedRooms()[$roomId]);
     }
     public function hasConversationsSiropuChat()
     {
          $conversations = $this->siropuChatGetConversations();
          return !empty($conversations);
     }
     public function hasConversationSiropuChat($convId)
     {
          return isset($this->siropuChatGetConversations()[$convId]);
     }
     public function getLastRoomIdSiropuChat()
     {
          $options = \XF::options();
          $visitor = \XF::visitor();

          if ($visitor->user_id)
          {
               $defaultRoomId = $this->siropu_chat_room_id ?: $options->siropuChatPrimaryRoom;
               return $this->app()->request()->getCookie('siropu_chat_room_id', $defaultRoomId);
          }

          return $options->siropuChatGuestRoom;
     }
     public function getLastConvIdSiropuChat()
     {
          return $this->app()->request()->getCookie('siropu_chat_conv_id', $this->siropu_chat_conv_id);
     }
     public function getSiropuChatActivityStatus($roomId = null)
     {
          if ($roomId)
          {
               $lastActivity = $this->siropu_chat_rooms[$roomId];
          }
          else
          {
               $lastActivity = $this->siropu_chat_last_activity;
          }

          $lastActivityDiff = \XF::$time - $lastActivity;

          if ($lastActivityDiff <= \XF::options()->siropuChatActiveStatusTimeout * 60)
          {
               return 'active';
          }
          else
          {
               return 'idle';
          }
     }
     public function isActiveSiropuChat($lastActivity = false)
     {
          if (!$this->user_id && \XF::options()->siropuChatGuestRoom)
          {
               $guestService = $this->app()->service('Siropu\Chat:Guest\Manager');

               if ($guest = $guestService->getGuest())
               {
                    $lastActivity = $guest['lastActivity'];
               }
          }

          if ($lastActivity === false)
          {
               $lastActivity = $this->siropu_chat_last_activity;
          }

          return $lastActivity >= $this->repository('Siropu\Chat:User')->getActivityTimeout();
     }
     public function siropuChatIncrementMessageCount()
     {
          $this->siropu_chat_message_count++;
     }
     public function siropuChatDecrementMessageCount()
     {
          $this->siropu_chat_message_count--;
     }
     public function siropuChatSetLastActivity($time = null)
     {
          $this->siropu_chat_last_activity = $time ?: \XF::$time;
     }
     public function siropuChatSetActiveRoom($roomId)
     {
          $this->siropu_chat_room_id = $roomId;
          \Siropu\Chat\Util\Cookie::setRoomId($roomId);
     }
     public function siropuChatUpdateRooms($roomId, $lastActivity = true)
     {
          if (empty($roomId))
          {
               return;
          }

          $rooms = $this->getSiropuChatRooms();

          if ($lastActivity)
          {
               $time = \XF::$time;
          }
          else
          {
               $time = 0;
          }

          $this->siropu_chat_rooms = array_replace($rooms, [$roomId => $time]);

          if (!isset($rooms[$roomId]))
          {
               $roomJoinTime = $this->getSiropuChatRoomJoinTime();
               $this->siropu_chat_room_join_time = array_replace($roomJoinTime, [$roomId => \XF::$time]);

               if ($lastActivity)
               {
                    $notifier = $this->app()->service('Siropu\Chat:Room\Notifier', $this, $roomId);
                    $notifier->notify('join');
               }
          }
     }
     public function siropuChatLeaveRoom($roomId, $notify = true, $message = '')
     {
          $rooms        = $this->siropu_chat_rooms;
          $roomJoinTime = $this->siropu_chat_room_join_time;

          unset($rooms[$roomId], $roomJoinTime[$roomId]);

          $this->siropu_chat_rooms = $rooms;
          $this->siropu_chat_room_join_time = $roomJoinTime;

          if (empty($rooms))
          {
               $this->siropu_chat_last_activity = 0;
          }

          $this->siropu_chat_room_id = empty($rooms) ? 0 : current(array_keys($rooms));

          if ($notify)
          {
               $notifier = $this->app()->service('Siropu\Chat:Room\Notifier', $this, $roomId, ['message' => $message]);
               $notifier->notify('leave');
          }
     }
     public function siropuChatLogout($message = '')
     {
          foreach ($this->siropuChatGetRoomIds() as $roomId)
          {
               $this->siropuChatLeaveRoom($roomId, true, $message);
          }

          $this->siropu_chat_last_activity = 0;
          $this->siropu_chat_room_id = 0;

          \Siropu\Chat\Util\Cookie::setRoomId(false);
     }
     public function siropuChatGetJoinedRooms()
     {
          $rooms = $this->getSiropuChatRooms();

          if (!$this->user_id && ($roomId = \XF::options()->siropuChatGuestRoom))
          {
               $rooms[$roomId] = \XF::$time;
          }

          return $rooms;
     }
     public function siropuChatGetRoomIds()
     {
          return array_keys($this->siropuChatGetJoinedRooms());
     }
     public function siropuChatGetConversations()
     {
          return $this->siropu_chat_conversations ?: [];
     }
     public function siropuChatGetConvIds()
     {
          return array_keys($this->siropuChatGetConversations());
     }
     public function siropuChatGetJoinedRoomsEntities()
     {
          return $this->repository('Siropu\Chat:Room')->findRooms()->fromRoom($this->siropuChatGetRoomIds())->fetch();
     }
     public function siropuChatSetStatus($status, &$reply = null)
     {
          $status = $this->app()->stringFormatter()->wholeWordTrim(strip_tags($status), \XF::options()->siropuChatStatusMaxLength);
          $this->siropu_chat_status = $status;

          if ($status)
          {
               $reply = \XF::phrase('siropu_chat_your_status_has_beed_set_to_x', ['status' => $status]);
          }
          else
          {
               $reply = \XF::phrase('siropu_chat_your_status_has_beed_removed');
          }
     }
     public function siropuChatGetUserSanctions()
     {
          return $this->repository('Siropu\Chat:Sanction')->findUserSanctions($this->user_id);
     }
     public function siropuChatGetRoomSanction($roomId)
     {
          return $this->repository('Siropu\Chat:Sanction')->findRoomUserSanction($roomId, $this->user_id);
     }
     public function siropuChatGetUserWrapper($at = null)
	{
          return '[USER=' . $this->user_id . ']' . ($at ? '@' : '') . $this->username . '[/USER]';
     }
     public function siropuChatLeaveConversation($convId)
     {
          $conversations = $this->siropu_chat_conversations;
          unset($conversations[$convId]);

          $this->siropu_chat_conversations = $conversations;
          $this->siropu_chat_conv_id = empty($conversations) ? 0 : current(array_keys($conversations));
     }
     public function siropuChatJoinConversation($convId, $userId)
     {
          $this->siropu_chat_conversations = array_replace($this->siropuChatGetConversations(), [$convId => $userId]);
     }
     public function getSiropuChatRooms()
     {
          return $this->siropu_chat_rooms ?: [];
     }
     public function getSiropuChatSettings()
     {
          $options = \XF::options();

          return array_replace_recursive([
                    'sound'         => $options->siropuChatDefaultSoundSettings,
                    'notification'  => $options->siropuChatDefaultNotificationSettings,
                    'display_mode'  => $options->siropuChatDisplayMode,
                    'message_color' => ''
               ],
               $options->siropuChatDefaultMiscSettings,
               $this->siropu_chat_settings ?: []
          );
     }
     public function getSiropuChatSetting($setting)
     {
          return $this->getSiropuChatSettings()[$setting];
     }
     public function hasDesktopNotifications()
     {
          foreach ($this->getSiropuChatSetting('notification') as $value)
          {
               if ($value)
               {
                    return true;
               }
          }
     }
     public function isVisibleSiropuChat()
     {
          $visitor = \XF::visitor();
          $options = \XF::options();

          if (!$options->siropuChatComplyWithUserPrivacy || $visitor->canViewSiropuChatHiddenUsers())
          {
               return true;
          }

          return $this->visible || $this->user_id == $visitor->user_id;
     }
     public function getSiropuChatRoomJoinTime($roomId = null)
     {
          $roomJoinTime = $this->siropu_chat_room_join_time ?: [];

          if ($roomId)
          {
               return isset($roomJoinTime[$roomId]) ? $roomJoinTime[$roomId] : 0;
          }

          return $roomJoinTime;
     }
     public function isInShoutboxModeSiropuChat()
     {
          $options = \XF::options();

          return ($options->siropuChatRooms
               && !$options->siropuChatPrivateConversations
               && $this->canJoinSiropuChatRooms() == 1
               && !$this->canBrowseSiropuChatRooms());
     }
     public static function getStructure(Structure $structure)
     {
          $structure = parent::getStructure($structure);

          $structure->columns['siropu_chat_room_id'] = [
               'type'      => self::UINT,
               'default'   => 0,
               'changeLog' => false
          ];
          $structure->columns['siropu_chat_conv_id'] = [
               'type'      => self::UINT,
               'default'   => 0,
               'changeLog' => false
          ];
          $structure->columns['siropu_chat_rooms'] = [
              'type'      => self::JSON_ARRAY,
              'default'   => [],
              'nullable'  => true,
              'changeLog' => false
          ];
          $structure->columns['siropu_chat_conversations'] = [
              'type'      => self::JSON_ARRAY,
              'default'   => [],
              'nullable'  => true,
              'changeLog' => false
          ];
          $structure->columns['siropu_chat_settings'] = [
              'type'      => self::JSON_ARRAY,
              'default'   => [],
              'nullable'  => true,
              'changeLog' => false
          ];
          $structure->columns['siropu_chat_room_join_time'] = [
              'type'      => self::JSON_ARRAY,
              'default'   => [],
              'nullable'  => true,
              'changeLog' => false
          ];
          $structure->columns['siropu_chat_status'] = [
               'type'      => self::STR,
               'default'   => '',
               'maxLength' => 255,
               'censor'    => true,
               'changeLog' => false
          ];
          $structure->columns['siropu_chat_is_sanctioned'] = [
               'type'      => self::UINT,
               'default'   => 0,
               'changeLog' => false
          ];
          $structure->columns['siropu_chat_message_count'] = [
               'type'      => self::UINT,
               'default'   => 0,
               'changeLog' => false
          ];
          $structure->columns['siropu_chat_last_activity'] = [
               'type'      => self::INT,
               'default'   => -1,
               'changeLog' => false
          ];

          return $structure;
     }
}
