<?php

namespace XenAddons\Showcase\Service\ItemUpdate;

use XenAddons\Showcase\Entity\ItemUpdate;

class Deleter extends \XF\Service\AbstractService
{
	/**
	 * @var ItemUpdate
	 */
	protected $update;

	protected $user;

	protected $alert = false;
	protected $alertReason = '';

	public function __construct(\XF\App $app, ItemUpdate $update)
	{
		parent::__construct($app);
		$this->setUpdate($update);
	}

	public function setUpdate(ItemUpdate $update)
	{
		$this->update = $update;
	}

	public function getUpdate()
	{
		return $this->update;
	}

	public function setUser(\XF\Entity\User $user = null)
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
		$user = $this->user ?: \XF::visitor();

		$result = null;

		$wasVisible = $this->update->update_state == 'visible';

		if ($type == 'soft')
		{
			$result = $this->update->softDelete($reason, $user);
		}
		else
		{
			$result = $this->update->delete();
		}

		if ($result && $wasVisible && $this->alert && $this->update->user_id != $user->user_id)
		{
			/** @var \XenAddons\Showcase\Repository\ItemUpdate $updateRepo */
			$updateRepo = $this->repository('XenAddons\Showcase:ItemUpdate');
			$updateRepo->sendModeratorActionAlert($this->update, 'delete', $this->alertReason);
		}

		return $result;
	}
}