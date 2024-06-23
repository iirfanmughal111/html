<?php

namespace DBTech\Credits\Admin\Controller;

use XF\Admin\Controller\AbstractController;
use XF\Mvc\FormAction;
use XF\Mvc\ParameterBag;

/**
 * Class Currency
 * @package DBTech\Credits\Admin\Controller
 */
class Currency extends AbstractController
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
		$currencies = $this->getCurrencyRepo()->findCurrenciesForList()->fetch();

		$viewParams = [
			'currencies' => $currencies,
		];
		return $this->view('DBTech\Credits:Currency\Listing', 'dbtech_credits_currency_list', $viewParams);
	}

	/**
	 * @param \DBTech\Credits\Entity\Currency $currency
	 * @return \XF\Mvc\Reply\View
	 */
	protected function currencyAddEdit(\DBTech\Credits\Entity\Currency $currency): \XF\Mvc\Reply\AbstractReply
	{
		$viewParams = [
			'currency' => $currency,
		];
		return $this->view('DBTech\Credits:Currency\Edit', 'dbtech_credits_currency_edit', $viewParams);
	}

	/**
	 * @param ParameterBag $params
	 * @return \XF\Mvc\Reply\View
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionEdit(ParameterBag $params): \XF\Mvc\Reply\AbstractReply
	{
		/** @var \DBTech\Credits\Entity\Currency $currency */
		$currency = $this->assertCurrencyExists($params->currency_id);
		return $this->currencyAddEdit($currency);
	}
	
	/**
	 * @return \XF\Mvc\Reply\View
	 */
	public function actionAdd(): \XF\Mvc\Reply\AbstractReply
	{
		/** @var \DBTech\Credits\Entity\Currency $currency */
		$currency = $this->em()->create('DBTech\Credits:Currency');
		
		return $this->currencyAddEdit($currency);
	}
	
	/**
	 * @param \DBTech\Credits\Entity\Currency $currency
	 *
	 * @return FormAction
	 */
	protected function currencySaveProcess(\DBTech\Credits\Entity\Currency $currency): FormAction
	{
		$form = $this->formAction();
		
		$input = $this->filter([
			'title' => 'str',
			'display_order' => 'uint',
			'active' => 'bool',
			
			'column' => 'str',
			'decimals' => 'uint',
			'negative' => 'uint',
			'privacy' => 'uint',
			'prefix' => 'str',
			'suffix' => 'str',
			'is_display_currency' => 'bool',
			'show_amounts' => 'bool',
			'sidebar' => 'bool',
			'member_dropdown' => 'bool',
			'postbit' => 'bool',
			
			'earnmax' => 'unum',
			'maxtime' => 'uint',
			'value' => 'num',
			'inbound' => 'bool',
			'outbound' => 'bool'
		]);
		
		$input['description'] = $this->plugin('XF:Editor')->fromInput('description');
		
		$form->basicEntitySave($currency, $input);
		
		return $form;
	}
	
	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\Redirect
	 * @throws \XF\Mvc\Reply\Exception
	 * @throws \XF\PrintableException
	 */
	public function actionSave(ParameterBag $params): \XF\Mvc\Reply\Redirect
	{
		$this->assertPostOnly();
		
		if ($params->currency_id)
		{
			/** @var \DBTech\Credits\Entity\Currency $currency */
			$currency = $this->assertCurrencyExists($params->currency_id);
		}
		else
		{
			/** @var \DBTech\Credits\Entity\Currency $currency */
			$currency = $this->em()->create('DBTech\Credits:Currency');
		}
		
		$this->currencySaveProcess($currency)->run();

		return $this->redirect($this->buildLink('dbtech-credits/currencies') . $this->buildLinkHash($currency->currency_id));
	}
	
	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\Error|\XF\Mvc\Reply\Redirect|\XF\Mvc\Reply\View
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionDelete(ParameterBag $params)
	{
		$currency = $this->assertCurrencyExists($params->currency_id);
		
		/** @var \XF\ControllerPlugin\Delete $plugin */
		$plugin = $this->plugin('XF:Delete');
		return $plugin->actionDelete(
			$currency,
			$this->buildLink('dbtech-credits/currencies/delete', $currency),
			$this->buildLink('dbtech-credits/currencies/edit', $currency),
			$this->buildLink('dbtech-credits/currencies'),
			$currency->currency_id
		);
	}
	
	/**
	 * @return \XF\Mvc\Reply\Message
	 */
	public function actionToggle(): \XF\Mvc\Reply\Message
	{
		/** @var \XF\ControllerPlugin\Toggle $plugin */
		$plugin = $this->plugin('XF:Toggle');
		return $plugin->actionToggle('DBTech\Credits:Currency', 'active');
	}

	/**
	 * @param string $id
	 * @param array|string|null $with
	 * @param null|string $phraseKey
	 *
	 * @return \DBTech\Credits\Entity\Currency|\XF\Mvc\Entity\Entity
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function assertCurrencyExists($id, $with = null, $phraseKey = null)
	{
		return $this->assertRecordExists('DBTech\Credits:Currency', $id, $with, $phraseKey);
	}

	/**
	 * @return \DBTech\Credits\Repository\Currency|\XF\Mvc\Entity\Repository
	 */
	protected function getCurrencyRepo()
	{
		return $this->repository('DBTech\Credits:Currency');
	}
}