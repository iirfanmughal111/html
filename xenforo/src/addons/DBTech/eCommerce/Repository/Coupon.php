<?php

namespace DBTech\eCommerce\Repository;

use XF\Mvc\Entity\Repository;

/**
 * Class Coupon
 * @package DBTech\eCommerce\Repository
 */
class Coupon extends Repository
{
	/**
	 * @param \DBTech\eCommerce\Entity\Coupon $coupon
	 * @param string $action
	 * @param string $reason
	 * @param array $extra
	 * @param \XF\Entity\User|null $forceUser
	 *
	 * @return bool
	 */
	public function sendModeratorActionAlert(
		\DBTech\eCommerce\Entity\Coupon $coupon,
		string $action,
		string $reason = '',
		array $extra = [],
		?\XF\Entity\User $forceUser = null
	): bool {
		if (!$forceUser)
		{
			return false;
		}

		$extra = array_merge([
			'title' => $coupon->title,
			'reason' => $reason,
			'depends_on_addon_id' => 'DBTech/eCommerce',
		], $extra);

		/** @var \XF\Repository\UserAlert $alertRepo */
		$alertRepo = $this->repository('XF:UserAlert');
		$alertRepo->alert(
			$forceUser,
			0,
			'',
			'user',
			$forceUser->user_id,
			"dbt_ecom_coupon_{$action}",
			$extra
		);

		return true;
	}

	/**
	 * @return \DBTech\eCommerce\Finder\Coupon|\XF\Mvc\Entity\Finder
	 */
	public function findCouponsForList()
	{
		return $this->finder('DBTech\eCommerce:Coupon')
			->order([['expiry_date', 'DESC'], ['start_date', 'DESC']], 'DESC')
			;
	}

	/**
	 * @return \DBTech\eCommerce\Finder\Coupon|\XF\Mvc\Entity\Finder
	 */
	public function findEntriesForPermissionList()
	{
		return $this->findCouponsForList();
	}
}