<?php

namespace DBTech\eCommerce\AdminSearch;

use XF\AdminSearch\AbstractPhrased;

/**
 * Class Discount
 *
 * @package DBTech\eCommerce\AdminSearch
 */
class Discount extends AbstractPhrased
{
	/**
	 * @return string
	 */
	protected function getFinderName(): string
	{
		return 'DBTech\eCommerce:Discount';
	}

	/**
	 * @return string
	 */
	protected function getContentIdName(): string
	{
		return 'discount_id';
	}

	/**
	 * @return string
	 */
	protected function getRouteName(): string
	{
		return 'dbtech-ecommerce/discounts/edit';
	}

	/**
	 * @return int
	 */
	public function getDisplayOrder(): int
	{
		return 53;
	}

	/**
	 * @return array|string[]
	 */
	public function getRelatedPhraseGroups(): array
	{
		return ['dbtech_ecommerce_discount_title'];
	}

	/**
	 * @return bool
	 */
	public function isSearchable(): bool
	{
		return \XF::visitor()->hasAdminPermission('dbtechEcomDiscount');
	}
}