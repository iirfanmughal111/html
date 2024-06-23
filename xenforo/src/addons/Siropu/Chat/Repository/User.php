<?php

namespace Siropu\Chat\Repository;

use XF\Mvc\Entity\Finder;
use XF\Mvc\Entity\Repository;

class User extends Repository
{
     public function findTopUsers($limit = 5)
     {
		return $this->repository('XF:User')
               ->findValidUsers()
               ->where('siropu_chat_message_count', '>', 0)
               ->order('siropu_chat_message_count', 'DESC')
               ->limit($limit);
     }
     public function findActiveUsers()
     {
          $options = \XF::options();

          switch ($options->siropuChatUsersOrder)
          {
               case 'most_active':
                    $field     = 'siropu_chat_last_activity';
                    $direction = 'DESC';
                    break;
               case 'alphabetically':
                    $field     = 'username';
                    $direction = 'ASC';
                    break;
          }

		return $this->finder('XF:User')
               ->where('siropu_chat_last_activity', '>=', $this->getActivityTimeout())
               ->order($field, $direction);
     }
     public function findActiveUsersForList(array $userRoomIds = [])
     {
          $visitor = \XF::visitor();

          return $this->findActiveUsers()
               ->where('siropu_chat_room_id', $userRoomIds)
               ->fetch()
               ->filter(function(\XF\Entity\User $user) use ($visitor)
               {
                    return ($user->isVisibleSiropuChat() && !$visitor->isIgnoring($user));
               });
     }
     public function findActiveUsersUnordered()
     {
          return $this->finder('XF:User')->where('siropu_chat_last_activity', '>=', $this->getActivityTimeout());
     }
     public function getActiveUserCount()
     {
          $visitor = \XF::visitor();

          return $this->finder('XF:User')
               ->where('siropu_chat_last_activity', '>=', $this->getActivityTimeout())
               ->fetch()
               ->filter(function(\XF\Entity\User $user) use ($visitor)
               {
                    return ($user->isVisibleSiropuChat() && !$visitor->isIgnoring($user));
               })
               ->count();
     }
     public function getActivityTimeout()
     {
          $options         = \XF::options();
          $activityTimeout = $options->siropuChatActiveStatusTimeout + $options->siropuChatIdleStatusTimeout;

          return strtotime("-$activityTimeout Minutes");
     }
     public function groupUsersByRoom($users)
     {
          $group = [];

          foreach ($users AS $user)
          {
               foreach ($user->getSiropuChatRooms() AS $roomId => $lastActivity)
               {
                    if ($lastActivity && $user->isActiveSiropuChat($lastActivity))
                    {
                         $group[$roomId][] = $user;
                    }
               }
          }

          if ($guestRoomId = \XF::options()->siropuChatGuestRoom)
          {
               $guestServiceManager = \XF::service('Siropu\Chat:Guest\Manager');

               foreach ($guestServiceManager->getGuestsForDisplay() as $guest)
               {
                    $group[$guestRoomId][] = $guest;
               }
          }

          return $group;
     }
     public function getUserCount($users)
     {
          $userCount = count($users);

          if (\XF::options()->siropuChatGuestRoom)
		{
			$guestServiceManager = \XF::service('Siropu\Chat:Guest\Manager');
			$userCount += $guestServiceManager->getActiveGuestCount();
		}

          return $userCount;
     }
     public function getUserIdsByRoom($users)
     {
          $group = [];

          foreach ($users AS $user)
          {
               foreach ($user->getSiropuChatRooms() AS $roomId => $lastActivity)
               {
                    if ($lastActivity && $user->isActiveSiropuChat($lastActivity))
                    {
                         $group[$roomId][] = $user->user_id;
                    }
               }
          }

          return $group;
     }
     public function getUsersData(array $users, $roomId)
     {
          $roomCache   = $this->app()->repository('Siropu\Chat:Room')->getRoomFromCache($roomId, false);
          $activeUsers = $this->app()->request()->filter('users', 'array');
          $roomUsers   = !empty($activeUsers[$roomId]) ? \Siropu\Chat\Util\Arr::getItemArray($activeUsers[$roomId]) : [];
          $hideStatus  = \XF::visitor()->getSiropuChatSetting('hide_status');

          $userData    = [];

          foreach ($users as $user)
          {
               $data = [
                    'activity' => $user->getSiropuChatActivityStatus($user->user_id ? $roomId : null),
                    'status'   => $hideStatus ? '' : strip_tags($user->siropu_chat_status),
                    'html'     => ''
               ];

               if ($user->user_id == 0)
               {
                    $data['username'] = $user->username;
               }

               if (!in_array($user->user_id, $roomUsers))
               {
                    $data['html'] = $this->app()->templater()->renderMacro('public:siropu_chat_user_list', 'room_user', [
                         'room' => $roomCache,
                         'user' => $user
                    ]);
               }

               $userData[$user->user_id] = $data;
          }

          foreach ($roomUsers as $userId)
          {
               if (!isset($userData[$userId]))
               {
                    $userData[$userId] = [
                         'activity' => 'inactive',
                         'status'   => '',
                         'html'     => ''
                    ];
               }
          }

          return $userData;
     }
     public function joinDefaultRooms()
     {
          $visitor = \XF::visitor();
          $options = \XF::options();

          if (!($visitor->user_id && $options->siropuChatRooms))
          {
               return;
          }

          $defaultRooms = $options->siropuChatDefaultJoinedRooms;

          if ($defaultRooms && $visitor->siropu_chat_last_activity == -1 && $visitor->canJoinSiropuChatRooms())
          {
               $rooms = $this->finder('Siropu\Chat:Room')
                    ->where('room_id', $defaultRooms)
                    ->order('room_id')
                    ->fetch();

               foreach ($rooms as $room)
               {
                    if ($room->canJoin() && $visitor->canJoinMoreSiropuChatRooms())
                    {
                         $visitor->siropuChatUpdateRooms($room->room_id, false);
                         $visitor->siropuChatSetActiveRoom($options->siropuChatPrimaryRoom ?: $room->room_id);
                    }
               }

               $visitor->siropu_chat_last_activity = 0;
               $visitor->save();
          }
     }
     public function autoLoginJoinedRooms($isChatPage = false)
     {
          $visitor   = \XF::visitor();
          $options   = \XF::options();

          $userRooms = $visitor->siropu_chat_rooms;
          $autoLogin = $options->siropuChatAutoLoginUsers;

          if (!($visitor->user_id && !empty($userRooms)))
          {
               return;
          }

          if ($autoLogin == 'any' || $autoLogin == 'chat' && $isChatPage)
          {
               $updateRooms = false;

               foreach ($userRooms as $roomId => $lastActive)
               {
                    if (!($lastActive && $visitor->isActiveSiropuChat($lastActive)))
                    {
                         $userRooms[$roomId] = \XF::$time;
                         $updateRooms = true;
                    }
               }

               if ($updateRooms)
               {
                    $visitor->siropuChatSetLastActivity();
                    $visitor->siropu_chat_rooms = $userRooms;
                    $visitor->save();

                    if ($isChatPage)
                    {
                         foreach ($userRooms as $key => $val)
                         {
                              $notifier = $this->app()->service('Siropu\Chat:Room\Notifier', $visitor, $key);
                              $notifier->notify('join');
                         }
                    }
               }
          }
     }
}
