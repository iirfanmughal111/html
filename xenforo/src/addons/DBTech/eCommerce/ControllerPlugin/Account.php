<?php

namespace DBTech\eCommerce\ControllerPlugin;

use XF\ControllerPlugin\AbstractPlugin;

/**
 * Class Account
 *
 * @package DBTech\eCommerce\ControllerPlugin
 */
class Account extends AbstractPlugin
{
	/**
	 * @return array
	 * @throws \InvalidArgumentException
	 */
	public function getCoreListData(): array
	{
		$orderRepo = $this->getOrderRepo();

		$orderFinder = $orderRepo->findOrdersForAccountList();

		$filters = $this->getOrderFilterInput();
		$this->applyOrderFilters($orderFinder, $filters);

		$totalOrders = $orderFinder->total();

		$page = $this->filterPage();
		$perPage = $this->options()->dbtechEcommerceOrdersPerPage;

		$orderFinder->limitByPage($page, $perPage);
		$orders = $orderFinder->fetch();
		
		if (!empty($filters['address']))
		{
			/** @var \DBTech\eCommerce\Entity\Address $addressFilter **/
			$addressFilter = $this->em()->find('DBTech\eCommerce:Address', $filters['address']);
			if ($addressFilter && $addressFilter->user_id != \XF::visitor()->user_id)
			{
				$addressFilter = null;
			}
		}
		else
		{
			$addressFilter = null;
		}
		
		if (!empty($filters['state']))
		{
			switch ($filters['state'])
			{
				case 'pending':
					$stateFilter = \XF::phrase('dbtech_ecommerce_pending');
					break;
				
				case 'awaiting_payment':
					$stateFilter = \XF::phrase('dbtech_ecommerce_awaiting_payment');
					break;
				
				case 'reversed':
					$stateFilter = \XF::phrase('dbtech_ecommerce_reversed_refunded');
					break;
				
				case 'completed':
					$stateFilter = \XF::phrase('dbtech_ecommerce_completed');
					break;
				
				case 'shipped':
					$stateFilter = \XF::phrase('dbtech_ecommerce_shipped');
					break;
					
				default:
					$stateFilter = \XF::phrase('dbtech_ecommerce_unknown_order_state');
					break;
			}
		}
		else
		{
			$stateFilter = null;
		}

		return [
			'orders' => $orders,
			'filters' => $filters,
			'addressFilter' => $addressFilter,
			'stateFilter' => $stateFilter,

			'total' => $totalOrders,
			'page' => $page,
			'perPage' => $perPage
		];
	}

	/**
	 * @param \DBTech\eCommerce\Finder\Order $orderFinder
	 * @param array $filters
	 */
	public function applyOrderFilters(\DBTech\eCommerce\Finder\Order $orderFinder, array $filters)
	{
		if (!empty($filters['address']))
		{
			$orderFinder->where('address_id', (int)$filters['address']);
		}
		
		if (!empty($filters['state']))
		{
			$orderFinder->where('order_state', $filters['state']);
		}
		
		$sorts = $this->getAvailableOrderSorts();

		if (!empty($filters['order']) && isset($sorts[$filters['order']]))
		{
			$orderFinder->order($sorts[$filters['order']], $filters['direction']);
		}
	}

	/**
	 * @return array
	 */
	public function getOrderFilterInput(): array
	{
		$filters = [];

		$input = $this->filter([
			'address' => 'uint',
			'state' => 'str',
			'order' => 'str',
			'direction' => 'str'
		]);
		
		if ($input['address'])
		{
			$filters['address'] = $input['address'];
		}
		
		if ($input['state'] && in_array($input['state'], ['awaiting_payment', 'reversed', 'completed']))
		{
			$filters['state'] = $input['state'];
		}

		$sorts = $this->getAvailableOrderSorts();

		if ($input['order'] && isset($sorts[$input['order']]))
		{
			if (!in_array($input['direction'], ['asc', 'desc']))
			{
				$input['direction'] = 'desc';
			}

			$defaultOrder = $this->options()->dbtechEcommerceOrderDefaultOrder ?: 'order_date';
			$defaultDir = $defaultOrder == 'address' ? 'asc' : 'desc';

			if ($input['order'] != $defaultOrder || $input['direction'] != $defaultDir)
			{
				$filters['order'] = $input['order'];
				$filters['direction'] = $input['direction'];
			}
		}

		return $filters;
	}

	/**
	 * @return array
	 */
	public function getAvailableOrderSorts(): array
	{
		// maps [name of sort] => field in/relative to Order entity
		return [
			'order_date' => 'order_date',
			'order_state' => 'order_state',
			'cost_amount' => 'cost_amount'
		];
	}

	/**
	 * @return \XF\Mvc\Reply\Redirect|\XF\Mvc\Reply\View
	 */
	public function actionFilters()
	{
		$filters = $this->getOrderFilterInput();

		if ($this->filter('apply', 'bool'))
		{
			return $this->redirect($this->buildLink(
				'dbtech-ecommerce/account',
				null,
				$filters
			));
		}
		
		$addressFilter = $this->getAddressRepo()->getAddressTitlePairs();
		asort($addressFilter);

		$defaultOrder = $this->options()->dbtechEcommerceOrderDefaultOrder ?: 'order_date';
		$defaultDir = $defaultOrder == 'address' ? 'asc' : 'desc';

		if (empty($filters['order']))
		{
			$filters['order'] = $defaultOrder;
		}
		if (empty($filters['direction']))
		{
			$filters['direction'] = $defaultDir;
		}

		$viewParams = [
			'filters' => $filters,
			'addressFilter' => $addressFilter,
		];
		return $this->view('DBTech\eCommerce:Order\Filters', 'dbtech_ecommerce_order_filters', $viewParams);
	}

	/**
	 * @return \DBTech\eCommerce\Repository\Order|\XF\Mvc\Entity\Repository
	 */
	protected function getOrderRepo()
	{
		return $this->repository('DBTech\eCommerce:Order');
	}

	/**
	 * @return \DBTech\eCommerce\Repository\Address|\XF\Mvc\Entity\Repository
	 */
	protected function getAddressRepo()
	{
		return $this->repository('DBTech\eCommerce:Address');
	}
}