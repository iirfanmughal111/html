<?php

namespace DBTech\eCommerce\Sitemap;

use XF\Sitemap\AbstractHandler;
use XF\Sitemap\Entry;

/**
 * Class Product
 *
 * @package DBTech\eCommerce\Sitemap
 */
class Product extends AbstractHandler
{
	/**
	 * @param $start
	 *
	 * @return \XF\Mvc\Entity\AbstractCollection
	 */
	public function getRecords($start): \XF\Mvc\Entity\AbstractCollection
	{
		$user = \XF::visitor();

		$ids = $this->getIds('xf_dbtech_ecommerce_product', 'product_id', $start);

		$finder = $this->app->finder('DBTech\eCommerce:Product');
		return $finder
			->where('product_id', $ids)
			->with(['Permissions|' . $user->permission_combination_id, 'Category', 'Category.Permissions|' . $user->permission_combination_id])
			->order('product_id')
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
		$url = $this->app->router('public')->buildLink('canonical:dbtech-ecommerce', $record);
		return Entry::create($url, [
			'lastmod' => $record->last_update
		]);
	}
	
	/**
	 * @param $record
	 *
	 * @return bool
	 */
	public function isIncluded($record): bool
	{
		/** @var $record \DBTech\eCommerce\Entity\Product */
		if (!$record->isVisible())
		{
			return false;
		}
		if ($record->isAddOn())
		{
			return false;
		}
		return $record->canView();
	}
}