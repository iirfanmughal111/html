<?php

namespace Siropu\Chat\Service\Conversation;

use XF\Mvc\Reply;

class Preparer extends \Siropu\Chat\Service\Message\Sorter
{
	protected $field = 'message_conversation_id';
     protected $unreadCount = 0;

	public function getOnlineCount($contacts)
	{
          if (empty($contacts))
          {
               return 0;
          }

		$online = $contacts->filter(function(\Siropu\Chat\Entity\Conversation $conversation)
		{
			return ($conversation->isOnline());
		});

		return $online->count();
	}
	public function getUnread()
	{
		$unreadMessages = $this->messages->filter(function(\Siropu\Chat\Entity\ConversationMessage $message)
		{
			return ($message->isUnread());
		});

          $this->unreadCount = $unreadMessages->count();

		$list = [];

		foreach ($unreadMessages as $message)
		{
			$list[$message->message_conversation_id][] = $message->message_id;

               $this->setHasImages($message->message_text);
		}

		return $list;
	}
     public function getUnreadCount()
	{
          return $this->unreadCount;
     }
}
