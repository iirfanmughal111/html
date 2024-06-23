<?php

namespace DBTech\eCommerce\Repository;

use XF\Mvc\Entity\Finder;
use XF\Mvc\Entity\Repository;

/**
 * Class Discount
 * @package DBTech\eCommerce\Repository
 */
class Discount extends Repository
{
	/**
	 * @param \DBTech\eCommerce\Entity\Discount $discount
	 * @param string $action
	 * @param string $reason
	 * @param array $extra
	 * @param \XF\Entity\User|null $forceUser
	 *
	 * @return bool
	 */
	public function sendModeratorActionAlert(
		\DBTech\eCommerce\Entity\Discount $discount,
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
			'title'               => $discount->title,
			'reason'              => $reason,
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
			"dbt_ecom_discount_{$action}",
			$extra
		);

		return true;
	}

	/**
	 * @return Finder
	 */
	public function findDiscountsForList(): Finder
	{
		return $this->finder('DBTech\eCommerce:Discount')
			->order(['discount_threshold', 'discount_percent'])
			;
	}

	/**
	 * @return Finder
	 */
	public function findDiscountsForCheck(): Finder
	{
		return $this->finder('DBTech\eCommerce:Discount')
			->order([['discount_threshold', 'DESC'], ['discount_percent', 'DESC']])
			;
	}
}