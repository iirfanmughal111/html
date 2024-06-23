<?php

namespace Siropu\Chat\Service\Conversation;

use XF\Mvc\Reply;

class Manager extends \XF\Service\AbstractService
{
	protected $conversation;
	protected $user;
	protected $message;

	public function __construct(\XF\App $app, \XF\Entity\User $user = null, \Siropu\Chat\Entity\Conversation $conversation = null, $message)
	{
		parent::__construct($app);

		if ($conversation)
		{
			$this->conversation = $conversation;
		}
		else if ($conversation = $this->getConversationRepo()->findConversationWithUser($user->user_id))
		{
			$this->conversation = $conversation;
		}
		else
		{
			$this->conversation = $this->em()->create('Siropu\Chat:Conversation');
		}

		$this->user    = $user;
		$this->message = $message;
	}
	public function startConversation()
	{
		$visitor = \XF::visitor();

		if (!$visitor->canChatInPrivateSiropuChat())
		{
			return new Reply\Error(\XF::phrase('do_not_have_permission'));
		}

		if (!$visitor->canMessageSiropuChatUser($this->user))
		{
			return new Reply\Error(\XF::phrase('siropu_chat_cannot_start_conversation_with_user_x', [
				'user' => $this->user->username
			]));
		}

		if ($this->conversation->isUpdate())
		{
			if ($this->conversation->user_left == $visitor->user_id || $this->conversation->user_left == 0)
			{
				$this->conversation->user_left = 0;
				$this->conversation->saveIfChanged();

				$visitor->siropuChatJoinConversation($this->conversation->conversation_id, $this->conversation->Contact->user_id);
				$visitor->save();
			}
		}
		else
		{
			$this->conversation->user_1 = $visitor->user_id;
			$this->conversation->user_2 = $this->user->user_id;
			$this->conversation->save();

			$visitor->siropuChatJoinConversation($this->conversation->conversation_id, $this->user->user_id);
			$visitor->siropu_chat_conv_id = $this->conversation->conversation_id;
			$visitor->save();

			$this->user->siropuChatJoinConversation($this->conversation->conversation_id, $visitor->user_id);
			$this->user->siropu_chat_conv_id = $this->user->siropu_chat_conv_id ?: $this->conversation->conversation_id;
			$this->user->save();

			$alertRepo = $this->app->repository('XF:UserAlert');
			$alertRepo->alert(
				$this->user,
				$visitor->user_id,
				$visitor->username,
				'siropu_chat_conv',
				$this->conversation->conversation_id,
				'new'
			);
		}

		$message = $this->em()->create('Siropu\Chat:ConversationMessage');
		$message->message_conversation_id = $this->conversation->conversation_id;
		$message->message_text = $this->message;
		$message->save();

		$messages = $this->getConversationMessageRepo()
			->findMessages()
			->forConversation($this->conversation->conversation_id)
			->fetch();

		if (!$visitor->getSiropuChatSetting('inverse'))
		{
			$messages = $messages->reverse();
		}

		$viewParams = [
			'contacts' => $this->getConversationRepo()->getUserConversations(),
			'messages' => $messages,
			'convId'   => $this->conversation->conversation_id
		];

		return new Reply\View('Siropu\Chat:Conversation\Start', '', $viewParams);
	}
	public function leaveConversation($reply = true)
	{
		$visitor = \XF::visitor();

		if (!$this->conversation->isActive())
		{
			return;
		}

		if ($this->conversation->user_left || !$this->conversation->Contact)
		{
			$this->conversation->delete();
		}
		else
		{
			$this->conversation->user_left = $visitor->user_id;
			$this->conversation->save();

			$message = $this->em()->create('Siropu\Chat:ConversationMessage');
			$message->message_conversation_id = $this->conversation->conversation_id;
			$message->message_text = \XF::phrase('siropu_chat_x_has_left_the_conversation', ['user' => $visitor->siropuChatGetUserWrapper()]);
			$message->message_type = 'bot';
			$message->save();
		}

          if ($reply === false)
          {
               return;
          }

          $visitor->siropuChatLeaveConversation($this->conversation->conversation_id);
		$visitor->save();

		$viewParams = [
			'contacts' => $this->getConversationRepo()->getUserConversations(),
			'convId'   => $this->conversation->conversation_id
		];

		return new Reply\View('Siropu\Chat:Conversation\Leave', '', $viewParams);
	}
     public function getConversation()
     {
          return $this->conversation;
     }
	public function getConversationRepo()
	{
		return $this->repository('Siropu\Chat:Conversation');
	}
	public function getConversationMessageRepo()
	{
		return $this->repository('Siropu\Chat:ConversationMessage');
	}
}
