<?php

namespace DBTech\eCommerce\Cron;

/**
 * Class CleanUp
 *
 * @package DBTech\eCommerce\Cron
 */
class CleanUp
{
	/**
	 * Clean up tasks that should be done daily. This task cannot be relied on
	 * to run daily, consistently.
	 *
	 * @throws \Exception
	 */
	public static function runDailyCleanUp()
	{
		$app = \XF::app();
		
		/** @var \DBTech\eCommerce\Repository\Order $orderRepo */
		$orderRepo = $app->repository('DBTech\eCommerce:Order');
		$orderRepo->sendPendingOrderReminders();
		$orderRepo->deleteOldPendingOrders();
		
		/** @var \DBTech\eCommerce\Repository\License $purchaseRepo */
		$purchaseRepo = $app->repository('DBTech\eCommerce:License');
		$purchaseRepo->sendExpiryReminders();
		$purchaseRepo->sendExpiredAlerts();
	}

	/**
	 * Clean up tasks that should be done hourly. This task cannot be relied on
	 * to run every hour, consistently.
	 *
	 * @throws \XF\Db\Exception
	 */
	public static function runHourlyCleanUp()
	{
		$app = \XF::app();
		
		/** @var \DBTech\eCommerce\Repository\ProductCost $productCostRepo */
		$productCostRepo = $app->repository('DBTech\eCommerce:ProductCost');
		$productCostRepo->updateUnusedCostData();
		$productCostRepo->deleteUnassociatedCosts();
	}
}