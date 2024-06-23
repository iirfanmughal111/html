<?php

namespace Siropu\Chat\Pub\Controller;

use XF\Mvc\ParameterBag;

class Archive extends AbstractController
{
     public static function getActivityDetails(array $activities)
	{
		return \XF::phrase('siropu_chat_viewing_chat_archive');
	}
     public function actionIndex(ParameterBag $params)
     {
          $visitor = \XF::visitor();
          $options = \XF::options();

          if (!$visitor->canViewSiropuChatArchive())
          {
               return $this->noPermission();
          }

          $messageIds = $this->filter('message_ids', 'array-uint');

          if (!empty($messageIds) && $visitor->canPruneSiropuChatMessages())
          {
               $this->getMessageRepo()->deleteMessagesByIds($messageIds);
               return $this->redirect($this->buildLink('chat/archive'));
          }

          if ($setOrder = $this->filter('set_order', 'str'))
          {
               $this->app()->response()->setCookie('siropu_chat_archive_order', $setOrder);
               return $this->redirect($this->buildLink('chat/archive'));
          }

          $input = $this->filter([
               'room_id'     => 'uint',
               'keywords'    => 'str',
               'users'       => 'str',
               'since_date'  => 'datetime',
               'until_date'  => 'datetime',
               'order'       => 'str'
          ]);

          if ($datePreset = $this->filter('date_preset', 'datetime'))
          {
               $input['since_date'] = $datePreset;
          }

          if (!$visitor->canViewSiropuChatRoomArchiveUponJoin())
          {
               $roomId       = $input['room_id'] ?: $visitor->siropu_chat_room_id;
               $roomJoinTime = $visitor->getSiropuChatRoomJoinTime($roomId);

               if ($roomJoinTime)
               {
                    $input['since_date'] = $roomJoinTime;
               }
          }

          if ($input['order'])
          {
               $orderBy = $input['order'];
          }
          else
          {
               $orderBy = $this->app()->request()->getCookie('siropu_chat_archive_order', 'newest');
          }

          $perPage = $options->siropuChatArchiveMessagesPerPage;

          $allowedTypes = $options->siropuChatArchiveMessageTypes;

          $searchTypes = [];

          if (!empty($allowedTypes['chat']))
          {
               $searchTypes[] = 'chat';
               $searchTypes[] = 'me';
          }

          if (!empty($allowedTypes['whisper']))
          {
               $searchTypes[] = 'whisper';
          }

          if (!empty($allowedTypes['bot']))
          {
               $searchTypes[] = 'bot';
          }

          if (!empty($allowedTypes['forum']))
          {
               $searchTypes[] = 'forum';
          }

          if ($params->message_id)
          {
               $message = $this->assertMessageExists($params->message_id);

               $finder = $this->getMessageRepo()
                    ->findMessages()
                    ->fromRoom($message->message_room_id)
                    ->fromType($searchTypes)
                    ->idBiggerThan($message->message_id);

               if (!$visitor->canSanctionSiropuChat())
               {
                    $finder->notIgnored();
               }

               return $this->redirect($this->buildLink('chat/archive', $message, [
                    'page'    => floor($finder->total() / $perPage) + 1,
                    'room_id' => $message->message_room_id,
                    'order'   => 'newest'
               ]) . "#rm-{$message->message_id}");
          }

          switch ($orderBy)
          {
               case 'oldest':
                    $order     = 'message_date';
                    $direction = 'ASC';
                    break;
               case 'likes':
                    $order     = 'message_like_count';
                    $direction = 'DESC';
                    break;
               case 'newest':
               default:
                    $order     = 'message_id';
                    $direction = 'DESC';
                    break;
          }

          $finder = $this->getMessageRepo()
               ->findMessages($order, $direction)
               ->fromType($searchTypes);

          if (!$visitor->canSanctionSiropuChat())
          {
               $finder->notIgnored();
          }

          if (!$visitor->getSiropuChatSetting('show_ignored'))
          {
               $finder->notFromIgnoredUsers();
          }

          $room = null;

          if ($visitor->canSearchSiropuChatArchive())
          {
               if (!empty($input['room_id']))
               {
                    $room = $this->assertRoomExists($input['room_id']);

                    if (!$room->canJoin())
                    {
                         return $this->noPermission();
                    }

                    $finder->fromRoom($input['room_id']);
               }

               if (!empty($input['keywords']))
               {
                    $finder->havingText($input['keywords']);
               }

               if (!empty($input['users']))
               {
                    $users = $this->repository('XF:User')->getUsersByNames(explode(',', $input['users']));
                    $finder->fromUser(array_keys($users->toArray()));
               }

               if (!empty($input['since_date']))
               {
                    $finder->dateNewerThan($input['since_date']);
               }

               if (!empty($input['until_date']))
               {
                    $finder->dateOlderThan($input['until_date']);
               }
          }

          $joinedRoomIds = $visitor->siropuChatGetRoomIds();

          if (!$room && !$visitor->canJoinAnyRoomSiropuChat())
          {
               $finder->fromRoom($joinedRoomIds);
          }

          $finder->limitByPage($params->page, $perPage);

          $viewParams = [
               'messages'    => $finder->fetch(),
               'total'       => $finder->total(),
               'page'        => $params->page,
               'perPage'     => $perPage,
               'params'      => array_filter($input),
               'order'       => $orderBy,
               'datePresets' => \XF::language()->getDatePresets(),
               'isArchive'   => true
          ];

          if ($visitor->canSearchSiropuChatArchive())
          {
               $roomFinder = $this->getRoomRepo()->findRoomsForSelect();

               if (!$visitor->canJoinAnyRoomSiropuChat())
               {
                    $roomFinder->fromRoom($joinedRoomIds);
               }

               $viewParams['rooms'] = $roomFinder->fetch();
          }

          return $this->view('Siropu\Chat:Archive', 'siropu_chat_archive', $viewParams);
     }
}
