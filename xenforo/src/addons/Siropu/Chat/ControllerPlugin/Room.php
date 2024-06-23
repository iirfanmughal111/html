<?php

namespace Siropu\Chat\ControllerPlugin;

class Room extends \XF\ControllerPlugin\AbstractPlugin
{
	public function checkPermissionsAndJoin(\Siropu\Chat\Entity\Room $room)
	{
		$visitor = \XF::visitor();
          $options = \XF::options();

          $canJoin = $room->canJoin($this->filter('password', 'str'), true, $message);

          if ($message)
          {
               return $this->message($message);
          }

		if (!$canJoin)
          {
			return $this->message(\XF::phrase('siropu_chat_room_permission_denied'));
          }

		if (!$visitor->canJoinMoreSiropuChatRooms($error))
		{
               if ($options->siropuChatRoomJoinLimitAction == 'message')
               {
                    return $this->message($error);
               }
               else
               {
                    $joinedRooms = $visitor->siropu_chat_rooms;
                    asort($joinedRooms);

                    $visitor->siropuChatLeaveRoom(current(array_keys($joinedRooms)));
               }
		}

          if ($notice = $room->isSanctionNotice())
          {
			return $this->message($notice);
          }

          if ($room->isLocked($error) && !$visitor->canJoinAnyRoomSiropuChat())
          {
               return $this->message($error);
          }

          if ($room->isJoined())
          {
               return $this->message(\XF::phrase('siropu_chat_room_already_joined'));
          }

		return $this->joinRoom($room, $visitor);
	}
	public function joinRoom(\Siropu\Chat\Entity\Room $room, \XF\Entity\User $visitor = null)
	{
		$visitor = $visitor ?: \XF::visitor();
          $visitor->siropuChatUpdateRooms($room->room_id);
          $visitor->siropuChatSetActiveRoom($room->room_id);
          $visitor->siropuChatSetLastActivity();
          $visitor->save();

          $templater = \XF::app()->templater();
          $templater->addDefaultParam('xf', \XF::app()->getGlobalTemplateData());

          return $this->controller->plugin('Siropu\Chat:Update')->getUpdates([
               'action'   => 'join',
               'roomId'   => $room->room_id,
               'roomName' => $room->room_name,
               'roomTab'  => $templater->renderTemplate('public:siropu_chat_room_tab', ['room' => $room])
          ]);
	}
}
