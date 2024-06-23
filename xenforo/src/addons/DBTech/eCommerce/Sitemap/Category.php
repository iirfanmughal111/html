<?php

namespace DBTech\eCommerce\Sitemap;

use XF\Sitemap\AbstractHandler;
use XF\Sitemap\Entry;

/**
 * Class Category
 *
 * @package DBTech\eCommerce\Sitemap
 */
class Category extends AbstractHandler
{
	/**
	 * @param $start
	 *
	 * @return \XF\Mvc\Entity\AbstractCollection
	 */
	public function getRecords($start): \XF\Mvc\Entity\AbstractCollection
	{
		$user = \XF::visitor();

		$ids = $this->getIds('xf_dbtech_ecommerce_category', 'category_id', $start);

		$finder = $this->app->finder('DBTech\eCommerce:Category');
		return $finder
			->where('category_id', $ids)
			->with(['Permissions|' . $user->permission_combination_id])
			->order('category_id')
			->fetch()
			;
	}

	/**
	 * @param $record
	 *
	 * @return Entry
	 */
	public function getEntry($record): Entry
	{
		$url = $this->app->router('public')->buildLink('canonical:dbtech-ecommerce/categories', $record);
		return Entry::create($url);
	}

	/**
	 * @param $record
	 *
	 * @return bool
	 */
	public function isIncluded($record): bool
	{
		/** @var $record \DBTech\eCommerce\Entity\Category */
		return $record->canView();
	}
}