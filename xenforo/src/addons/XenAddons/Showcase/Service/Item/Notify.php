<?php

namespace XenAddons\Showcase\Service\Item;

use XenAddons\Showcase\Entity\Item;
use XF\Service\AbstractNotifier;

class Notify extends AbstractNotifier
{
	/**
	 * @var Item
	 */
	protected $item;

	protected $actionType;

	public function __construct(\XF\App $app,Item $item, $actionType)
	{
		parent::__construct($app);

		switch ($actionType)
		{
			case 'update':
			case 'item':
				break;

			default:
				throw new \InvalidArgumentException("Unknown action type '$actionType'");
		}

		$this->actionType = $actionType;
		$this->item = $item;
	}

	public static function createForJob(array $extraData)
	{
		$item = \XF::app()->find('XenAddons\Showcase:Item', $extraData['itemId'], ['Category']);
		if (!$item)
		{
			return null;
		}

		return \XF::service('XenAddons\Showcase:Item\Notify', $item, $extraData['actionType']);
	}

	protected function getExtraJobData()
	{
		return [
			'itemId' => $this->item->item_id,
			'actionType' => $this->actionType
		];
	}

	protected function loadNotifiers()
	{
		return [
			'mention' => $this->app->notifier('XenAddons\Showcase:Item\Mention', $this->item),
			'categoryWatch' => $this->app->notifier('XenAddons\Showcase:Item\CategoryWatch', $this->item, $this->actionType),
			'itemWatch'=> $this->app->notifier('XenAddons\Showcase:Item\ItemWatch', $this->item)
		];
	}

	protected function loadExtraUserData(array $users)
	{
		$permCombinationIds = [];
		foreach ($users AS $user)
		{
			$id = $user->permission_combination_id;
			$permCombinationIds[$id] = $id;
		}

		$this->app->permissionCache()->cacheMultipleContentPermsForContent(
			$permCombinationIds,
			'item_category', $this->item->Category->category_id
		);
	}

	protected function canUserViewContent(\XF\Entity\User $user)
	{
		return \XF::asVisitor(
			$user,
			function() { return $this->item->canView(); }
		);
	}

	public function skipUsersWatchingCategory(\XenAddons\Showcase\Entity\Category $category)
	{
		$checkCategories = array_keys($category->breadcrumb_data);
		$checkCategories[] = $category->category_id;

		$db = $this->db();

		$watchers = $db->fetchAll("
			SELECT user_id, send_alert, send_email
			FROM xf_xa_sc_category_watch
			WHERE category_id IN (" . $db->quote($checkCategories) . ")
				AND (category_id = ? OR include_children > 0)
				AND (send_alert = 1 OR send_email = 1)
		", $category->category_id);

		foreach ($watchers AS $watcher)
		{
			if ($watcher['send_alert'])
			{
				$this->setUserAsAlerted($watcher['user_id']);
			}
			if ($watcher['send_email'])
			{
				$this->setUserAsEmailed($watcher['user_id']);
			}
		}
	}
}