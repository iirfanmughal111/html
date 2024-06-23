<?php

namespace DBTech\eCommerce\Service\License;

use DBTech\eCommerce\Entity\License;

/**
 * Class Reassign
 *
 * @package DBTech\eCommerce\Service\License
 */
class Reassign extends \XF\Service\AbstractService
{
	/** @var \DBTech\eCommerce\Entity\License */
	protected $license;

	/** @var bool */
	protected $alert = false;
	
	/** @var string */
	protected $alertReason = '';


	/**
	 * Reassign constructor.
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
	 * @return License
	 */
	public function getLicense(): License
	{
		return $this->license;
	}

	/**
	 * @param bool $alert
	 * @param string|null $reason
	 *
	 * @return $this
	 */
	public function setSendAlert(bool $alert, ?string $reason = null): Reassign
	{
		$this->alert = $alert;
		if ($reason !== null)
		{
			$this->alertReason = $reason;
		}

		return $this;
	}
	
	/**
	 * @param \XF\Entity\User $newUser
	 *
	 * @return bool
	 * @throws \LogicException
	 * @throws \Exception
	 * @throws \XF\PrintableException
	 */
	public function reassignTo(\XF\Entity\User $newUser): bool
	{
		$license = $this->license;
		$oldUser = $license->User;
		$reassigned = ($license->user_id != $newUser->user_id);

		$license->user_id = $newUser->user_id;
		$license->username = $newUser->username;
		$license->save();

		if ($reassigned && $this->alert && $license->isVisible())
		{
			if (\XF::visitor()->user_id != $oldUser->user_id)
			{
				/** @var \DBTech\eCommerce\Repository\License $licenseRepo */
				$licenseRepo = $this->repository('DBTech\eCommerce:License');
				$licenseRepo->sendModeratorActionAlert(
					$this->license,
					'reassign_from',
					$this->alertReason,
					['to' => $newUser->username],
					$oldUser
				);
			}

			if (\XF::visitor()->user_id != $newUser->user_id)
			{
				/** @var \DBTech\eCommerce\Repository\License $licenseRepo */
				$licenseRepo = $this->repository('DBTech\eCommerce:License');
				$licenseRepo->sendModeratorActionAlert(
					$this->license,
					'reassign_to',
					$this->alertReason,
					[],
					$newUser
				);
			}
		}

		return $reassigned;
	}
}