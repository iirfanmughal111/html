<?php

namespace XenAddons\Showcase\Service\ItemUpdateReply;

use XenAddons\Showcase\Entity\ItemUpdateReply;
use XF\Entity\User;

class Deleter extends \XF\Service\AbstractService
{
	/**
	 * @var ItemUpdateReply
	 */
	protected $reply;

	/**
	 * @var User
	 */
	protected $user;

	protected $alert = false;
	protected $alertReason = '';

	public function __construct(\XF\App $app, ItemUpdateReply $reply)
	{
		parent::__construct($app);
		$this->setReply($reply);
		$this->setUser(\XF::visitor());
	}

	protected function setReply(ItemUpdateReply $reply)
	{
		$this->reply = $reply;
	}

	public function getReply()
	{
		return $this->reply;
	}

	protected function setUser(\XF\Entity\User $user)
	{
		$this->user = $user;
	}

	public function getUser()
	{
		return $this->user;
	}

	public function setSendAlert($alert, $reason = null)
	{
		$this->alert = (bool)$alert;
		if ($reason !== null)
		{
			$this->alertReason = $reason;
		}
	}

	public function delete($type, $reason = '')
	{
		$user = $this->user;

		$reply = $this->reply;
		$wasVisible = ($reply->reply_state == 'visible');

		if ($type == 'soft')
		{
			$result = $reply->softDelete($reason, $user);
		}
		else
		{
			$result = $reply->delete();
		}

		if ($result && $wasVisible && $this->alert && $reply->user_id != $user->user_id)
		{
			/** @var \XenAddons\Showcase\Repository\ItemUpdate $updateRepo */
			$updateRepo = $this->repository('XenAddons\Showcase:ItemUpdate');
			$updateRepo->sendReplyModeratorActionAlert($reply, 'delete', $this->alertReason);
		}

		return $result;
	}
}