<?php

namespace Siropu\Chat;

class Data
{
     public function __construct()
     {
          $this->getUserRepo()->joinDefaultRooms();
          $this->getUserRepo()->autoLoginJoinedRooms();
     }
     public function getViewParams(array $extraParams = [])
     {
          $options    = \XF::options();
          $visitor    = \XF::visitor();

          $isChatPage = !empty($extraParams['isChatPage']) ? true : false;
          $isMobile   = \Siropu\Chat\Criteria\Device::isMobile();
          $cssClass   = $this->getCssClass($isChatPage);

          if (!empty($extraParams['messageDisplayLimit']))
          {
               $messageDisplayLimit = $extraParams['messageDisplayLimit'];
          }
          else
          {
               $messageDisplayLimit = $options->siropuChatMessageDisplayLimit;
          }

          $viewParams = [
               'channel'             => $this->getChannel(),
               'cssClass'            => $cssClass,
               'settings'            => $this->getSettings(),
               'roomId'              => $visitor->getLastRoomIdSiropuChat(),
               'convId'              => $visitor->getLastConvIdSiropuChat(),
               'notice'              => $this->getNotice(),
               'ads'                 => $this->getAds(),
               'disabledButtons'     => $this->getDisabledButtons(),
               'messageDisplayLimit' => $messageDisplayLimit,
               'isMobile'            => $isMobile,
               'isResponsive'        => $isMobile || strpos($cssClass, 'Sidebar'),
               'isChatPage'          => $isChatPage,
               'commands'            => [
                    'join'    => $this->getCommandRepo()->getDefaultCommandFromCache('join'),
                    'whisper' => $this->getCommandRepo()->getDefaultCommandFromCache('whisper')
               ],
               'rooms'               => [],
               'users'               => [],
               'userIds'             => [],
               'userCount'           => 0,
               'messages'            => [],
               'lastMessage'         => [],
               'lastRoomIds'         => [],
               'convIds'             => [],
               'convContacts'        => [],
               'convMessages'        => [],
               'convUnread'          => [],
               'convOnline'          => 0,
               'fullPageRoom'        => 0,
          ];

          if (!$options->siropuChatRooms && $options->siropuChatPrivateConversations)
          {
               $viewParams['convOnly'] = true;
          }

          $app = \XF::app();

          $isFullPage   = $app->request()->filter('fullpage', 'bool');
          $routeMatch   = $app->router('public')->routeToController($app->request()->getRoutePath());
          $parameterBag = $routeMatch->getParameterBag();

          if ($isFullPage && $parameterBag->room_id)
          {
               $viewParams['fullPageRoom'] = $parameterBag->room_id;
          }
          else
          {
               $this->setConverationParams($viewParams);
          }

          $this->setRoomParams($viewParams);

          return array_merge($viewParams, $extraParams);
     }
     public function setRoomParams(array &$viewParams)
     {
          $visitor = \XF::visitor();
          $options = \XF::options();

          if (!($options->siropuChatRooms && $visitor->canJoinSiropuChatRooms() && $visitor->hasJoinedRoomsSiropuChat()))
          {
               return;
          }

          $app = \XF::app();

          $messageSorter   = $app->service('Siropu\Chat:Message\Sorter');
          $userRoomIds     = $visitor->siropuChatGetRoomIds();
          $displayLimit    = $viewParams['messageDisplayLimit'];
          $updateUserRooms = false;

          $fullPageRoom    = $viewParams['fullPageRoom'] ?? 0;
          $isRoomEmbed     = false;

          if ($fullPageRoom)
          {
               $userRoomIds = [$fullPageRoom];
          }

          $rooms = $app->finder('Siropu\Chat:Room')
               ->fromRoom($userRoomIds)
               ->visible()
               ->fetch();

          foreach ($userRoomIds AS $roomId)
          {
               if (!isset($rooms[$roomId]) && $visitor->user_id)
               {
                    $visitor->siropuChatLeaveRoom($roomId, false);
                    $updateUserRooms = true;

                    continue;
               }

               $messageFinder = $this->getMessageRepo()
                    ->findMessages()
                    ->fromRoom($roomId)
                    ->limit($displayLimit * 2);

               if (!$visitor->canSanctionSiropuChat())
               {
                    $messageFinder->notIgnored();
               }

               if (!$this->getSettings()['show_ignored'])
               {
                    $messageFinder->notFromIgnoredUsers();
               }

               $roomMessages = $messageFinder->fetch()->filter(function(\Siropu\Chat\Entity\Message $message)
               {
                    return ($message->canView());
               });

               $messageSorter->prepareForDisplay($roomMessages->slice(0, $displayLimit));
          }

          if ($updateUserRooms)
          {
               $visitor->save();
          }

          $users = $this->getUserRepo()->findActiveUsersForList($userRoomIds);

          $lastMessage = $messageSorter->getLastMessage();

          if ($lastMessage && !$lastMessage->isPastJoinTime())
          {
               $lastMessage = [];
          }

          $viewParams['rooms']       = $rooms;
          $viewParams['users']       = $this->getUserRepo()->groupUsersByRoom($users);
          $viewParams['userIds']     = $this->getUserRepo()->getUserIdsByRoom($users);
          $viewParams['userCount']   = $this->getUserRepo()->getUserCount($users);
          $viewParams['messages']    = $messageSorter->getGroupedMessages();
          $viewParams['lastMessage'] = $lastMessage;
          $viewParams['lastRoomIds'] = $messageSorter->getLastIds();
     }
     public function setConverationParams(array &$viewParams)
     {
          $visitor = \XF::visitor();
          $options = \XF::options();

          if (!($options->siropuChatPrivateConversations
               && $visitor->canChatInPrivateSiropuChat()
               && $visitor->hasConversationsSiropuChat()))
          {
               return;
          }

          $conversationsMessages = $this->getConversationMessageRepo()
               ->findMessages()
               ->forConversation($visitor->getLastConvIdSiropuChat())
               ->fetch()
               ->filter(function(\Siropu\Chat\Entity\ConversationMessage $message)
               {
                    return ($message->canView());
               });

          $conversationPreparer = \XF::app()->service('Siropu\Chat:Conversation\Preparer');
          $conversationPreparer->prepareForDisplay($conversationsMessages);

          $messages = $conversationPreparer->getGroupedMessages();
          $conversations = $this->getConversationRepo()->getUserConversations();

          $viewParams['convContacts'] = $conversations;
          $viewParams['convMessages'] = $messages;
          $viewParams['convUnread']   = $conversationPreparer->getUnread();
          $viewParams['convIds']      = $visitor->siropuChatGetConvIds();

          switch ($options->siropuChatConvTabCount)
          {
               case 'onlineCount':
                    $viewParams['convTabCount'] = $conversationPreparer->getOnlineCount($conversations);
                    break;
               case 'unreadCount':
                    $viewParams['convTabCount'] = $conversationPreparer->getUnreadCount();
                    break;
          }
     }
     public function getChannel()
     {
          $visitor = \XF::visitor();
          $options = \XF::options();

          if (!$visitor->user_id)
          {
               return 'room';
          }

          $channel = \XF::app()->request()->getCookie('siropu_chat_channel', 'room');

          if ($channel == 'room' && !($options->siropuChatRooms && $visitor->canJoinSiropuChatRooms()))
          {
               return 'conversation';
          }
          else if ($channel == 'conversation' && !($options->siropuChatPrivateConversations && $visitor->canChatInPrivateSiropuChat()))
          {
               return 'room';
          }

          return $channel;
     }
     public function getSettings()
     {
          $options  = \XF::options();
          $visitor  = \XF::visitor();

          $settings = $visitor->getSiropuChatSettings();

          if (!$visitor->canChangeDisplayModeSiropuChat())
          {
               $settings['display_mode'] = $options->siropuChatDisplayMode;
          }

          return $settings;
     }
     public function getCssClass($isChatPage = false)
     {
          $options     = \XF::options();
          $visitor     = \XF::visitor();
          $settings    = $this->getSettings();
          $displayMode = $visitor->canChangeDisplayModeSiropuChat() ? $settings['display_mode'] : $options->siropuChatDisplayMode;

          if ($isChatPage)
          {
               $displayMode = 'chat_page';
          }

          switch ($displayMode)
          {
               case 'chat_page':
                    $cssClass = 'siropuChatPage';
                    break;
               case 'all_pages':
                    $cssClass = 'siropuChatAllPages';
                    break;
               case 'below_breadcrumb':
                    $cssClass = 'siropuChatBelowBreadcrumb';
                    break;
               case 'above_forum_list':
                    $cssClass = 'siropuChatAboveForumList';
                    break;
               case 'below_forum_list':
                    $cssClass = 'siropuChatBelowForumList';
                    break;
               case 'above_content':
                    $cssClass = 'siropuChatAboveContent';
                    break;
               case 'below_content':
                    $cssClass = 'siropuChatBelowContent';
                    break;
               case 'sidebar_top':
                    $cssClass = 'siropuChatSidebar siropuChatSidebarTop';
                    break;
               case 'sidebar_bottom':
                    $cssClass = 'siropuChatSidebar siropuChatSidebarBottom';
                    break;
               default:
                    $cssClass = 'siropuChatCustom';
                    break;
          }

          if ($settings['maximized'])
          {
               $cssClass .= ' siropuChatMaximized';
          }

          if ($settings['editor_on_top'])
          {
               $cssClass .= ' siropuChatEditorTop';
          }

          if ($settings['hide_chatters'])
          {
               $cssClass .= ' siropuChatHideUserList';
          }

          if (!$options->siropuChatRooms)
          {
               $cssClass .= ' siropuChatNoRooms';
          }

          return $cssClass;
     }
     public function getDisabledBbCodes()
     {
          $options  = \XF::options();
          $disabled = [];

          foreach ($options->siropuChatEnabledBBCodes as $tag => $val)
          {
               if (!$val)
               {
                    $disabled[] = $tag;
               }
          }

          foreach ($options->siropuChatDisallowedCustomBbCodes as $tag)
          {
               $disabled[] = $tag;
          }

          return $disabled;
     }
     public function getDisabledButtons()
     {
          $buttons = ['_align', '_indent', 'xfList', 'undo', 'redo'];

          if ($disabled = $this->getDisabledBbCodes())
          {
               foreach ($disabled as $tag)
               {
                    switch ($tag)
                    {
                         case 'b':
                              $buttons[] = 'bold';
                              break;
                         case 'i':
                              $buttons[] = 'italic';
                              break;
                         case 'u':
                              $buttons[] = 'underline';
                              break;
                         case 's':
                              $buttons[] = 'strikeThrough';
                              break;
                         case 'url':
                              $buttons[] = '_link';
                              break;
                         case 'img':
                              $buttons[] = '_image';
                              break;
                         case 'color':
                              $buttons[] = 'textColor';
                              break;
                         case 'font':
                              $buttons[] = 'fontFamily';
                              break;
                         case 'size':
                              $buttons[] = 'fontSize';
                              break;
                         case 'media':
                              $buttons[] = 'xfMedia';
                              break;
                         case 'quote':
                              $buttons[] = 'xfQuote';
                              break;
                         case 'spoiler':
                              $buttons[] = 'xfSpoiler';
                              break;
                         case 'ispoiler':
                              $buttons[] = 'xfInlineSpoiler';
                              break;
                         case 'code':
                              $buttons[] = 'xfCode';
                              break;
                         case 'icode':
                              $buttons[] = 'xfInlineCode';
                              break;
                         case 'table':
                              $buttons[] = 'insertTable';
                              break;
                         case 'paragraph':
                              $buttons[] = 'paragraphFormat';
                              break;
                         case 'hr':
                              $buttons[] = 'insertHR';
                              break;
                         case 'remove':
                              $buttons[] = 'clearFormatting';
                              break;
                         default:
                              $buttons[] = 'xfCustom_' . $tag;
                              break;
                    }
               }
          }

          $options = \XF::options();

          if ($customButtons = array_filter(array_map('trim', explode(',', $options->siropuChatDisableCustomEditorButtons))))
          {
               foreach ($customButtons as $button)
               {
                    $buttons[] = $button;
               }
          }

          if ($options->siropuChatDisableSmilieButton)
          {
               $buttons[] = 'xfSmilie';
          }

          if (!$options->siropuChatEditorToggleBbcode)
          {
               $buttons[] = 'xfBbCode';
          }

          return $buttons;
     }
     public function getAds()
     {
          $visitor = \XF::visitor();
          $options = \XF::options();

          $ads = [
               'aboveEditor' => '',
               'belowEditor' => ''
          ];

		if (!$visitor->hasPermission('siropuChat', 'viewAds'))
		{
			return $ads;
		}

          if ($aboveEditor = $options->siropuChatAdsAboveEditor)
		{
			shuffle($aboveEditor);
			$ads['aboveEditor'] = $aboveEditor[0];
		}

		if ($belowEditor = $options->siropuChatAdsBelowEditor)
		{
			shuffle($belowEditor);
			$ads['belowEditor'] = $belowEditor[0];
		}

		return $ads;
     }
     public function getNotice(array $notices = [])
	{
          $options = \XF::options();
          $notices = $notices ?: $options->siropuChatNotice;

		if ($notices)
		{
			shuffle($notices);
			return $notices[0];
		}
	}
     /**
	 * @return \Siropu\Chat\Repository\User
	 */
     public function getUserRepo()
     {
          return \XF::app()->repository('Siropu\Chat:User');
     }
     /**
	 * @return \Siropu\Chat\Repository\Room
	 */
     public function getRoomRepo()
     {
          return\XF::app()->repository('Siropu\Chat:Room');
     }
     /**
	 * @return \Siropu\Chat\Repository\Message
	 */
     public function getMessageRepo()
     {
          return \XF::app()->repository('Siropu\Chat:Message');
     }
     /**
	 * @return \Siropu\Chat\Repository\Conversation
	 */
     public function getConversationRepo()
     {
          return \XF::app()->repository('Siropu\Chat:Conversation');
     }
     /**
	 * @return \Siropu\Chat\Repository\ConversationMessage
	 */
     public function getConversationMessageRepo()
     {
          return \XF::app()->repository('Siropu\Chat:ConversationMessage');
     }
     /**
	 * @return \Siropu\Chat\Repository\Command
	 */
     public function getCommandRepo()
     {
          return \XF::app()->repository('Siropu\Chat:Command');
     }
}
