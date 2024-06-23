<?php

namespace Siropu\Chat\ControllerPlugin;

class Update extends \XF\ControllerPlugin\AbstractPlugin
{
	public function getUpdates(array $params)
	{
		$visitor  = \XF::visitor();
          $options  = \XF::options();

		$settings = $visitor->getSiropuChatSettings();
		$whereOr  = [];

          $hideTabs = $this->filter('hide_tabs', 'bool');
          $inRoomId = $this->filter('room_id', 'uint');

          $userRoomIds   = $visitor->siropuChatGetRoomIds();
          $messageFinder = $this->getMessageRepo()->findMessages()->defaultLimit();

          if ($inRoomId && !in_array($inRoomId, $userRoomIds))
          {
               return $this->view();
          }

		if (!empty($params['lastId']))
		{
               if ($hideTabs)
               {
                    $messageFinder->where('message_room_id', $inRoomId);
                    $messageFinder->where('message_id', '>', $params['lastId'][$inRoomId]);
               }
               else
               {
                    foreach ($params['lastId'] as $roomId => $lastId)
     			{
     				if (in_array($roomId, $userRoomIds))
     				{
     					$whereOr[] = [['message_room_id', $roomId], ['message_id', '>', $lastId]];
     				}
     			}
               }
		}

		if (!$visitor->canSanctionSiropuChat())
		{
			$messageFinder->notIgnored();
		}

          if (!empty($params['roomId']))
          {
               $messageFinder->fromRoom($params['roomId']);
          }
          else if ($whereOr)
          {
			$messageFinder->whereOr($whereOr);
          }
		else
		{
			$messageFinder->fromRoom($visitor->siropuChatGetRoomIds());
		}

		if ($settings['hide_bot'] && empty($params['roomId']))
		{
			$messageFinder->notFromType('bot');
		}

		if (!$settings['show_ignored'])
          {
               $messageFinder->notFromIgnoredUsers();
          }

          $messages = $messageFinder->fetch()->filter(function(\Siropu\Chat\Entity\Message $message)
		{
			return ($message->canView());
		});

          $userUpdateInterval = $this->isRoomChannel() ? 30 : 60;
          $userLastUpdate     = $this->filter('user_last_update', 'uint') ?: \XF::$time - $userUpdateInterval;

          $action = $params['action'];

		if (!$settings['hide_chatters']
               && (!empty($params['inactiveRoom']) || ($action != 'submit' && \XF::$time - $userLastUpdate >= $userUpdateInterval)))
		{
               if ($hideTabs)
               {
                    $userRoomIds = [$inRoomId];
               }

			$users = $this->repository('Siropu\Chat:User')->findActiveUsersForList($userRoomIds);

               $params['updateUsers'] = true;
		}
		else
		{
			$users = [];
		}

		$messageSorterService = $this->service('Siropu\Chat:Message\Sorter');
		$messageSorterService->prepareForDisplay($messages);

          $viewParams = [
               'users'       => $this->getUserRepo()->groupUsersByRoom($users),
			'userCount'   => $this->getUserRepo()->getUserCount($users),
			'messages'    => $messageSorterService->getGroupedMessages(),
			'lastMessage' => $messageSorterService->getLastMessage(),
               'lastRoomIds' => $messageSorterService->getLastIds(),
			'playSound'   => $messageSorterService->getPlaySound(),
               'hasImages'   => $messageSorterService->getHasImages(),
               'isSelf'      => $messageSorterService->getIsSelf(),
               'params'      => $params
          ];

		if ($action == 'update' && !$hideTabs)
		{
			$viewParams = array_merge($viewParams, $this->getConvData($params));
		}
          else if ($action == 'join')
		{
               $userIds = $this->getUserRepo()->getUserIdsByRoom($users);

               if (isset($userIds[$params['roomId']]))
               {
                    $viewParams['params']['userIds'] = $userIds[$params['roomId']];
               }
          }

          return $this->view('Siropu\Chat:Chat', '', $viewParams);
	}
	public function getConvUpdates(array $params = [])
	{
		return $this->view('Siropu\Chat:Chat', '', $this->getConvData($params));
	}
	public function getConvData(array $params)
	{
		$visitor = \XF::visitor();
		$options = \XF::options();

		if (!($options->siropuChatPrivateConversations
               && $visitor->canChatInPrivateSiropuChat()
               && $visitor->hasConversationsSiropuChat()))
          {
			return [];
		}

          $convUpdateInterval = $this->isConvChannel() ? 30 : 60;
          $convLastUpdate     = $this->filter('conv_last_update', 'uint') ?: \XF::$time - $convUpdateInterval;

          if (\XF::$time - $convLastUpdate >= $convUpdateInterval)
          {
               $contacts = $this->getConversationRepo()->getUserConversations();
               $params['updateConv'] = true;
          }
          else
          {
               $contacts = [];
          }

		$finder = $this->getConversationMessageRepo()
			->findMessages()
			->forConversation($visitor->siropuChatGetConvIds());

		if (!empty($params['insert_id']))
		{
			$finder->where('message_id', '>=', $this->filter('conv_last_id', 'uint') ?: $params['insert_id']);
		}
		else
		{
			$finder->unread();
		}

		$messages = $finder->fetch()->filter(function(\Siropu\Chat\Entity\ConversationMessage $message)
		{
			return ($message->canView());
		});

		$conversationPreparer = $this->service('Siropu\Chat:Conversation\Preparer');
		$conversationPreparer->prepareForDisplay($messages);

          $viewParams = [
               'convContacts'    => $contacts,
			'convMessages'    => $conversationPreparer->getGroupedMessages(),
			'convLastMessage' => $conversationPreparer->getLastMessage(),
			'convUnread'      => $conversationPreparer->getUnread(),
               'hasImages'       => $conversationPreparer->getHasImages(),
               'isSelf'          => $conversationPreparer->getIsSelf(),
			'params'          => $params
          ];

          switch ($options->siropuChatConvTabCount)
          {
               case 'onlineCount':
                    $viewParams['convTabCount'] = $conversationPreparer->getOnlineCount($contacts);
                    break;
               case 'unreadCount':
                    $viewParams['convTabCount'] = $conversationPreparer->getUnreadCount();
                    break;
          }

		return $viewParams;
	}
     public function getChannel()
     {
          return $this->request->getCookie('siropu_chat_channel', 'room');
     }
     public function isRoomChannel()
     {
          return $this->getChannel() == 'room';
     }
     public function isConvChannel()
     {
          return $this->getChannel() == 'conversation';
     }
	protected function getMessageRepo()
	{
		return $this->repository('Siropu\Chat:Message');
	}
	protected function getUserRepo()
	{
		return $this->repository('Siropu\Chat:User');
	}
	protected function getConversationRepo()
	{
		return $this->repository('Siropu\Chat:Conversation');
	}
	protected function getConversationMessageRepo()
	{
		return $this->repository('Siropu\Chat:ConversationMessage');
	}
}
