<?php

namespace DBTech\eCommerce\Service\ProductRating;

use DBTech\eCommerce\Entity\ProductRating;

/**
 * Class Delete
 *
 * @package DBTech\eCommerce\Service\ProductRating
 */
class Delete extends \XF\Service\AbstractService
{
	/** @var \DBTech\eCommerce\Entity\ProductRating */
	protected $rating;

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
	 * @param ProductRating $rating
	 */
	public function __construct(\XF\App $app, ProductRating $rating)
	{
		parent::__construct($app);
		$this->rating = $rating;
	}
	
	/**
	 * @return ProductRating
	 */
	public function getRating(): ProductRating
	{
		return $this->rating;
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
		$wasVisible = $this->rating->rating_state == 'visible';

		if ($type == 'soft')
		{
			$result = $this->rating->softDelete($reason, $user);
		}
		else
		{
			$result = $this->rating->delete();
		}

		if ($result && $wasVisible && $this->alert && $this->rating->Product->user_id != $user->user_id)
		{
			/** @var \DBTech\eCommerce\Repository\ProductRating $ratingRepo */
			$ratingRepo = $this->repository('DBTech\eCommerce:ProductRating');
			$ratingRepo->sendModeratorActionAlert($this->rating, 'delete', $this->alertReason);
		}

		return $result;
	}
}