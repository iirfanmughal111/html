<?php

namespace DBTech\eCommerce\Admin\Controller;

use XF\Admin\Controller\AbstractController;
use XF\Mvc\ParameterBag;

/**
 * Class Sale
 * @package DBTech\eCommerce\Admin\Controller
 */
class Sale extends AbstractController
{
	/**
	 * @param $action
	 * @param ParameterBag $params
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function preDispatchController($action, ParameterBag $params)
	{
		$this->assertAdminPermission('dbtechEcomSale');
	}

	/**
	 * @return \XF\Mvc\Reply\View
	 */
	public function actionIndex(): \XF\Mvc\Reply\AbstractReply
	{
		$page = $this->filterPage();
		$perPage = 300;

		/** @var \DBTech\eCommerce\Finder\Sale $saleFinder */
		$saleFinder = $this->getSaleRepo()->findSalesForList();
		$saleFinder->limitByPage($page, $perPage);

		$filter = $this->filter('_xfFilter', [
			'text' => 'str',
			'prefix' => 'bool'
		]);
		if (strlen($filter['text']))
		{
			$saleFinder->searchText($filter['text'], false, $filter['prefix']);
		}

		$sales = $saleFinder->fetch();
		$total = $saleFinder->total();
		
		$options = $this->em()->find('XF:Option', 'dbtechEcommerceSales');
		
		$viewParams = [
			'sales' => $sales,

			'filter' => $filter['text'],

			'page' => $page,
			'perPage' => $perPage,
			'total' => $total,
			'options' => [$options]
		];
		return $this->view('DBTech\eCommerce:Sale\Listing', 'dbtech_ecommerce_sale_list', $viewParams);
	}

	/**
	 * @param \DBTech\eCommerce\Entity\Sale $sale
	 * @return \XF\Mvc\Reply\View
	 */
	protected function saleAddEdit(\DBTech\eCommerce\Entity\Sale $sale): \XF\Mvc\Reply\AbstractReply
	{
		$viewParams = [
			'sale' => $sale,
			'nextDiscountCounter' => count($sale->product_discounts),
			'nextDateCounter' => count($sale->other_dates),
		];
		return $this->view('DBTech\eCommerce:Sale\Edit', 'dbtech_ecommerce_sale_edit', $viewParams);
	}

	/**
	 * @param ParameterBag $params
	 * @return \XF\Mvc\Reply\View
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionEdit(ParameterBag $params): \XF\Mvc\Reply\AbstractReply
	{
		/** @var \DBTech\eCommerce\Entity\Sale $sale */
		$sale = $this->assertSaleExists($params->sale_id);
		return $this->saleAddEdit($sale);
	}

	/**
	 * @return \DBTech\eCommerce\Service\Sale\Create
	 * @throws \Exception
	 * @throws \Exception
	 * @throws \Exception
	 */
	protected function setupSaleCreate(): \DBTech\eCommerce\Service\Sale\Create
	{
		/** @var \DBTech\eCommerce\Service\Sale\Create $creator */
		$creator = $this->service('DBTech\eCommerce:Sale\Create');
		
		$bulkInput = $this->filter([
			'title' => 'str',
			'email_notify' => 'bool',
			'sale_type' => 'str',
			'sale_percent' => 'float',
			'sale_value' => 'float',
			'discount_excluded' => 'bool',
			'allow_auto_discount' => 'bool',
			'feature_products' => 'bool',
			'is_recurring' => 'bool',
			'recurring_length_amount' => 'uint',
			'recurring_length_unit' => 'str',
		]);
		$creator->getSale()->bulkSet($bulkInput);
		
		$creator->setDescription($this->filter('description', 'str'));
		
		$dateInput = $this->filter([
			'start_date' => 'datetime',
			'start_time' => 'str'
		]);
		$creator->setStartDate($dateInput['start_date'], $dateInput['start_time']);
		
		$dateInput = $this->filter([
			'length_amount' => 'uint',
			'length_unit' => 'str',
		]);
		$creator->setDuration($dateInput['length_amount'], $dateInput['length_unit']);
		
		$discounts = [];
		$args = $this->filter('product_discounts', 'array');
		foreach ($args AS $arg)
		{
			if (empty($arg['product_id']))
			{
				continue;
			}
			$discounts[] = $this->filterArray($arg, [
				'product_id' => 'uint',
				'product_value' => 'float',
			]);
		}
		
		$creator->setProductDiscounts($discounts);
		
		$dates = [];
		$args = $this->filter('other_dates', 'array');
		foreach ($args AS $arg)
		{
			if (empty($arg['start']) || empty($arg['end']))
			{
				continue;
			}
			$dates[] = $this->filterArray($arg, [
				'start' => 'datetime',
				'end' => 'datetime',
			]);
		}
		
		$creator->setOtherDates($dates);
		
		if ($this->filter('email_notify_immediately', 'bool'))
		{
			$creator->setEmail(true);
		}
		
		return $creator;
	}
	
	/**
	 * @param \DBTech\eCommerce\Service\Sale\Create $creator
	 */
	protected function finalizeSaleCreate(\DBTech\eCommerce\Service\Sale\Create $creator)
	{
		$creator->sendNotifications();
	}
	
	/**
	 * @return \XF\Mvc\Reply\View
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionAdd(): \XF\Mvc\Reply\AbstractReply
	{
		$productRepo = $this->getProductRepo();
		if (!$productRepo->findProductsForList()->total())
		{
			throw $this->exception($this->error(\XF::phrase('dbtech_ecommerce_please_create_at_least_one_product_before_continuing')));
		}
		
		$copySaleId = $this->filter('source_sale_id', 'uint');
		if ($copySaleId)
		{
			$copySale = $this->assertSaleExists($copySaleId)->toArray(false);
			foreach ([
				'sale_id'
			] as $key)
			{
				unset($copySale[$key]);
			}
			
			/** @var \DBTech\eCommerce\Entity\Sale $sale */
			$sale = $this->em()->create('DBTech\eCommerce:Sale');
			$sale->bulkSet($copySale);
		}
		else
		{
			/** @var \DBTech\eCommerce\Entity\Sale $sale */
			$sale = $this->em()->create('DBTech\eCommerce:Sale');
		}
		
		return $this->saleAddEdit($sale);
	}
	
	/**
	 * @param \DBTech\eCommerce\Entity\Sale $sale
	 *
	 * @return \DBTech\eCommerce\Service\Sale\Edit
	 * @throws \Exception
	 */
	protected function setupSaleEdit(\DBTech\eCommerce\Entity\Sale $sale): \DBTech\eCommerce\Service\Sale\Edit
	{
		/** @var \DBTech\eCommerce\Service\Sale\Edit $editor */
		$editor = $this->service('DBTech\eCommerce:Sale\Edit', $sale);
		
		$bulkInput = $this->filter([
			'title' => 'str',
			'email_notify' => 'bool',
			'sale_type' => 'str',
			'sale_percent' => 'float',
			'sale_value' => 'float',
			'discount_excluded' => 'bool',
			'allow_auto_discount' => 'bool',
			'feature_products' => 'bool',
			'is_recurring' => 'bool',
			'recurring_length_amount' => 'uint',
			'recurring_length_unit' => 'str',
		]);
		$editor->getSale()->bulkSet($bulkInput);
		
		$editor->setDescription($this->filter('description', 'str'));
		
		$dateInput = $this->filter([
			'start_date' => 'datetime',
			'start_time' => 'str'
		]);
		$editor->setStartDate($dateInput['start_date'], $dateInput['start_time']);
		
		$dateInput = $this->filter([
			'end_date' => 'datetime',
			'end_time' => 'str'
		]);
		$editor->setEndDate($dateInput['end_date'], $dateInput['end_time']);
		
		$discounts = [];
		$args = $this->filter('product_discounts', 'array');
		foreach ($args AS $arg)
		{
			if (empty($arg['product_id']))
			{
				continue;
			}
			$discounts[] = $this->filterArray($arg, [
				'product_id' => 'uint',
				'product_value' => 'float',
			]);
		}
		
		$editor->setProductDiscounts($discounts);
		
		$dates = [];
		$args = $this->filter('other_dates', 'array');
		foreach ($args AS $arg)
		{
			if (empty($arg['start']) || empty($arg['end']))
			{
				continue;
			}
			$dates[] = $this->filterArray($arg, [
				'start' => 'datetime',
				'end' => 'datetime',
			]);
		}
		
		$editor->setOtherDates($dates);
		
		return $editor;
	}
	
	/**
	 * @param \DBTech\eCommerce\Service\Sale\Edit $editor
	 */
	protected function finalizeSaleEdit(\DBTech\eCommerce\Service\Sale\Edit $editor)
	{
	}

	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\Error|\XF\Mvc\Reply\Redirect
	 * @throws \LogicException
	 * @throws \XF\Mvc\Reply\Exception
	 * @throws \Exception
	 */
	public function actionSave(ParameterBag $params)
	{
		$this->assertPostOnly();

		if ($params->sale_id)
		{
			/** @var \DBTech\eCommerce\Entity\Sale $sale */
			$sale = $this->assertSaleExists($params->sale_id);
			
			/** @var \DBTech\eCommerce\Service\Sale\Edit $editor */
			$editor = $this->setupSaleEdit($sale);
			//			$editor->checkForSpam();
			
			if (!$editor->validate($errors))
			{
				return $this->error($errors);
			}
			$editor->save();
			$this->finalizeSaleEdit($editor);
		}
		else
		{
			/** @var \DBTech\eCommerce\Service\Sale\Create $creator */
			$creator = $this->setupSaleCreate();
			//			$creator->checkForSpam();
			
			if (!$creator->validate($errors))
			{
				throw $this->exception($this->error($errors));
			}
			
			/** @var \DBTech\eCommerce\Entity\Sale $sale */
			$sale = $creator->save();
			$this->finalizeSaleCreate($creator);
		}

		return $this->redirect($this->buildLink('dbtech-ecommerce/sales') . $this->buildLinkHash($sale->sale_id));
	}

	/**
	 * @param \XF\Mvc\ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\Redirect|\XF\Mvc\Reply\View
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionDelete(ParameterBag $params)
	{
		$sale = $this->assertSaleExists($params->sale_id);
		
		/** @var \DBTech\eCommerce\ControllerPlugin\Delete $plugin */
		$plugin = $this->plugin('DBTech\eCommerce:Delete');
		return $plugin->actionDeleteWithState(
			$sale,
			'sale_state',
			'DBTech\eCommerce:Sale\Delete',
			'dbtech_ecommerce_sale',
			$this->buildLink('dbtech-ecommerce/sales/delete', $sale),
			$this->buildLink('dbtech-ecommerce/sales/edit', $sale),
			$this->buildLink('dbtech-ecommerce/sales'),
			$sale->title,
			true,
			false
		);
	}

	/**
	 * @param int|null $id
	 * @param array|string|null $with
	 * @param null|string $phraseKey
	 *
	 * @return \DBTech\eCommerce\Entity\Sale
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function assertSaleExists(?int $id, $with = null, ?string $phraseKey = null): \DBTech\eCommerce\Entity\Sale
	{
		return $this->assertRecordExists('DBTech\eCommerce:Sale', $id, $with, $phraseKey);
	}

	/**
	 * @return \DBTech\eCommerce\Repository\Sale|\XF\Mvc\Entity\Repository
	 */
	protected function getSaleRepo()
	{
		return $this->repository('DBTech\eCommerce:Sale');
	}
	
	/**
	 * @return \DBTech\eCommerce\Repository\Product|\XF\Mvc\Entity\Repository
	 */
	protected function getProductRepo()
	{
		return $this->repository('DBTech\eCommerce:Product');
	}
	
	/**
	 * @return \DBTech\eCommerce\Repository\Category|\XF\Mvc\Entity\Repository
	 */
	protected function getCategoryRepo()
	{
		return $this->repository('DBTech\eCommerce:Category');
	}
}