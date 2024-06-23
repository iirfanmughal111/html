<?php

namespace XenAddons\Showcase\Sitemap;

use XF\Sitemap\AbstractHandler;
use XF\Sitemap\Entry;

class Item extends AbstractHandler
{
	public function getRecords($start)
	{
		$app = $this->app;
		$user = \XF::visitor();

		$ids = $this->getIds('xf_xa_sc_item', 'item_id', $start);

		$finder = $app->finder('XenAddons\Showcase:Item');
		$items = $finder
			->where('item_id', $ids)
			->with(['Category', 'Category.Permissions|' . $user->permission_combination_id])
			->order('item_id')
			->fetch();

		return $items;
	}

	/**
	 * @param $record \XenAddons\Showcase\Entity\Item
	 *
	 * @return Entry
	 */
	public function getEntry($record)
	{
		/** @var \XenAddons\Showcase\Entity\Item $record */
		return Entry::create($record->getContentUrl(true), [
			'lastmod' => $record->last_update
		]);
	}

	public function isIncluded($record)
	{
		/** @var $record \XenAddons\Showcase\Entity\Item */
		if (
			!$record->isVisible() ||
			!$record->isSearchEngineIndexable()
		)
		{
			return false;
		}
		return $record->canView();
	}
}