<?php

namespace Siropu\Chat\Service\Message;

class Sorter extends \XF\Service\AbstractService
{
     protected $message;
	protected $groupedMessages = [];
	protected $lastMessages = [];
     protected $lastIds = [];
     protected $isSelf = [];
	protected $inverse = false;
     protected $hasImages = false;
	protected $playSound;
     protected $field = 'message_room_id';

     public function __construct(\XF\App $app)
	{
		parent::__construct($app);

		$this->inverse = \XF::visitor()->getSiropuChatSetting('inverse');
	}
     public function setMessages($messages)
     {
          $this->messages = $messages;
     }
     public function prepareForDisplay($messages)
	{
		$this->messages = $this->inverse ? $messages : $messages->reverse();

		$this->groupByField();
		$this->setLastMessagesAndIds();
	}
     public function groupByField()
     {
          foreach ($this->messages AS $message)
	     {
	          $this->groupedMessages[$message->{$this->field}][] = $message;

			if (!in_array($this->playSound, ['mention', 'whisper']))
			{
				if ($message->isBot())
				{
					$this->playSound = 'bot';
				}
				else if ($message->isError())
				{
					$this->playSound = 'error';
				}
				else if ($message->isMentioned())
				{
					$this->playSound = 'mention';
				}
				else if ($message->isRecipient())
				{
					$this->playSound = 'whisper';
				}
				else
				{
					$this->playSound = 'normal';
				}
			}

               $this->setHasImages($message->message_text);
	     }
     }
     public function setHasImages($message)
     {
          if (!$this->hasImages && strpos($message, '[IMG]') !== false)
          {
               $this->hasImages = true;
          }
     }
     public function setLastMessagesAndIds()
	{
		foreach ($this->groupedMessages as $roomId => $messages)
		{
               $lastMessage = $this->inverse ? current($messages) : end($messages);

			$this->lastMessages[$roomId] = $lastMessage;
               $this->lastIds[$roomId] = $lastMessage->message_id;

               $isSelf = 0;

               foreach ($messages as $message)
               {
                    if ($message->isSelf())
                    {
                         $isSelf++;
                    }
               }

               $this->isSelf[$roomId] = $isSelf == count($messages);
		}
	}
     public function getLastMessage()
	{
		$last = $this->lastMessages;

		usort($last, function($a, $b)
		{
			$_a = $a->message_id;
			$_b = $b->message_id;

			if ($_a == $_b)
			{
				return 0;
			}

			return ($_a < $_b) ? -1 : 1;
		});

		return $last ? end($last) : [];
	}
     public function getGroupedMessages()
	{
		return $this->groupedMessages;
	}
	public function getLastIds()
	{
		return $this->lastIds;
	}
	public function getPlaySound()
	{
		return $this->playSound;
	}
     public function getHasImages()
	{
		return $this->hasImages;
	}
     public function getIsSelf()
	{
          return $this->isSelf;
	}
}
