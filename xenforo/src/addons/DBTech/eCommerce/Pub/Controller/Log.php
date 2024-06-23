<?php

namespace DBTech\eCommerce\Pub\Controller;

use XF\Mvc\ParameterBag;

/**
 * Class Log
 * @package DBTech\eCommerce\Pub\Controller
 */
class Log extends AbstractController
{
	/**
	 * @param $action
	 * @param ParameterBag $params
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function preDispatchController($action, ParameterBag $params)
	{
		/** @var \DBTech\eCommerce\XF\Entity\User $visitor */
		$visitor = \XF::visitor();
		
		
		switch ($action)
		{
			case 'Download':
				if (!$visitor->hasPermission('dbtechEcommerceAdmin', 'viewDownloadLog'))
				{
					throw $this->exception($this->noPermission());
				}
				break;
		}
	}

	/**
	 * @param ParameterBag $params
	 * @return \XF\Mvc\Reply\View
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionDownload(ParameterBag $params): \XF\Mvc\Reply\AbstractReply
	{
		if ($params->download_log_id)
		{
			$entry = $this->assertDownloadLogExists($params->download_log_id, [
				'License',
				'Download',
				'User',
				'Ip',
				'License.Product',
				'License.User',
			], 'requested_log_entry_not_found');

			$license = $entry->License;

			$viewParams = [
				'entry' => $entry,
				'license' => $license,
			];
			return $this->view('DBTech\eCommerce:Log\Download\View', 'dbtech_ecommerce_log_download_view', $viewParams);
		}
		
		$criteria = $this->filter('criteria', 'array');
		$order = $this->filter('order', 'str');
		$direction = $this->filter('direction', 'str');
		
		$page = $this->filterPage();
		$perPage = $this->options()->dbtechEcommerceLogEntriesPerPage;
		
		/** @var \DBTech\eCommerce\Searcher\DownloadLog $searcher */
		$searcher = $this->searcher('DBTech\eCommerce:DownloadLog', $criteria);
		
		if ($order && !$direction)
		{
			$direction = $searcher->getRecommendedOrderDirection($order);
		}
		
		$searcher->setOrder($order, $direction);
		
		$finder = $searcher->getFinder();
		$finder->limitByPage($page, $perPage);
		
		$finder->with('Ip');
		
		$total = $finder->total();
		$entries = $finder->fetch();
		
		$viewParams = [
			'entries' => $entries,

			'total' => $total,
			'page' => $page,
			'perPage' => $perPage,

			'criteria' => $searcher->getFilteredCriteria(),
			// 'filter' => $filter['text'],
			'sortOptions' => $searcher->getOrderOptions(),
			'order' => $order,
			'direction' => $direction

		];
		return $this->view('DBTech\eCommerce:Log\Download\Listing', 'dbtech_ecommerce_log_download_list', $viewParams);
	}

	/**
	 * @return \XF\Mvc\Reply\View
	 */
	public function actionDownloadSearch(): \XF\Mvc\Reply\AbstractReply
	{
		$viewParams = $this->getDownloadLogSearcherParams();

		return $this->view('DBTech\eCommerce:Log\Download\Search', 'dbtech_ecommerce_log_download_search', $viewParams);
	}

	/**
	 * @param array $extraParams
	 * @return array
	 */
	protected function getDownloadLogSearcherParams(array $extraParams = []): array
	{
		/** @var \DBTech\eCommerce\Searcher\DownloadLog $searcher */
		$searcher = $this->searcher('DBTech\eCommerce:DownloadLog');
		
		$viewParams = [
			'criteria' => $searcher->getFormCriteria(),
			'sortOrders' => $searcher->getOrderOptions()
		];
		return $viewParams + $searcher->getFormData() + $extraParams;
	}

	/**
	 * @return \XF\Mvc\Reply\View
	 */
	public function actionStoreCredit(): \XF\Mvc\Reply\AbstractReply
	{
		$criteria = $this->filter('criteria', 'array');
		$order = $this->filter('order', 'str');
		$direction = $this->filter('direction', 'str');

		$page = $this->filterPage();
		$perPage = $this->options()->dbtechEcommerceLogEntriesPerPage;
		
		/** @var \DBTech\eCommerce\Searcher\StoreCreditLog $searcher */
		$searcher = $this->searcher('DBTech\eCommerce:StoreCreditLog', $criteria);

		if ($order && !$direction)
		{
			$direction = $searcher->getRecommendedOrderDirection($order);
		}

		$searcher->setOrder($order, $direction);

		$finder = $searcher->getFinder();
		$finder->limitByPage($page, $perPage);

		$finder->with('Ip');

		$total = $finder->total();
		$entries = $finder->fetch();

		$viewParams = [
			'entries' => $entries,

			'total' => $total,
			'page' => $page,
			'perPage' => $perPage,

			'criteria' => $searcher->getFilteredCriteria(),
			// 'filter' => $filter['text'],
			'sortOptions' => $searcher->getOrderOptions(),
			'order' => $order,
			'direction' => $direction

		];
		return $this->view('DBTech\eCommerce:Log\StoreCredit\Listing', 'dbtech_ecommerce_log_store_credit_list', $viewParams);
	}

	/**
	 * @return \XF\Mvc\Reply\View
	 */
	public function actionStoreCreditSearch(): \XF\Mvc\Reply\AbstractReply
	{
		$viewParams = $this->getStoreCreditLogSearcherParams();

		return $this->view('DBTech\eCommerce:Log\StoreCredit\Search', 'dbtech_ecommerce_log_store_credit_search', $viewParams);
	}

	/**
	 * @param array $extraParams
	 * @return array
	 */
	protected function getStoreCreditLogSearcherParams(array $extraParams = []): array
	{
		/** @var \DBTech\eCommerce\Searcher\StoreCreditLog $searcher */
		$searcher = $this->searcher('DBTech\eCommerce:StoreCreditLog');

		$viewParams = [
			'criteria' => $searcher->getFormCriteria(),
			'sortOrders' => $searcher->getOrderOptions()
		];
		return $viewParams + $searcher->getFormData() + $extraParams;
	}

	/**
	 * @return \XF\Mvc\Reply\View
	 */
	public function actionCoupon(): \XF\Mvc\Reply\AbstractReply
	{
		$criteria = $this->filter('criteria', 'array');
		$order = $this->filter('order', 'str');
		$direction = $this->filter('direction', 'str');

		$page = $this->filterPage();
		$perPage = $this->options()->dbtechEcommerceLogEntriesPerPage;
		
		/** @var \DBTech\eCommerce\Searcher\CouponLog $searcher */
		$searcher = $this->searcher('DBTech\eCommerce:CouponLog', $criteria);

		if ($order && !$direction)
		{
			$direction = $searcher->getRecommendedOrderDirection($order);
		}

		$searcher->setOrder($order, $direction);

		$finder = $searcher->getFinder();
		$finder->limitByPage($page, $perPage);

		$finder->with('Ip');

		$total = $finder->total();
		$entries = $finder->fetch();

		$viewParams = [
			'entries' => $entries,

			'total' => $total,
			'page' => $page,
			'perPage' => $perPage,

			'criteria' => $searcher->getFilteredCriteria(),
			// 'filter' => $filter['text'],
			'sortOptions' => $searcher->getOrderOptions(),
			'order' => $order,
			'direction' => $direction

		];
		return $this->view('DBTech\eCommerce:Log\Coupon\Listing', 'dbtech_ecommerce_log_coupon_list', $viewParams);
	}

	/**
	 * @return \XF\Mvc\Reply\View
	 */
	public function actionCouponSearch(): \XF\Mvc\Reply\AbstractReply
	{
		$viewParams = $this->getCouponLogSearcherParams();

		return $this->view('DBTech\eCommerce:Log\Coupon\Search', 'dbtech_ecommerce_log_coupon_search', $viewParams);
	}

	/**
	 * @param array $extraParams
	 * @return array
	 */
	protected function getCouponLogSearcherParams(array $extraParams = []): array
	{
		/** @var \DBTech\eCommerce\Searcher\CouponLog $searcher */
		$searcher = $this->searcher('DBTech\eCommerce:CouponLog');

		$viewParams = [
			'criteria' => $searcher->getFormCriteria(),
			'sortOrders' => $searcher->getOrderOptions()
		];
		return $viewParams + $searcher->getFormData() + $extraParams;
	}

	/**
	 * @return \XF\Mvc\Reply\View
	 */
	public function actionPurchase(): \XF\Mvc\Reply\AbstractReply
	{
		$criteria = $this->filter('criteria', 'array');
		$order = $this->filter('order', 'str');
		$direction = $this->filter('direction', 'str');

		$page = $this->filterPage();
		$perPage = $this->options()->dbtechEcommerceLogEntriesPerPage;
		
		/** @var \DBTech\eCommerce\Searcher\PurchaseLog $searcher */
		$searcher = $this->searcher('DBTech\eCommerce:PurchaseLog', $criteria);

		if ($order && !$direction)
		{
			$direction = $searcher->getRecommendedOrderDirection($order);
		}

		$searcher->setOrder($order, $direction);

		$finder = $searcher->getFinder();
		$finder->limitByPage($page, $perPage);

		$finder->with('Ip');

		$total = $finder->total();
		$entries = $finder->fetch();

		$viewParams = [
			'entries' => $entries,

			'total' => $total,
			'page' => $page,
			'perPage' => $perPage,

			'criteria' => $searcher->getFilteredCriteria(),
			// 'filter' => $filter['text'],
			'sortOptions' => $searcher->getOrderOptions(),
			'order' => $order,
			'direction' => $direction

		];
		return $this->view('DBTech\eCommerce:Log\Purchase\Listing', 'dbtech_ecommerce_log_purchase_list', $viewParams);
	}

	/**
	 * @return \XF\Mvc\Reply\View
	 */
	public function actionPurchaseSearch(): \XF\Mvc\Reply\AbstractReply
	{
		$viewParams = $this->getPurchaseLogSearcherParams();

		return $this->view('DBTech\eCommerce:Log\Purchase\Search', 'dbtech_ecommerce_log_purchase_search', $viewParams);
	}

	/**
	 * @param array $extraParams
	 * @return array
	 */
	protected function getPurchaseLogSearcherParams(array $extraParams = []): array
	{
		/** @var \DBTech\eCommerce\Searcher\PurchaseLog $searcher */
		$searcher = $this->searcher('DBTech\eCommerce:PurchaseLog');

		$viewParams = [
			'criteria' => $searcher->getFormCriteria(),
			'sortOrders' => $searcher->getOrderOptions()
		];
		return $viewParams + $searcher->getFormData() + $extraParams;
	}

	/**
	 * @param int|null $id
	 * @param array|string|null $with
	 * @param null|string $phraseKey
	 *
	 * @return \DBTech\eCommerce\Entity\DownloadLog
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function assertDownloadLogExists(?int $id, $with = null, ?string $phraseKey = null): \DBTech\eCommerce\Entity\DownloadLog
	{
		return $this->assertRecordExists('DBTech\eCommerce:DownloadLog', $id, $with, $phraseKey);
	}
}