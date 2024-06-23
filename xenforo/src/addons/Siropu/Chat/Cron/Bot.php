<?php

namespace Siropu\Chat\Cron;

class Bot
{
	public static function postBotMessages()
	{
		$app = \XF::app();

		$botMessageRepo = $app->repository('Siropu\Chat:BotMessage');

		if (!\XF::options()->siropuChatActiveBotMessageCount)
		{
			return;
		}

		$roomList = $app->repository('Siropu\Chat:Room')
			->findRooms()
			->fetch()
			->pluckNamed('room_id');

		$current = new \DateTime('now', new \DateTimeZone(\XF::options()->guestTimeZone));

		foreach ($botMessageRepo->findBotMessagesForList()->isEnabled()->fetch() as $botMessage)
		{
			$runTime = $botMessage->run_time;
			$nextRun = $botMessage->next_run;

			if ($runTime != $current->format('Y-m-d H:i'))
			{
				continue;
			}

			$rooms = $botMessage->message_rooms ?: $roomList;

			foreach ($rooms as $roomId)
			{
				$message = $app->em()->create('Siropu\Chat:Message');
				$message->message_user_id = 0;
				$message->message_username = '';
				$message->message_room_id = $roomId;
				$message->message_bot_name = $botMessage->message_bot_name;
				$message->message_text = $botMessage->message_text;
				$message->message_type = 'bot';
				$message->save();
			}

			if ($nextRun == $runTime)
			{
				$botMessage->message_enabled = 0;
				$botMessage->save();
			}
		}
     }
}
