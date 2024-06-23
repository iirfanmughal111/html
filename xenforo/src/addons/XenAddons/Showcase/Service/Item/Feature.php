<?php

namespace XenAddons\Showcase\Service\Item;

use XenAddons\Showcase\Entity\Item;

class Feature extends \XF\Service\AbstractService
{
	/**
	 * @var \XenAddons\Showcase\Entity\Item
	 */
	protected $item;

	public function __construct(\XF\App $app, Item $item)
	{
		parent::__construct($app);
		$this->item = $item;
	}

	public function getItem()
	{
		return $this->item;
	}

	public function feature()
	{
		$db = $this->db();
		$db->beginTransaction();

		$affected = $db->insert('xf_xa_sc_item_feature', [
			'item_id' => $this->item->item_id,
			'feature_date' => \XF::$time
		], false, 'feature_date = VALUES(feature_date)');

		if ($affected == 1)
		{
			// insert
			$this->onNewFeature();
		}

		$db->commit();
	}

	protected function onNewFeature()
	{
		$item = $this->item;
		$category = $this->item->Category;
		
		$item->last_feature_date = \XF::$time;
		$item->save();
		
		if ($item->isVisible())
		{
			if ($category)
			{
				$category->featured_count++;
				$category->save();
			}
		}

		$this->app->logger()->logModeratorAction('sc_item', $item, 'feature');
	}

	public function unfeature()
	{
		$db = $this->db();
		$db->beginTransaction();

		$affected = $db->delete('xf_xa_sc_item_feature', 'item_id = ?', $this->item->item_id);
		if ($affected)
		{
			$this->onUnfeature();
		}

		$db->commit();
	}

	protected function onUnfeature()
	{
		if ($this->item->isVisible())
		{
			$category = $this->item->Category;
			if ($category)
			{
				$category->featured_count--;
				$category->save();
			}
		}

		$this->app->logger()->logModeratorAction('sc_item', $this->item, 'unfeature');
	}
}