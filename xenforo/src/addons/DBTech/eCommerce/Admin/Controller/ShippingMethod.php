<?php

namespace DBTech\eCommerce\Admin\Controller;

use XF\Admin\Controller\AbstractController;
use XF\Mvc\ParameterBag;

/**
 * Class ShippingMethod
 *
 * @package DBTech\eCommerce\Admin\Controller
 */
class ShippingMethod extends AbstractController
{
	/**
	 * @param $shippingMethod
	 * @param ParameterBag $params
	 *
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function preDispatchController($shippingMethod, ParameterBag $params)
	{
		$this->assertAdminPermission('dbtechEcomProduct');
	}
	
	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\Redirect|\XF\Mvc\Reply\View
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionIndex(ParameterBag $params)
	{
		if ($params['shipping_method_id'])
		{
			$shippingMethod = $this->assertShippingMethodExists($params['shipping_method_id']);
			return $this->redirect($this->buildLink('dbtech-ecommerce/shipping-methods/edit', $shippingMethod));
		}
		
		$shippingMethodRepo = $this->getShippingMethodRepo();
		$shippingMethods = $shippingMethodRepo->findShippingMethodsForList()->fetch();
		
		$viewParams = [
			'shippingMethods' => $shippingMethods,
			'totalShippingMethods' => $shippingMethods->count()
		];
		return $this->view('DBTech\eCommerce:ShippingMethod\Listing', 'dbtech_ecommerce_shipping_method_list', $viewParams);
	}
	
	/**
	 * @param \DBTech\eCommerce\Entity\ShippingMethod $shippingMethod
	 *
	 * @return \XF\Mvc\Reply\View
	 */
	protected function actionAddEdit(\DBTech\eCommerce\Entity\ShippingMethod $shippingMethod): \XF\Mvc\Reply\AbstractReply
	{
		$viewParams = [
			'shippingMethod' => $shippingMethod,
		];
		return $this->view('DBTech\eCommerce:ShippingMethod\Edit', 'dbtech_ecommerce_shipping_method_edit', $viewParams);
	}
	
	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\View
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionEdit(ParameterBag $params): \XF\Mvc\Reply\AbstractReply
	{
		$shippingMethod = $this->assertShippingMethodExists($params->shipping_method_id);
		return $this->actionAddEdit($shippingMethod);
	}
	
	/**
	 * @return \XF\Mvc\Reply\View
	 */
	public function actionAdd(): \XF\Mvc\Reply\AbstractReply
	{
		/** @var \DBTech\eCommerce\Entity\ShippingMethod $shippingMethod */
		$shippingMethod = $this->em()->create('DBTech\eCommerce:ShippingMethod');
		return $this->actionAddEdit($shippingMethod);
	}
	
	/**
	 * @param \DBTech\eCommerce\Entity\ShippingMethod $shippingMethod
	 *
	 * @return \XF\Mvc\FormAction
	 */
	protected function actionSaveProcess(\DBTech\eCommerce\Entity\ShippingMethod $shippingMethod): \XF\Mvc\FormAction
	{
		$form = $this->formAction();

		$input = $this->filter([
			'title' => 'str',
			'active' => 'bool',
			'display_order' => 'int',
			'cost_formula' => 'str'
		]);
		
		$form->basicEntitySave($shippingMethod, $input);

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

		if ($params->shipping_method_id)
		{
			$shippingMethod = $this->assertShippingMethodExists($params->shipping_method_id);
		}
		else
		{
			$shippingMethod = $this->em()->create('DBTech\eCommerce:ShippingMethod');
		}

		$this->actionSaveProcess($shippingMethod)->run();

		return $this->redirect($this->buildLink('dbtech-ecommerce/shipping-methods') . $this->buildLinkHash($shippingMethod->shipping_method_id));
	}
	
	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\Error|\XF\Mvc\Reply\Redirect|\XF\Mvc\Reply\View
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionDelete(ParameterBag $params)
	{
		$shippingMethod = $this->assertShippingMethodExists($params->shipping_method_id);
		
		$deleteParams = [];
		if (!$this->isPost())
		{
			$deleteParams['numOrders'] = $this->finder('DBTech\eCommerce:OrderItem')->where('shipping_method_id', $shippingMethod->shipping_method_id)->total();
		}
		
		/** @var \XF\ControllerPlugin\Delete $plugin */
		$plugin = $this->plugin('XF:Delete');
		return $plugin->actionDelete(
			$shippingMethod,
			$this->buildLink('dbtech-ecommerce/shipping-methods/delete', $shippingMethod),
			$this->buildLink('dbtech-ecommerce/shipping-methods/edit', $shippingMethod),
			$this->buildLink('dbtech-ecommerce/shipping-methods'),
			$shippingMethod->title,
			'dbtech_ecommerce_shipping_method_delete',
			$deleteParams
		);
	}
	
	/**
	 * @return \XF\Mvc\Reply\Message
	 */
	public function actionToggle(): \XF\Mvc\Reply\Message
	{
		/** @var \XF\ControllerPlugin\Toggle $plugin */
		$plugin = $this->plugin('XF:Toggle');
		return $plugin->actionToggle('DBTech\eCommerce:ShippingMethod');
	}
	
	/**
	 * @param int|null $id
	 * @param array|string|null $with
	 * @param null|string $phraseKey
	 *
	 * @return \DBTech\eCommerce\Entity\ShippingMethod
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function assertShippingMethodExists(?int $id, $with = null, ?string $phraseKey = null): \DBTech\eCommerce\Entity\ShippingMethod
	{
		return $this->assertRecordExists('DBTech\eCommerce:ShippingMethod', $id, $with, $phraseKey);
	}

	/**
	 * @return \DBTech\eCommerce\Repository\ShippingMethod
	 */
	protected function getShippingMethodRepo(): \DBTech\eCommerce\Repository\ShippingMethod
	{
		return $this->repository('DBTech\eCommerce:ShippingMethod');
	}
}