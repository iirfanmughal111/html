<?php

namespace DBTech\eCommerce\AdminSearch;

use XF\AdminSearch\AbstractPhrased;

/**
 * Class Coupon
 *
 * @package DBTech\eCommerce\AdminSearch
 */
class Coupon extends AbstractPhrased
{
	/**
	 * @return string
	 */
	protected function getFinderName(): string
	{
		return 'DBTech\eCommerce:Coupon';
	}

	/**
	 * @return string
	 */
	protected function getContentIdName(): string
	{
		return 'coupon_id';
	}

	/**
	 * @return string
	 */
	protected function getRouteName(): string
	{
		return 'dbtech-ecommerce/coupons/edit';
	}

	/**
	 * @return int
	 */
	public function getDisplayOrder(): int
	{
		return 52;
	}

	/**
	 * @return array|string[]
	 */
	public function getRelatedPhraseGroups(): array
	{
		return ['dbtech_ecommerce_coupon_title'];
	}

	/**
	 * @return bool
	 */
	public function isSearchable(): bool
	{
		return \XF::visitor()->hasAdminPermission('dbtechEcomCoupon');
	}
}