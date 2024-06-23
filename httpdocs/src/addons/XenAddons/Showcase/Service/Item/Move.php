<?php

namespace XenAddons\Showcase\Service\Item;

use XenAddons\Showcase\Entity\Item;

class Move extends \XF\Service\AbstractService
{
	/**
	 * @var \XenAddons\Showcase\Entity\Item
	 */
	protected $item;

	protected $alert = false;
	protected $alertReason = '';

	protected $notifyWatchers = false;

	protected $prefixId = null;

	protected $extraSetup = [];

	public function __construct(\XF\App $app, Item $item)
	{
		parent::__construct($app);
		$this->item = $item;
	}

	public function getItem()
	{
		return $this->item;
	}

	public function setSendAlert($alert, $reason = null)
	{
		$this->alert = (bool)$alert;
		if ($reason !== null)
		{
			$this->alertReason = $reason;
		}
	}

	public function setPrefix($prefixId)
	{
		$this->prefixId = ($prefixId === null ? $prefixId : intval($prefixId));
	}

	public function setNotifyWatchers($value = true)
	{
		$this->notifyWatchers = (bool)$value;
	}

	public function addExtraSetup(callable $extra)
	{
		$this->extraSetup[] = $extra;
	}

	public function move(\XenAddons\Showcase\Entity\Category $category)
	{
		$user = \XF::visitor();

		$item = $this->item;
		$oldCategory = $item->Category;

		$moved = ($item->category_id != $category->category_id);

		foreach ($this->extraSetup AS $extra)
		{
			call_user_func($extra, $item, $category);
		}

		$item->category_id = $category->category_id;
		if ($this->prefixId !== null)
		{
			$item->prefix_id = $this->prefixId;
		}

		if (!$item->preSave())
		{
			throw new \XF\PrintableException($item->getErrors());
		}

		$db = $this->db();
		$db->beginTransaction();

		$item->save(true, false);

		$db->commit();

		if ($moved && $item->isVisible() && $this->alert && $item->user_id != $user->user_id)
		{
			/** @var \XenAddons\Showcase\Repository\Item $itemRepo */
			$itemRepo = $this->repository('XenAddons\Showcase:Item');
			$itemRepo->sendModeratorActionAlert($this->item, 'move', $this->alertReason);
		}

		if ($moved && $this->notifyWatchers)
		{
			/** @var \XenAddons\Showcase\Service\Item\Notify $notifier */
			$notifier = $this->service('XenAddons\Showcase:Item\Notify', $item, 'item');
			if ($oldCategory)
			{
				$notifier->skipUsersWatchingCategory($oldCategory);
			}
			$notifier->notifyAndEnqueue(3);
		}

		return $moved;
	}
}