<?php

namespace DBTech\Credits\Admin\Controller;

use XF\Admin\Controller\AbstractController;
use XF\Mvc\ParameterBag;

/**
 * Class Log
 * @package DBTech\Credits\Admin\Controller
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
		$this->assertAdminPermission('dbtechCredits');
	}

	/**
	 * @return \XF\Mvc\Reply\View
	 */
	public function actionIndex(): \XF\Mvc\Reply\AbstractReply
	{
		return $this->view('DBTech\Credits:Log', 'dbtech_credits_logs');
	}
	
	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\View
	 * @throws \XF\Mvc\Reply\Exception
	 * @throws \Exception
	 */
	public function actionTransaction(ParameterBag $params): \XF\Mvc\Reply\AbstractReply
	{
		if ($params->transaction_id)
		{
			$entry = $this->assertTransactionLogExists($params->transaction_id, [
				'Event',
				'Currency',
				'TargetUser',
				'SourceUser'
			], 'requested_log_entry_not_found');

			/** @var \DBTech\Credits\Repository\EventTrigger $eventTriggerRepo */
			$eventTriggerRepo = $this->repository('DBTech\Credits:EventTrigger');
			$eventTrigger = $eventTriggerRepo->getHandler($entry->event_trigger_id);

			$viewParams = [
				'entry' => $entry,
				'eventTrigger' => $eventTrigger,
			];
			return $this->view('DBTech\Credits:Log\Transaction\View', 'dbtech_credits_log_transaction_view', $viewParams);
		}
		
		$criteria = $this->filter('criteria', 'array');
		$order = $this->filter('order', 'str');
		$direction = $this->filter('direction', 'str');
		
		$page = $this->filterPage();
		$perPage = $this->options()->dbtech_credits_transactions;
		
		/** @var \DBTech\Credits\Searcher\TransactionLog $searcher */
		$searcher = $this->searcher('DBTech\Credits:TransactionLog', $criteria);
		
		if (empty($criteria))
		{
			$searcher->setCriteria($searcher->getFormDefaults());
		}
		
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
		return $this->view('DBTech\Credits:Log\Transaction\Listing', 'dbtech_credits_log_transaction_list', $viewParams);
	}

	/**
	 * @return \XF\Mvc\Reply\View
	 */
	public function actionTransactionSearch(): \XF\Mvc\Reply\AbstractReply
	{
		$viewParams = $this->getTransactionLogSearcherParams();

		return $this->view('DBTech\Credits:Log\Transaction\Search', 'dbtech_credits_log_transaction_search', $viewParams);
	}

	/**
	 * @param array $extraParams
	 * @return array
	 */
	protected function getTransactionLogSearcherParams(array $extraParams = []): array
	{
		/** @var \DBTech\Credits\Searcher\TransactionLog $searcher */
		$searcher = $this->searcher('DBTech\Credits:TransactionLog');
		
		$viewParams = [
			'criteria' => $searcher->getFormCriteria(),
			'sortOrders' => $searcher->getOrderOptions()
		];
		return $viewParams + $searcher->getFormData() + $extraParams;
	}
	
	/**
	 * @param string $id
	 * @param array|string|null $with
	 * @param null|string $phraseKey
	 *
	 * @return \DBTech\Credits\Entity\Transaction|\XF\Mvc\Entity\Entity
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function assertTransactionLogExists($id, $with = null, $phraseKey = null)
	{
		return $this->assertRecordExists('DBTech\Credits:Transaction', $id, $with, $phraseKey);
	}
}