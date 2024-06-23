<?php

namespace Siropu\Chat\Service\Sanction;

class Manager extends \XF\Service\AbstractService
{
	protected $bypassPermissionCheck = false;
	protected $sanction;
	protected $user;

	public function __construct(\XF\App $app, \XF\Entity\User $user, \Siropu\Chat\Entity\Sanction $sanction = null, array $data = [])
	{
		parent::__construct($app);

		if ($sanction)
		{
			$this->sanction = $sanction;
		}
		else if ($user->isSanctionedSiropuChat() && ($sanction = $user->siropuChatGetRoomSanction($data['room_id'])))
		{
			$this->sanction = $this->em()->find('Siropu\Chat:Sanction', $sanction->sanction_id);
		}
		else
		{
			$this->sanction = $this->em()->create('Siropu\Chat:Sanction');
		}

		$this->setUser($user);
		$this->setData($data);
	}
	public function bypassPermissionCheck()
	{
		$this->bypassPermissionCheck = true;
	}
	public function setUser(\XF\Entity\User $user = null)
	{
		$this->user = $user;
	}
	public function setData(array $data = [])
	{
		$this->sanction->sanction_user_id = $this->user->user_id;

		if (isset($data['room_id']))
		{
			$this->sanction->sanction_room_id = $data['room_id'];
		}

		if (isset($data['end_date']))
		{
			$this->sanction->sanction_end = $data['end_date'];
		}

		if (isset($data['reason']))
		{
			$this->sanction->sanction_reason = $data['reason'];
		}
	}
	public function applySanction($type, &$error = null)
	{
		$visitor = \XF::visitor();

		if (!$this->bypassPermissionCheck & !$visitor->canSanctionSiropuChatUser($this->user))
		{
			$error = \XF::phrase('siropu_chat_user_cannot_be_sanctioned', ['user' => $this->user->username]);
			return false;
		}

		$this->sanction->sanction_type = $type;
		$this->sanction->save();

		switch ($type)
		{
			case 'kick':
			case 'ban':
				if ($this->sanction->sanction_room_id == 0)
				{
					$this->user->siropuChatLogout();
				}
				else
				{
					$this->user->siropuChatLeaveRoom($this->sanction->sanction_room_id);
				}

                    if ($this->user->isInShoutboxModeSiropuChat())
                    {
                         $this->user->siropu_chat_last_activity = -1;
                    }
			break;
		}

		$this->user->siropu_chat_is_sanctioned = $this->sanction->getTypeNumber();
		$this->user->save();

		$this->postNotification();

		return true;
	}
	public function liftSanction()
	{
		$this->sanction->delete();

		if (!$this->user->siropuChatGetUserSanctions()->total())
		{
			$this->user->siropu_chat_is_sanctioned = 0;
			$this->user->save();
		}

		$this->postNotification("un{$this->sanction->sanction_type}");
	}
	private function postNotification($type = null)
	{
		$notifier = \XF::service('Siropu\Chat:Room\Notifier', $this->user, $this->sanction->sanction_room_id, [
               'until'  => $this->sanction->sanction_end,
               'reason' => $this->sanction->sanction_reason
          ]);
		$notifier->notify($type ?: $this->sanction->sanction_type);
	}
}
