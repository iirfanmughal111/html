<?php

namespace Siropu\Chat\Pub\Controller;

use XF\Mvc\ParameterBag;

class Room extends AbstractController
{
     public static function getActivityDetails(array $activities)
	{
          return self::getActivityDetailsForContent(
			$activities, \XF::phrase('siropu_chat_viewing_chat_room'), 'room_id',
			function(array $ids)
			{
				$rooms = \XF::em()->findByIds('Siropu\Chat:Room', $ids);

				$router = \XF::app()->router('public');
				$data   = [];

				foreach ($rooms AS $id => $room)
				{
					$data[$id] = [
						'title' => $room->room_name,
						'url'   => $router->buildLink('chat/room', $room)
					];
				}

				return $data;
			},
			\XF::phrase('siropu_chat_viewing_chat_room')
		);
	}
     public function actionIndex(ParameterBag $params)
     {
          if (!$this->isLoggedIn())
          {
               return $this->noPermission();
          }

          $room = $this->assertRoomExists($params->room_id);

          if ($notice = $room->isSanctionNotice())
          {
			return $this->message($notice);
          }

          $canJoin = $room->canJoin($this->filter('password', 'str'), true, $message);

          if ($message)
          {
               return $this->message($message);
          }

          if (!$room->isJoined() && !$canJoin)
          {
               return $this->view('Siropu\Chat:Room\Join', 'siropu_chat_room_join', ['room' => $room]);
          }

          \Siropu\Chat\Util\Cookie::setRoomId($room->room_id);

          $visitor = \XF::visitor();
          $visitor->siropuChatUpdateRooms($room->room_id);
          $visitor->siropuChatSetActiveRoom($room->room_id);
          $visitor->siropuChatSetLastActivity();
          $visitor->save();

          $fullPage = $this->filter('fullpage', 'bool');

          if ($visitor->getLastRoomIdSiropuChat() != $room->room_id)
          {
               $linkParams = [];

               if ($fullPage)
               {
                    $linkParams = [
                         'fullpage' => 1
                    ];
               }

               return $this->redirect($this->buildLink('chat/room', $room, $linkParams));
          }

          $viewParams = [
               'channel'    => 'room',
               'isFullPage' => $fullPage,
               'hideTabs'   => $fullPage
          ];

          return $this->plugin('Siropu\Chat:Chat')->loadChat($viewParams);
     }
     public function actionCreate()
     {
          $visitor = \XF::visitor();

          if (!$visitor->canCreateSiropuChatRooms())
          {
               throw $this->exception($this->noPermission());
          }

          $room = $this->em()->create('Siropu\Chat:Room');
          return $this->roomAddEdit($room);
     }
     public function actionLink(ParameterBag $params)
     {
          $room = $this->assertRoomExists($params->room_id);

          $viewParams = [
               'room' => $room
          ];

          return $this->view('Siropu\Chat:Room\Link', 'siropu_chat_room_link', $viewParams);
     }
     protected function roomAddEdit(\Siropu\Chat\Entity\Room $room)
     {
          $rooms = $this->getRoomRepo()
               ->findRoomsForList()
               ->notFromRoom($room->room_id)
               ->fetch()
               ->pluckNamed('room_name', 'room_id');

          $viewParams = [
               'room'       => $room,
               'rooms'      => $rooms,
               'userGroups' => $this->repository('XF:UserGroup')->findUserGroupsForList()->fetch(),
               'languages'  => $this->repository('XF:Language')->getLanguageTree(false)->getFlattened()
          ];

          return $this->view('Siropu\Chat:Room\Edit', 'siropu_chat_room_edit', $viewParams);
     }
     public function actionEdit(ParameterBag $params)
     {
          $room = $this->assertRoomExistsAndCanEdit($params->room_id);
          return $this->roomAddEdit($room);
     }
     public function actionSave(ParameterBag $params)
     {
          $this->assertPostOnly();

          $visitor = \XF::visitor();

          if ($params->room_id)
          {
               $room = $this->assertRoomExistsAndCanEdit($params->room_id);
          }
          else
          {
               if (!$visitor->canCreateSiropuChatRooms())
               {
                    return $this->noPermission();
               }

               $room = $this->em()->create('Siropu\Chat:Room');
          }

          if ($visitor->canPasswordProtectSiropuChatRooms())
          {
               $room->room_password = $this->filter('room_password', 'str');
          }

          if ($visitor->canEditSiropuChatRoomSettings())
          {
               $room->room_user_groups  = $this->filter('room_user_groups', 'array-uint');
               $room->room_leave        = $this->filter('room_leave', 'uint');
               $room->room_readonly     = $this->filter('room_readonly', 'bool');
               $room->room_locked       = $this->filter('room_locked', 'datetime');
               $room->room_rss          = $this->filter('room_rss', 'bool');
               $room->room_max_users    = $this->filter('room_max_users', 'uint');
               $room->room_prune        = $this->filter('room_prune', 'uint');
               $room->room_flood        = $this->filter('room_flood', 'uint');
               $room->room_thread_id    = $this->filter('room_thread_id', 'uint');
               $room->room_thread_reply = $this->filter('room_thread_reply', 'bool');
               $room->room_language_id  = $this->filter('room_language_id', 'uint');
               $room->room_child_ids    = $this->filter('room_child_ids', 'array-uint');

               if ($room->room_prune)
               {
                    if (!$room->room_last_prune)
                    {
                         $room->room_last_prune = \XF::$time;
                    }
               }
               else
               {
                    $room->room_last_prune = 0;
               }
          }

          $users = $this->filter('room_users', 'str');

          if ($visitor->canSetSiropuChatRoomUsers())
          {
               if (!empty($users))
               {
                    $userFinder = $this->finder('XF:User')
                         ->where('username', array_map('trim', explode(',', $users)))
                         ->fetch()
                         ->filter(function(\XF\Entity\User $user)
                         {
                              return ($user->canUseSiropuChat() && $user->canJoinSiropuChatRooms());
                         });

                    if ($userFinder->count())
                    {
                         $room->room_users = $userFinder->pluckNamed('username', 'user_id');
                    }
                    else
                    {
                         return $this->message(\XF::phrase('siropu_chat_room_users_no_valid'));
                    }
               }
               else
               {
                    $room->room_users = [];
               }
          }

          $input = $this->filter([
			'room_name'        => 'str',
			'room_description' => 'str'
		]);

          $room->bulkSet($input);
          $room->save();

          if ($this->filter('join_room', 'bool'))
          {
               return $this->plugin('Siropu\Chat:Room')->joinRoom($room);
          }

          if ($room->isUpdate())
          {
               return $this->message(\XF::phrase('your_changes_have_been_saved'));
          }

          $reply = $this->view('Siropu\Chat:Room\List', 'siropu_chat_room_list');
          $reply->setJsonParam('room_id', $room->room_id);

          return $reply;
     }
     public function actionDelete(ParameterBag $params)
     {
          $room = $this->assertRoomExists($params->room_id);

          if ($room->isMain())
          {
               return $this->error(\XF::phrase('siropu_chat_room_cannot_be_deleted'));
          }

          if (!$room->canDelete())
          {
               return $this->noPermission();
          }

          if ($this->isPost())
          {
               $room->delete();

               if ($room->isJoined())
               {
                    $visitor = \XF::visitor();
                    $visitor->siropuChatLeaveRoom($room->room_id);
                    $visitor->save();
               }

               $reply = $this->view();
               $reply->setJsonParams(['room_id' => $room->room_id]);

               return $reply;
          }

          $viewParams = [
               'room' => $room
          ];

          return $this->view('Siropu\Chat:Room\Delete', 'siropu_chat_room_delete', $viewParams);
     }
     public function actionList()
     {
          $find    = $this->filter('find', 'str');
          $page    = $this->filterPage();
          $perPage = $this->options()->siropuChatRoomsPerPage;

          $roomFinder = $this->getRoomRepo()
               ->findRoomsForList()
               ->limitByPage($page, $perPage);

          if ($find)
          {
               $roomFinder->havingName($find);
          }

          $rooms = $roomFinder->fetch()
               ->filter(function(\Siropu\Chat\Entity\Room $room)
               {
                    return ($room->canJoin(null, false) || \XF::visitor()->canViewSiropuChatPrivateRooms());
               });

          $users = $this->getUserRepo()
               ->findActiveUsers()
               ->fetch()
               ->filter(function(\XF\Entity\User $user)
               {
                    return ($user->isVisibleSiropuChat());
               });

          $total = $roomFinder->total();

          $viewParams = [
               'rooms'     => $rooms,
               'total'     => $total,
               'page'      => $page,
               'perPage'   => $perPage,
               'find'      => $find,
               'users'     => $this->getUserRepo()->groupUsersByRoom($users),
               'paginated' => $total > $perPage
          ];

          return $this->view('Siropu\Chat:Room\List', 'siropu_chat_room_list', $viewParams);
     }
     public function actionJoin(ParameterBag $params)
     {
          $this->assertPostOnly();

          if (!$this->isLoggedIn())
          {
               return $this->noPermission();
          }

          return $this->plugin('Siropu\Chat:Room')->checkPermissionsAndJoin($this->assertRoomExists($params->room_id));
     }
     public function actionLeave(ParameterBag $params)
     {
          $room = $this->assertRoomExists($params->room_id);

          if (!($this->isLoggedIn() && $room->isJoined()))
          {
               return $this->noPermission();
          }

          if (!$room->canLeave($error))
          {
               return $this->message($error);
          }

          $visitor = \XF::visitor();

          if ($visitor->isInShoutboxModeSiropuChat())
          {
               return $this->message(\XF::phrase('siropu_chat_cannot_leave_room'));
          }

          $visitor->siropuChatLeaveRoom($params->room_id);
          $visitor->save();

          $jsonParams = [
               'roomId' => $params->room_id,
               'action' => 'leave'
          ];

          if ($this->filter('widget', 'bool'))
          {
               $jsonParams['message'] = \XF::phrase('siropu_chat_you_have_left_the_room_x', ['name' => $room->room_name]);
          }

          $reply = $this->view();
          $reply->setJsonParams($jsonParams);

          return $reply;
     }
     public function actionSanctions(ParameterBag $params)
     {
          $room    = $this->assertRoomExists($params->room_id);
          $page    = $this->filterPage($params->page);
          $perPage = 25;

          $sanctionFinder = $this->getSanctionRepo()
               ->findSanctions()
               ->forRoom($room->room_id)
               ->limitByPage($page, $perPage, 1);

          $sanctions      = $sanctionFinder->fetch();
          $hasMore        = $sanctions->count() > $perPage;
		$sanctions      = $sanctions->slice(0, $perPage);
		$sanctionsCount = $sanctionFinder->total();

          $viewParams = [
               'room'      => $room,
               'sanctions' => $sanctions,
               'perPage'   => $perPage,
			'total'     => $sanctionsCount,
               'page'      => $page,
			'hasMore'   => $hasMore
          ];

          return $this->view('Siropu\Chat:Room\Sanctions', 'siropu_chat_room_sanctions', $viewParams);
     }
     public function actionFind()
     {
          $q = ltrim($this->filter('q', 'str', ['no-trim']));

          if ($q !== '' && utf8_strlen($q) >= 2)
		{
			$roomFinder = $this->finder('Siropu\Chat:Room');
			$rooms = $roomFinder->where('room_name', 'like', $roomFinder->escapeLike($q, '?%'))->fetch(10);
		}
		else
		{
			$rooms = [];
			$q     = '';
		}

          $viewParams = [
			'rooms' => $rooms,
               'q'     => $q
		];

		return $this->view('Siropu\Chat:Room\Find', '', $viewParams);
     }
     public function actionLoadMoreMessages(ParameterBag $params)
     {
          $room = $this->assertRoomExists($params->room_id);

          if (!$room->isJoined())
          {
               return $this->noPermission();
          }

          $visitor = \XF::visitor();

          if (!$visitor->canViewSiropuChatArchive())
          {
               return $this->noPermission();
          }

          $finder = $this->getMessageRepo()
               ->findMessages()
               ->fromRoom($room->room_id)
               ->idSmallerThan($this->filter('message_id', 'uint'))
               ->defaultLimit();

          if ($this->getChatSettings()['hide_bot'])
		{
			$finder->notFromType('bot');
		}

          if (!$this->getChatSettings()['show_ignored'])
          {
               $finder->notFromIgnoredUsers();
          }

          if ($text = $this->filter('find', 'string'))
          {
               $finder->havingText($text);
          }

          $messages = $finder->fetch()->filter(function(\Siropu\Chat\Entity\Message $message)
          {
               return ($message->canView());
          });

          if (!$this->getChatSettings()['inverse'])
          {
               $messages = $messages->reverse();
          }

          $viewParams = [
               'messages' => $messages,
               'hasMore'  => $messages->count() == \XF::options()->siropuChatMessageDisplayLimit,
               'find'     => ''
          ];

          return $this->view('Siropu\Chat:Message\Find', '', $viewParams);
     }
     public function actionSanction(ParameterBag $params)
     {
          $room = $this->assertRoomExists($params->room_id);
          $user = $this->assertUserExists($params->user_id);
          $type = $this->filter('sanction_type', 'str');

          $visitor = \XF::visitor();

          if (!($visitor->canRoomAuthorSanctionSiropuChatUser($user, $room->room_user_id)))
          {
               return $this->noPermission();
          }

          if ($this->isPost())
          {
               $length = $this->filter('length', 'uint');
               $reason = $this->filter('reason', 'str');

               if ($length > 24)
               {
                    $length = 24;
               }

               $data = [
                    'room_id'  => $room->room_id,
                    'end_date' => strtotime("+$length Hours"),
                    'reason'   => $reason
               ];

               $sanctionService = $this->service('Siropu\Chat:Sanction\Manager', $user, null, $data);
               $sanctionService->bypassPermissionCheck();
               $sanctionService->applySanction($type);

               $reply = $this->message(\XF::phrase('siropu_chat_x_has_been_sanctioned_for_x_hours', [
                    'user'   => $user->username,
                    'length' => $length
               ]));

               $reply->setJsonParams([
                    'user_id'       => $user->user_id,
                    'sanction_type' => $type
               ]);

               return $reply;
          }

          $viewParams = [
               'room' => $room,
               'user' => $user
          ];

          return $this->view('Siropu\Chat:Room\Sanction', 'siropu_chat_room_sanction', $viewParams);
     }
     public function actionRss(ParameterBag $params)
     {
          $room = $this->assertRoomExists($params->room_id);

          if (!$room->room_rss)
          {
               return $this->noPermission();
          }

          $messageFinder = $this->getMessageRepo()->findRoomMessages($room->room_id);
          $messageFinder->fromType(['chat', 'me']);
          $messageFinder->order('message_date', 'DESC');
          $messageFinder->limit(50);

          $messages = $messageFinder->fetch();

          $viewParams = [
               'room'     => $room,
               'messages' => $messages->reverse()
          ];

          $this->setResponseType('rss');

          return $this->view('Siropu\Chat:Room\Rss', 'siropu_chat_room_rss', $viewParams);
     }
     public function actionTyping(ParameterBag $params)
     {
          $visitor = \XF::visitor();

          if (!$this->isLoggedIn())
          {
               return $this->noPermission();
          }

          $room = $this->assertRoomExists($params->room_id);

          if ($visitor->hasJoinedRoomSiropuChat($room->room_id))
          {
               $fileName = "data/siropu/chat/room/{$room->room_id}.json";

               if (file_exists($fileName))
               {
                    $whosTyping = json_decode(file_get_contents($fileName), true) ?: [];

                    foreach ($whosTyping as $id => $data)
                    {
                         if ($data['t'] - \XF::$time <= 5000)
                         {
                              unset($whosTyping[$id]);
                         }
                    }
               }
               else
               {
                    $whosTyping = [];
               }

               $whosTyping[$visitor->user_id] = ['u' => $visitor->username, 't' => \XF::$time];

               $room->writeWhosTypingJsonFile($whosTyping);
          }

          return $this->view('Siropu\Chat:Room\Typing');
     }
     protected function assertRoomExistsAndCanEdit($id, $with = null)
	{
		$room = $this->assertRoomExists($id, $with);

          if (!$room->canEdit())
          {
               throw $this->exception($this->noPermission());
          }

          return $room;
	}
}
