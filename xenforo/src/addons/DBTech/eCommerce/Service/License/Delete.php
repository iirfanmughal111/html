<?php

namespace DBTech\eCommerce\Service\License;

use DBTech\eCommerce\Entity\License;

/**
 * Class Delete
 *
 * @package DBTech\eCommerce\Service\License
 */
class Delete extends \XF\Service\AbstractService
{
	/** @var \DBTech\eCommerce\Entity\License */
	protected $license;

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
	 * @param \DBTech\eCommerce\Entity\License $license
	 */
	public function __construct(\XF\App $app, License $license)
	{
		parent::__construct($app);
		$this->license = $license;
	}

	/**
	 * @return \DBTech\eCommerce\Entity\License
	 */
	public function getLicense(): License
	{
		return $this->license;
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
	 * @throws \Exception
	 * @throws \XF\PrintableException
	 */
	public function delete(string $type, string $reason = ''): bool
	{
		$user = $this->user ?: \XF::visitor();
		$wasVisible = $this->license->isVisible();
		
		if ($type == 'soft')
		{
			$result = $this->license->softDelete($reason, $user);
		}
		else
		{
			$result = $this->license->delete();
		}
		
		if ($result && $wasVisible && $this->alert && $this->license->user_id != $user->user_id)
		{
			/** @var \DBTech\eCommerce\Repository\License $licenseRepo */
			$licenseRepo = $this->repository('DBTech\eCommerce:License');
			$licenseRepo->sendModeratorActionAlert($this->license, 'delete', $this->alertReason);
		}
		
		return $result;
	}
	
	/**
	 * @throws \LogicException
	 * @throws \Exception
	 * @throws \XF\PrintableException
	 */
	public function unDelete(): bool
	{
		$user = $this->user ?: \XF::visitor();
		$wasDeleted = $this->license->license_state == 'deleted';
		
		$result = $this->license->unDelete($user);
		
		if ($result && $wasDeleted && $this->alert && $this->license->user_id != $user->user_id)
		{
			/** @var \DBTech\eCommerce\Repository\License $licenseRepo */
			$licenseRepo = $this->repository('DBTech\eCommerce:License');
			$licenseRepo->sendModeratorActionAlert($this->license, 'undelete', $this->alertReason);
		}
		return $result;
	}
}