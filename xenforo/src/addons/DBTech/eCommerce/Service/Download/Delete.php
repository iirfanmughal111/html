<?php

namespace DBTech\eCommerce\Service\Download;

use DBTech\eCommerce\Entity\Download;

/**
 * Class Delete
 *
 * @package DBTech\eCommerce\Service\Download
 */
class Delete extends \XF\Service\AbstractService
{
	/** @var \DBTech\eCommerce\Entity\Download */
	protected $download;

	/** @var \XF\Entity\User|null */
	protected $user;
	
	/** @var bool */
	protected $alert = false;
	
	/** @var string */
	protected $alertReason = '';
	
	/**
	 * Delete constructor.
	 *
	 * @param \XF\App $app
	 * @param Download $download
	 */
	public function __construct(\XF\App $app, Download $download)
	{
		parent::__construct($app);
		$this->download = $download;
	}
	
	/**
	 * @return Download
	 */
	public function getDownload(): Download
	{
		return $this->download;
	}

	/**
	 * @param \XF\Entity\User|null $user
	 *
	 * @return $this
	 */
	public function setUser(?\XF\Entity\User $user = null): Delete
	{
		$this->user = $user;

		return $this;
	}
	
	/**
	 * @return null|\XF\Entity\User
	 */
	public function getUser(): ?\XF\Entity\User
	{
		return $this->user;
	}

	/**
	 * @param bool $alert
	 * @param string|null $reason
	 *
	 * @return $this
	 */
	public function setSendAlert(bool $alert, ?string $reason = null): Delete
	{
		$this->alert = $alert;
		if ($reason !== null)
		{
			$this->alertReason = $reason;
		}

		return $this;
	}
	
	/**
	 * @param string $type
	 * @param string $reason
	 *
	 * @return bool
	 * @throws \InvalidArgumentException
	 * @throws \LogicException
	 * @throws \XF\PrintableException
	 * @throws \Exception
	 */
	public function delete(string $type, string $reason = ''): bool
	{
		$user = $this->user ?: \XF::visitor();
		$wasVisible = $this->download->download_state == 'visible';

		if ($type == 'soft')
		{
			$result = $this->download->softDelete($reason, $user);
		}
		else
		{
			$result = $this->download->delete();
		}

		if ($result && $wasVisible && $this->alert && $this->download->Product->user_id != $user->user_id)
		{
			/** @var \DBTech\eCommerce\Repository\Download $downloadRepo */
			$downloadRepo = $this->repository('DBTech\eCommerce:Download');
			$downloadRepo->sendModeratorActionAlert($this->download, 'delete', $this->alertReason);
		}

		return $result;
	}
	
	/**
	 * @return bool
	 * @throws \LogicException
	 * @throws \Exception
	 * @throws \XF\PrintableException
	 */
	public function unDelete(): bool
	{
		$user = $this->user ?: \XF::visitor();
		$wasDeleted = $this->download->download_state == 'deleted';
		
		$result = $this->download->unDelete($user);
		
		if ($result && $wasDeleted && $this->alert && $this->download->Product->user_id != $user->user_id)
		{
			/** @var \DBTech\eCommerce\Repository\Download $downloadRepo */
			$downloadRepo = $this->repository('DBTech\eCommerce:Download');
			$downloadRepo->sendModeratorActionAlert($this->download, 'undelete', $this->alertReason);
		}
		
		return $result;
	}
}