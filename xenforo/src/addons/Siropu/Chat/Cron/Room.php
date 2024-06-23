<?php

namespace Siropu\Chat\Cron;

class Room
{
	public static function pruneMessages()
	{
		$app = \XF::app();
		$messageRepo = $app->repository('Siropu\Chat:Message');

		foreach ($app->repository('Siropu\Chat:Room')->findAutoPrune() as $room)
		{
			$lastPrune = round((\XF::$time - $room->room_last_prune) / 3600);

			if ($lastPrune >= $room->room_prune && $messageRepo->findRoomMessages($room->room_id)->notFromType('prune')->total())
			{
				$messageRepo->pruneRoomMessages($room->room_id);

				$room->room_last_prune = \XF::$time;
				$room->save();

				$notifier = \XF::app()->service('Siropu\Chat:Room\Notifier', null, $room->room_id);
		          $notifier->notify('prune');
			}
		}
	}
	public static function updateUserCount()
	{
		$app = \XF::app();

		if (\XF::options()->siropuChatRoomsOrder != 'users')
		{
			return;
		}

		$userRepo = $app->repository('Siropu\Chat:User');
		$userList = $userRepo->groupUsersByRoom($userRepo->findActiveUsers());

		foreach ($userList as $roomId => $users)
		{
			if ($room = $app->em()->find('Siropu\Chat:Room', $roomId))
			{
				$room->room_user_count = count($users);
				$room->save();
			}
		}

		$app->repository('Siropu\Chat:Room')->resetInactiveRoomsUserCount(array_keys($userList));
     }
	public static function cleanActionLog() {}
     public static function updateLastActivity() {}
     public static function deleteInactiveRooms() {}
}
