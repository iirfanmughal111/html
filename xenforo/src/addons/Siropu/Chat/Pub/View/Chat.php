<?php

namespace Siropu\Chat\Pub\View;

class Chat extends \XF\Mvc\View
{
     public function renderHtml()
	{
          $this->response->removeHeader('X-Frame-Options');
     }
	public function renderJson()
	{
		$visitor     = \XF::visitor();
          $options     = \XF::options();

		$params      = $this->getParams();

		$rooms       = [];
		$joinedRooms = $visitor->siropuChatGetRoomIds();

		$class       = \XF::app()->extendClass('Siropu\Chat\Data');
		$chatData    = new $class();

		$templater   = \XF::app()->templater();

          $userRepo    = \XF::repository('Siropu\Chat:User');
          $convRepo    = \XF::repository('Siropu\Chat:Conversation');

          $settings    = $visitor->getSiropuChatSettings();

          $isChatPage  = \XF::app()->request()->filter('is_chat_page', 'bool');

		foreach ($joinedRooms AS $roomId)
		{
               $rM = !empty($params['messages'][$roomId]) ? $params['messages'][$roomId] : null;
               $rU = !empty($params['users'][$roomId]) ? $params['users'][$roomId] : [];

			$rooms[$roomId] = [
				'messages'  => $rM ? $templater->renderMacro('public:siropu_chat_message_list', 'room', ['messages' => $rM]) : '',
				'users'     => $rU ? $userRepo->getUsersData($rU, $roomId) : [],
				'userCount' => count($rU)
			];
		}

		$lastRow     = [];
          $convLastRow = [];

		if ($visitor->hasDesktopNotifications() || $settings['display_mode'] == 'all_pages' && !$isChatPage)
		{
               if (!empty($params['lastMessage']))
               {
                    $lm = $params['lastMessage'];

                    if ($lm->isPastJoinTime())
                    {
                         $avatar = $lm->User ? $lm->User->getAvatarUrl('l', null, true) : '';

          			$lastRow = [
          				'id'      => $lm->message_id,
          				'roomId'  => $lm->message_room_id,
          				'type'    => str_replace('chat', 'normal', $lm->message_type),
          				'message' => $templater->renderMacro('public:siropu_chat_room_message_helper', 'message_content', ['message' => $lm, 'lastRow' => true]) . $templater->fn('date_dynamic', [$lm->message_date, ['class' => 'siropuChatDateTime']]),
          				'text'    => \XF::app()->stringFormatter()->stripBbCode($lm->message_text),
          				'avatar'  => $avatar
          			];
                    }
               }

               if (!empty($params['convLastMessage']))
     		{
     			$lm     = $params['convLastMessage'];
     			$avatar = $lm->User ? $lm->User->getAvatarUrl('l', null, true) : '';

     			$convLastRow = [
     				'id'     => $lm->message_id,
     				'convId' => $lm->message_conversation_id,
     				'type'   => 'private',
     				'text'   => \XF::app()->stringFormatter()->stripBbCode($lm->message_text),
     				'avatar' => $avatar
     			];
     		}
		}

		$convContacts  = [];
		$convMessages  = [];

		if (!empty($params['convContacts']))
		{
			$convContacts = $convRepo->getConversationData($params['convContacts']);
		}

		if (!empty($params['convMessages']))
		{
			foreach ($params['convMessages'] AS $convId => $messages)
			{
				$convMessages[$convId] = $templater->renderMacro('public:siropu_chat_message_list', 'conversation', [
                         'messages' => $messages]);
			}
		}

		$noticeHtml = '';

		if ($notice = $chatData->getNotice())
		{
			$noticeHtml = $templater->renderMacro('public:siropu_chat_notice_macros', 'notice', ['notice' => $notice]);
		}

		if ($chatData->getChannel() == 'conversation')
		{
			$convLastActive = \XF::app()->request()->filter('conv_last_active', 'uint');
		}
		else
		{
			$convLastActive = false;
		}

		return [
			'active'        => $visitor->isActiveSiropuChat($convLastActive),
			'rooms'         => $rooms,
			'joinedRooms'   => $joinedRooms ?: 0,
			'lastRow'       => $lastRow,
			'lastId'        => !empty($params['lastRoomIds']) ? $params['lastRoomIds'] : [],
			'userCount'     => !empty($params['userCount']) ? $params['userCount'] : 0,
			'playSound'     => !empty($params['playSound']) ? $params['playSound'] : '',
               'hasImages'     => !empty($params['hasImages']) ? true : false,
               'isSelf'        => !empty($params['isSelf']) ? $params['isSelf'] : [],
			'convContacts'  => $convContacts,
			'convMessages'  => $convMessages,
			'convLastRow'   => $convLastRow,
			'convUnread'    => !empty($params['convUnread']) ? $params['convUnread'] : '',
               'convTabCount'  => !empty($params['convTabCount']) ? $params['convTabCount'] : 0,
			'convPlaySound' => !empty($convMessages) ? 'private' : '',
			'actions'       => \Siropu\Chat\Util\Action::getData(true),
			'notice'        => $noticeHtml,
			'serverTime'    => \XF::$time
		] + (isset($params['params']) ? $params['params'] : []);
	}
}
