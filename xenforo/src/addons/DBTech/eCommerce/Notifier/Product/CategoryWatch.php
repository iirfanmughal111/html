<?php

namespace DBTech\eCommerce\Notifier\Product;

/**
 * Class CategoryWatch
 *
 * @package DBTech\eCommerce\Notifier\Product
 */
class CategoryWatch extends AbstractWatch
{
	/**
	 * @return array
	 * @throws \InvalidArgumentException
	 */
	protected function getDefaultWatchNotifyData(): array
	{
		$product = $this->product;
		$category = $product->Category;
		
		$checkCategories = array_keys($category->breadcrumb_data);
		$checkCategories[] = $category->category_id;

		$finder = $this->app()->finder('DBTech\eCommerce:CategoryWatch');
		$finder->where('category_id', $checkCategories)
			->where('User.user_state', '=', 'valid')
			->where('User.is_banned', '=', 0)
			->whereOr(
				['send_alert', '>', 0],
				['send_email', '>', 0]
			);

		$finder->where('notify_on', ['product', 'download']);

		$activeLimit = $this->app()->options()->watchAlertActiveOnly;
		if (!empty($activeLimit['enabled']))
		{
			$finder->where('User.last_activity', '>=', \XF::$time - 86400 * $activeLimit['days']);
		}

		$notifyData = [];
		foreach ($finder->fetchColumns(['user_id', 'send_alert', 'send_email']) AS $watch)
		{
			$notifyData[$watch['user_id']] = [
				'alert' => (bool)$watch['send_alert'],
				'email' => (bool)$watch['send_email']
			];
		}

		return $notifyData;
	}

	/**
	 * @return string
	 */
	protected function getWatchEmailTemplateName(): string
	{
		return 'dbtech_ecommerce_watched_category_product';
	}
}