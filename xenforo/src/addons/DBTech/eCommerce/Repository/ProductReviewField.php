<?php

namespace DBTech\eCommerce\Repository;

use XF\Repository\AbstractField;

/**
 * Class ProductReviewField
 *
 * @package DBTech\eCommerce\Repository
 */
class ProductReviewField extends AbstractField
{
	/**
	 * @return string
	 */
	protected function getRegistryKey(): string
	{
		return 'dbtechEcommerceReviews';
	}

	/**
	 * @return string
	 */
	protected function getClassIdentifier(): string
	{
		return 'DBTech\eCommerce:ProductReviewField';
	}

	/**
	 * @return array
	 */
	public function getDisplayGroups(): array
	{
		return [
			'above_review' => \XF::phrase('dbtech_ecommerce_above_review'),
			'below_review' => \XF::phrase('dbtech_ecommerce_below_review')
		];
	}
}