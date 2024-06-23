<?php

namespace DBTech\eCommerce\Admin\Controller;

use XF\Admin\Controller\AbstractController;
use XF\Mvc\ParameterBag;

/**
 * Class SalesTax
 * @package DBTech\eCommerce\Admin\Controller
 */
class SalesTax extends AbstractController
{
	/**
	 * @param $action
	 * @param ParameterBag $params
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function preDispatchController($action, ParameterBag $params)
	{
		$this->assertAdminPermission('dbtechEcomSalesTax');
	}

	/**
	 * @return \XF\Mvc\Reply\View
	 */
	public function actionIndex(): \XF\Mvc\Reply\AbstractReply
	{
		$countries = $this->getCountryRepo()->findCountriesWithTaxForList()->fetch();
		
		$options = $this->em()->find('XF:Option', 'dbtechEcommerceSalesTax');

		$viewParams = [
			'countries' => $countries,
			'options' => [$options]
		];
		return $this->view('DBTech\eCommerce:Sale\Listing', 'dbtech_ecommerce_sales_tax_list', $viewParams);
	}

	/**
	 * @param ParameterBag $params
	 * @return \XF\Mvc\Reply\View
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionEdit(ParameterBag $params): \XF\Mvc\Reply\AbstractReply
	{
		/** @var \DBTech\eCommerce\Entity\Country $country */
		$country = $this->assertCountryExists($params->country_code);
		
		$viewParams = [
			'country' => $country,
		];
		return $this->view('DBTech\eCommerce:SalesTax\Edit', 'dbtech_ecommerce_sales_tax_edit', $viewParams);
	}
	
	/**
	 * @return \XF\Mvc\Reply\View
	 */
	public function actionAdd(): \XF\Mvc\Reply\AbstractReply
	{
		$countries = $this->getCountryRepo()->findCountriesForList()->fetch();
		
		$viewParams = [
			'countries' => $countries->pluckNamed('name', 'country_code'),
		];
		return $this->view('DBTech\eCommerce:SalesTax\Add', 'dbtech_ecommerce_sales_tax_add', $viewParams);
	}
	
	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\Error|\XF\Mvc\Reply\Redirect
	 * @throws \LogicException
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionSave(ParameterBag $params)
	{
		$this->assertPostOnly();

		if ($params->country_code)
		{
			/** @var \DBTech\eCommerce\Entity\Country $country */
			$country = $this->assertCountryExists($params->country_code);
		}
		else
		{
			/** @var \DBTech\eCommerce\Entity\Country $country */
			$country = $this->assertCountryExists($this->filter('country_code', 'str'));
		}
		
		$country->fastUpdate('sales_tax_rate', $this->filter('sales_tax_rate', 'float'));

		return $this->redirect($this->buildLink('dbtech-ecommerce/sales-tax') . $this->buildLinkHash($country->country_code));
	}

	/**
	 * @param string|null $id
	 * @param array|string|null $with
	 * @param null|string $phraseKey
	 *
	 * @return \DBTech\eCommerce\Entity\Country
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function assertCountryExists(?string $id, $with = null, ?string $phraseKey = null): \DBTech\eCommerce\Entity\Country
	{
		return $this->assertRecordExists('DBTech\eCommerce:Country', $id, $with, $phraseKey);
	}

	/**
	 * @return \DBTech\eCommerce\Repository\Country|\XF\Mvc\Entity\Repository
	 */
	protected function getCountryRepo()
	{
		return $this->repository('DBTech\eCommerce:Country');
	}
}