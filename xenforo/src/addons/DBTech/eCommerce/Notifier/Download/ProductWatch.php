<?php

namespace DBTech\eCommerce\Notifier\Download;

/**
 * Class ProductWatch
 *
 * @package DBTech\eCommerce\Notifier\Download
 */
class ProductWatch extends AbstractWatch
{
	/**
	 * @return array
	 */
	protected function getApplicableActionTypes(): array
	{
		return ['download'];
	}

	/**
	 * @return array|mixed
	 */
	protected function getDefaultWatchNotifyData(): array
	{
		$download = $this->download;

		$finder = $this->app()->finder('DBTech\eCommerce:ProductWatch');

		$finder->where('product_id', $download->product_id)
			->where('User.user_state', '=', 'valid')
			->where('User.is_banned', '=', 0);

		$activeLimit = $this->app()->options()->watchAlertActiveOnly;
		if (!empty($activeLimit['enabled']))
		{
			$finder->where('User.last_activity', '>=', \XF::$time - 86400 * $activeLimit['days']);
		}

		$notifyData = [];
		foreach ($finder->fetchColumns(['user_id', 'email_subscribe']) AS $watch)
		{
			$notifyData[$watch['user_id']] = [
				'alert' => true,
				'email' => (bool)$watch['email_subscribe']
			];
		}

		return $notifyData;
	}

	/**
	 * @return mixed|string
	 */
	protected function getWatchEmailTemplateName(): string
	{
		return 'dbtech_ecommerce_watched_product_update';
	}
}