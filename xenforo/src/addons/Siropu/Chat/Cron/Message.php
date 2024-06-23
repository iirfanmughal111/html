<?php

namespace Siropu\Chat\Cron;

class Message
{
	public static function deleteOlderMessages()
	{
		if ($timeFrame = \XF::options()->siropuChatDeleteMessagesOlderThan)
		{
			\XF::app()->repository('Siropu\Chat:Message')->deleteMessagesOlderThan(strtotime("-$timeFrame Days"));
		}
     }
	public static function deleteIgnoredMessages()
	{
		\XF::app()->repository('Siropu\Chat:Message')->deleteIgnoredMessages();
     }
}
