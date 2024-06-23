<?php

namespace DBTech\eCommerce\Admin\Controller;

use XF\Admin\Controller\AbstractController;
use XF\Mvc\ParameterBag;

/**
 * Class Log
 * @package DBTech\eCommerce\Admin\Controller
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
		$this->assertAdminPermission('dbtechEcomLogs');
	}

	/**
	 * @return \XF\Mvc\Reply\View
	 */
	public function actionIndex(): \XF\Mvc\Reply\AbstractReply
	{
		return $this->view('DBTech\eCommerce:Log', 'dbtech_ecommerce_logs');
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
	 * @param ParameterBag $params
	 * @return \XF\Mvc\Reply\View
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionOrder(ParameterBag $params): \XF\Mvc\Reply\AbstractReply
	{
		if ($params->order_id)
		{
			$entry = $this->assertOrderExists($params->order_id, [
				'User',
			], 'requested_log_entry_not_found');
			
			$viewParams = [
				'order' => $entry,
			];
			return $this->view('DBTech\eCommerce:Log\Order\View', 'dbtech_ecommerce_log_order_view', $viewParams);
		}
		
		$criteria = $this->filter('criteria', 'array');
		$order = $this->filter('order', 'str');
		$direction = $this->filter('direction', 'str');
		
		$page = $this->filterPage();
		$perPage = $this->options()->dbtechEcommerceLogEntriesPerPage;
		
		/** @var \DBTech\eCommerce\Searcher\Order $searcher */
		$searcher = $this->searcher('DBTech\eCommerce:Order', $criteria);
		
		if ($order && !$direction)
		{
			$direction = $searcher->getRecommendedOrderDirection($order);
		}
		
		$searcher->setOrder($order, $direction);
		
		$finder = $searcher->getFinder();
		$finder->limitByPage($page, $perPage);
		
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
		return $this->view('DBTech\eCommerce:Log\Order\Listing', 'dbtech_ecommerce_log_order_list', $viewParams);
	}

	/**
	 * @return \XF\Mvc\Reply\View
	 */
	public function actionOrderCsv(): \XF\Mvc\Reply\AbstractReply
	{
		$criteria = $this->filter('criteria', 'array');
		$order = $this->filter('order', 'str');
		$direction = $this->filter('direction', 'str');

		/** @var \DBTech\eCommerce\Searcher\Order $searcher */
		$searcher = $this->searcher('DBTech\eCommerce:Order', $criteria);

		if ($order && !$direction)
		{
			$direction = $searcher->getRecommendedOrderDirection($order);
		}

		$searcher->setOrder($order, $direction);

		$finder = $searcher->getFinder();
		$entries = $finder->fetch();

		$csvBuilder = $this->service('DBTech\eCommerce:Order\CsvBuilder', $entries);
		$csvBuilder->build();

		$this->setResponseType('raw');

		$viewParams = [
			'fileName' => 'ecommerce-invoices.csv',
			'csvPath' => $csvBuilder->getFilePath()
		];
		return $this->view('DBTech\eCommerce:Order\Log\Download', '', $viewParams);
	}
	
	/**
	 * @return \XF\Mvc\Reply\View
	 */
	public function actionOrderSearch(): \XF\Mvc\Reply\AbstractReply
	{
		$viewParams = $this->getOrderLogSearcherParams();
		
		return $this->view('DBTech\eCommerce:Log\Order\Search', 'dbtech_ecommerce_log_order_search', $viewParams);
	}
	
	/**
	 * @param array $extraParams
	 * @return array
	 */
	protected function getOrderLogSearcherParams(array $extraParams = []): array
	{
		/** @var \DBTech\eCommerce\Searcher\Order $searcher */
		$searcher = $this->searcher('DBTech\eCommerce:Order');
		
		$viewParams = [
			'criteria' => $searcher->getFormCriteria(),
			'sortOrders' => $searcher->getOrderOptions()
		];
		return $viewParams + $searcher->getFormData() + $extraParams;
	}
	
	/**
	 * @param ParameterBag $params
	 * @return \XF\Mvc\Reply\View
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionCommissionPayment(ParameterBag $params): \XF\Mvc\Reply\AbstractReply
	{
		if ($params->commission_payment_id)
		{
			$entry = $this->assertCommissionPaymentExists($params->commission_payment_id, [
				'Commission',
				'User',
				'Ip'
			], 'requested_log_entry_not_found');
			
			$viewParams = [
				'payment' => $entry,
			];
			return $this->view('DBTech\eCommerce:Log\CommissionPayment\View', 'dbtech_ecommerce_log_commission_payment_view', $viewParams);
		}
		
		$criteria = $this->filter('criteria', 'array');
		$order = $this->filter('order', 'str');
		$direction = $this->filter('direction', 'str');
		
		$page = $this->filterPage();
		$perPage = $this->options()->dbtechEcommerceLogEntriesPerPage;
		
		/** @var \DBTech\eCommerce\Searcher\CommissionPayment $searcher */
		$searcher = $this->searcher('DBTech\eCommerce:CommissionPayment', $criteria);
		
		if ($order && !$direction)
		{
			$direction = $searcher->getRecommendedOrderDirection($order);
		}
		
		$searcher->setOrder($order, $direction);
		
		$finder = $searcher->getFinder();
		$finder->limitByPage($page, $perPage);
		
		$finder->with(['Commission', 'Ip', 'User']);
		
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
		return $this->view('DBTech\eCommerce:Log\CommissionPayment\Listing', 'dbtech_ecommerce_log_commission_payment_list', $viewParams);
	}
	
	/**
	 * @return \XF\Mvc\Reply\View
	 */
	public function actionCommissionPaymentSearch(): \XF\Mvc\Reply\AbstractReply
	{
		$viewParams = $this->getCommissionPaymentLogSearcherParams();
		
		return $this->view('DBTech\eCommerce:Log\CommissionPayment\Search', 'dbtech_ecommerce_log_commission_payment_search', $viewParams);
	}
	
	/**
	 * @param array $extraParams
	 * @return array
	 */
	protected function getCommissionPaymentLogSearcherParams(array $extraParams = []): array
	{
		/** @var \DBTech\eCommerce\Searcher\CommissionPayment $searcher */
		$searcher = $this->searcher('DBTech\eCommerce:CommissionPayment');
		
		$viewParams = [
			'criteria' => $searcher->getFormCriteria(),
			'sortOrders' => $searcher->getOrderOptions()
		];
		return $viewParams + $searcher->getFormData() + $extraParams;
	}
	
	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\View
	 */
	public function actionStoreCredit(ParameterBag $params): \XF\Mvc\Reply\AbstractReply
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
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\View
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionCoupon(ParameterBag $params): \XF\Mvc\Reply\AbstractReply
	{
		if ($params->coupon_log_id)
		{
			$entry = $this->assertCouponLogExists($params->coupon_log_id, [
				'Order',
				'User',
				'Ip',
				'Product'
			], 'requested_log_entry_not_found');
			
			$viewParams = [
				'entry' => $entry,
			];
			return $this->view('DBTech\eCommerce:Log\Coupon\View', 'dbtech_ecommerce_log_coupon_view', $viewParams);
		}
		
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
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\View
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionPurchase(ParameterBag $params): \XF\Mvc\Reply\AbstractReply
	{
		if ($params->purchase_log_id)
		{
			$entry = $this->assertPurchaseLogExists($params->purchase_log_id, [
				'Order',
				'User',
				'Ip',
				'Product'
			], 'requested_log_entry_not_found');
			
			$viewParams = [
				'entry' => $entry,
			];
			return $this->view('DBTech\eCommerce:Log\Purchase\View', 'dbtech_ecommerce_log_purchase_view', $viewParams);
		}
		
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
	 * @return \XF\Mvc\Reply\View
	 */
	public function actionDistributor(): \XF\Mvc\Reply\AbstractReply
	{
		$criteria = $this->filter('criteria', 'array');
		$order = $this->filter('order', 'str');
		$direction = $this->filter('direction', 'str');
		
		$page = $this->filterPage();
		$perPage = $this->options()->dbtechEcommerceLogEntriesPerPage;
		
		/** @var \DBTech\eCommerce\Searcher\DistributorLog $searcher */
		$searcher = $this->searcher('DBTech\eCommerce:DistributorLog', $criteria);
		
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
		return $this->view('DBTech\eCommerce:Log\Distributor\Listing', 'dbtech_ecommerce_log_distributor_list', $viewParams);
	}
	
	/**
	 * @return \XF\Mvc\Reply\View
	 */
	public function actionDistributorSearch(): \XF\Mvc\Reply\AbstractReply
	{
		$viewParams = $this->getDistributorLogSearcherParams();
		
		return $this->view('DBTech\eCommerce:Log\Distributor\Search', 'dbtech_ecommerce_log_distributor_search', $viewParams);
	}
	
	/**
	 * @param array $extraParams
	 * @return array
	 */
	protected function getDistributorLogSearcherParams(array $extraParams = []): array
	{
		/** @var \DBTech\eCommerce\Searcher\DistributorLog $searcher */
		$searcher = $this->searcher('DBTech\eCommerce:DistributorLog');
		
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
	
	/**
	 * @param int|null $id
	 * @param array|string|null $with
	 * @param null|string $phraseKey
	 *
	 * @return \DBTech\eCommerce\Entity\CouponLog
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function assertCouponLogExists(?int $id, $with = null, ?string $phraseKey = null): \DBTech\eCommerce\Entity\CouponLog
	{
		return $this->assertRecordExists('DBTech\eCommerce:CouponLog', $id, $with, $phraseKey);
	}
	
	/**
	 * @param int|null $id
	 * @param array|string|null $with
	 * @param null|string $phraseKey
	 *
	 * @return \DBTech\eCommerce\Entity\PurchaseLog
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function assertPurchaseLogExists(?int $id, $with = null, ?string $phraseKey = null): \DBTech\eCommerce\Entity\PurchaseLog
	{
		return $this->assertRecordExists('DBTech\eCommerce:PurchaseLog', $id, $with, $phraseKey);
	}
	
	/**
	 * @param int|null $id
	 * @param array|string|null $with
	 * @param null|string $phraseKey
	 *
	 * @return \DBTech\eCommerce\Entity\Order
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function assertOrderExists(?int $id, $with = null, ?string $phraseKey = null): \DBTech\eCommerce\Entity\Order
	{
		return $this->assertRecordExists('DBTech\eCommerce:Order', $id, $with, $phraseKey);
	}
	
	/**
	 * @param int|null $id
	 * @param array|string|null $with
	 * @param null|string $phraseKey
	 *
	 * @return \DBTech\eCommerce\Entity\CommissionPayment
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function assertCommissionPaymentExists(?int $id, $with = null, ?string $phraseKey = null): \DBTech\eCommerce\Entity\CommissionPayment
	{
		return $this->assertRecordExists('DBTech\eCommerce:CommissionPayment', $id, $with, $phraseKey);
	}
}