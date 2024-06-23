<?php

namespace DBTech\eCommerce\Service\Download;

use DBTech\eCommerce\Entity\Download;
use XF\Service\AbstractNotifier;

/**
 * Class Notify
 *
 * @package DBTech\eCommerce\Service\Download
 */
class Notify extends AbstractNotifier
{
	/** @var \DBTech\eCommerce\Entity\Download  */
	protected $download;
	
	/** @var string */
	protected $actionType;
	
	/**
	 * Notify constructor.
	 *
	 * @param \XF\App $app
	 * @param Download $download
	 * @param string $actionType
	 *
	 * @throws \InvalidArgumentException
	 */
	public function __construct(\XF\App $app, Download $download, string $actionType)
	{
		parent::__construct($app);

		switch ($actionType)
		{
			case 'download':
			case 'product':
				break;

			default:
				throw new \InvalidArgumentException("Unknown action type '$actionType'");
		}

		$this->actionType = $actionType;
		$this->download = $download;
	}
	
	/**
	 * @param array $extraData
	 *
	 * @return null|void|\XF\Service\AbstractService
	 */
	public static function createForJob(array $extraData): ?\XF\Service\AbstractService
	{
		$download = \XF::app()->find('DBTech\eCommerce:Download', $extraData['downloadId'], ['User', 'Product', 'Product.Category']);
		if (!$download)
		{
			return null;
		}

		return \XF::service('DBTech\eCommerce:Download\Notify', $download, $extraData['actionType']);
	}
	
	/**
	 * @return array
	 */
	protected function getExtraJobData(): array
	{
		return [
			'downloadId' => $this->download->download_id,
			'actionType' => $this->actionType
		];
	}
	
	/**
	 * @return array
	 */
	protected function loadNotifiers(): array
	{
		return [
			'mention' => $this->app->notifier('DBTech\eCommerce:Download\Mention', $this->download),
			'productWatch' => $this->app->notifier('DBTech\eCommerce:Download\ProductWatch', $this->download, $this->actionType),
			'categoryWatch' => $this->app->notifier('DBTech\eCommerce:Download\CategoryWatch', $this->download, $this->actionType),
		];
	}
	
	/**
	 * @param array $users
	 */
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
			'dbtech_ecommerce_category',
			$this->download->Product->product_category_id
		);
		
		$this->app->permissionCache()->cacheMultipleContentPermsForContent(
			$permCombinationIds,
			'dbtech_ecommerce_product',
			$this->download->product_id
		);
	}
	
	/**
	 * @param \XF\Entity\User $user
	 *
	 * @return mixed
	 * @throws \Exception
	 */
	protected function canUserViewContent(\XF\Entity\User $user)
	{
		return \XF::asVisitor(
			$user,
			function (): bool
			{
				return $this->download->canView();
			}
		);
	}
	
	/**
	 * @param \DBTech\eCommerce\Entity\Category $category
	 */
	public function skipUsersWatchingCategory(\DBTech\eCommerce\Entity\Category $category)
	{
		$checkCategories = array_keys($category->breadcrumb_data);
		$checkCategories[] = $category->category_id;

		$db = $this->db();

		$watchers = $db->fetchAll('
			SELECT user_id, send_alert, send_email
			FROM xf_dbtech_ecommerce_category_watch
			WHERE category_id IN (' . $db->quote($checkCategories) . ')
				AND (category_id = ? OR include_children > 0)
				AND (send_alert = 1 OR send_email = 1)
		', $category->category_id);

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