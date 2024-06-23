<?php

namespace Siropu\Chat\Service\Room;

class Notifier extends \XF\Service\AbstractService
{
	protected $user;
	protected $roomId;
	protected $params;

	public function __construct(\XF\App $app, \XF\Entity\User $user = null, $roomId, array $params = [])
	{
		parent::__construct($app);

		$this->user   = $user;
		$this->params = $params;
		$this->roomId = $roomId;
	}
	public function notify($action)
	{
		$visitor = \XF::visitor();
          $options = \XF::options();

		if (empty($options->siropuChatRoomNotifications[$action]) && !in_array($action, ['prune', 'prune_all']))
		{
			return;
		}

          if (in_array($action, ['join', 'leave']) && $this->user->hasPermission('siropuChat', 'silentJoinLeaveRooms'))
          {
               return;
          }

		$phraseParms = [
			'user'      => $this->user ? new \XF\PreEscaped($this->user->siropuChatGetUserWrapper()) : '',
			'moderator' => new \XF\PreEscaped($visitor->siropuChatGetUserWrapper())
		];

		$leaveMessage   = !empty($this->params['message']) ? $this->params['message'] : null;
		$sanctionEnd    = !empty($this->params['until']) ? $this->params['until'] : null;
		$sanctionReason = !empty($this->params['reason']) ? $this->params['reason'] : null;

		if ($leaveMessage)
		{
			$phraseParms['message'] = new \XF\PreEscaped($leaveMessage);
		}
		if ($sanctionEnd)
		{
			$phraseParms['date'] = \XF::language()->dateTime($sanctionEnd);
		}
		if ($sanctionReason)
		{
			$phraseParms['reason'] = new \XF\PreEscaped($sanctionReason);
		}

		$message = $this->app->service('Siropu\Chat:Message\Creator', $this->user, $this->roomId);

		switch ($action)
		{
			case 'join':
				if ($this->user->user_id)
				{
					$text = \XF::phrase('siropu_chat_x_has_joined_the_room', $phraseParms);
				}
				else
				{
					$text = \XF::phrase('siropu_chat_guest_x_has_joined_the_room', $phraseParms);
				}
				break;
			case 'leave':
				if ($leaveMessage)
				{
                         if ($this->user->user_id)
     				{
                              $text = \XF::phrase('siropu_chat_x_has_left_the_room_message', $phraseParms);
                         }
                         else
                         {
                              $text = \XF::phrase('siropu_chat_guest_x_has_left_the_room_message', $phraseParms);
                         }
				}
				else
                    {
                         if ($this->user->user_id)
     				{
                              $text = \XF::phrase('siropu_chat_x_has_left_the_room', $phraseParms);
                         }
                         else
                         {
                              $text = \XF::phrase('siropu_chat_guest_x_has_left_the_room', $phraseParms);
                         }
				}
				break;
			case 'mute':
				if ($sanctionEnd)
				{
					if ($sanctionReason)
					{
						$text = \XF::phrase('siropu_chat_x_has_been_muted_by_x_until_x_for_reason_x', $phraseParms);
					}
					else
					{
						$text = \XF::phrase('siropu_chat_x_has_been_muted_by_x_until_x', $phraseParms);
					}
				}
				else
				{
					if ($sanctionReason)
					{
						$text = \XF::phrase('siropu_chat_x_has_been_muted_by_x_permanently_for_reason_x', $phraseParms);
					}
					else
					{
						$text = \XF::phrase('siropu_chat_x_has_been_muted_by_x_permanently', $phraseParms);
					}
				}
				break;
			case 'unmute':
				$text = \XF::phrase('siropu_chat_x_has_been_unmuted_by_x', $phraseParms);
				break;
			case 'kick':
			if ($sanctionEnd)
			{
				if ($sanctionReason)
				{
					$text = \XF::phrase('siropu_chat_x_has_been_kicked_by_x_until_x_for_reason_x', $phraseParms);
				}
				else
				{
					$text = \XF::phrase('siropu_chat_x_has_been_kicked_by_x_until_x', $phraseParms);
				}
			}
			else
			{
				if ($sanctionReason)
				{
					$text = \XF::phrase('siropu_chat_x_has_been_kicked_by_x_permanently_for_reason_x', $phraseParms);
				}
				else
				{
					$text = \XF::phrase('siropu_chat_x_has_been_kicked_by_x_permanently', $phraseParms);
				}
			}
				break;
			case 'unkick':
				$text = \XF::phrase('siropu_chat_x_has_been_unkicked_by_x', $phraseParms);
				break;
			case 'ban':
			if ($sanctionEnd)
			{
				if ($sanctionReason)
				{
					$text = \XF::phrase('siropu_chat_x_has_been_banned_by_x_until_x_for_reason_x', $phraseParms);
				}
				else
				{
					$text = \XF::phrase('siropu_chat_x_has_been_banned_by_x_until_x', $phraseParms);
				}
			}
			else
			{
				if ($sanctionReason)
				{
					$text = \XF::phrase('siropu_chat_x_has_been_banned_by_x_permanently_for_reason_x', $phraseParms);
				}
				else
				{
					$text = \XF::phrase('siropu_chat_x_has_been_banned_by_x_permanently', $phraseParms);
				}
			}
				break;
			case 'unban':
				$text = \XF::phrase('siropu_chat_x_has_been_unbanned_by_x', $phraseParms);
				break;
			case 'prune':
				$text = \XF::phrase('siropu_chat_room_has_been_pruned');
				$message->setType('prune');
				break;
			case 'prune_all':
				$text = \XF::phrase('siropu_chat_x_has_deleted_all_the_messages', $phraseParms);
				$message->setType('prune');
				break;
		}

		$message->setText($text);
		$message->save();
	}
}
