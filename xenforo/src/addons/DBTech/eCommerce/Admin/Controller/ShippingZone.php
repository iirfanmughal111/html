<?php

namespace DBTech\eCommerce\Admin\Controller;

use XF\Admin\Controller\AbstractController;
use XF\Mvc\ParameterBag;

/**
 * Class ShippingZone
 *
 * @package DBTech\eCommerce\Admin\Controller
 */
class ShippingZone extends AbstractController
{
	/**
	 * @param $action
	 * @param ParameterBag $params
	 *
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function preDispatchController($action, ParameterBag $params)
	{
		$this->assertAdminPermission('dbtechEcomProduct');
		
		$countryRepo = $this->getCountryRepo();
		if (!$countryRepo->findCountriesForList()->total())
		{
			throw $this->exception($this->error(\XF::phrase('dbtech_ecommerce_please_create_at_least_one_country_before_continuing')));
		}
		
		$shippingMethodRepo = $this->getShippingMethodRepo();
		if (!$shippingMethodRepo->findShippingMethodsForList()->total())
		{
			throw $this->exception($this->error(\XF::phrase('dbtech_ecommerce_please_create_at_least_one_shipping_method_before_continuing')));
		}
	}
	
	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\Redirect|\XF\Mvc\Reply\View
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionIndex(ParameterBag $params)
	{
		if ($params['shipping_zone_id'])
		{
			$shippingZone = $this->assertShippingZoneExists($params['shipping_zone_id']);
			return $this->redirect($this->buildLink('dbtech-ecommerce/shipping-zones/edit', $shippingZone));
		}
		
		$shippingZoneRepo = $this->getShippingZoneRepo();
		$shippingZones = $shippingZoneRepo->findShippingZonesForList()->fetch();
		
		$viewParams = [
			'shippingZones' => $shippingZones,
			'totalShippingZones' => $shippingZones->count()
		];
		return $this->view('DBTech\eCommerce:ShippingZone\Listing', 'dbtech_ecommerce_shipping_zone_list', $viewParams);
	}
	
	/**
	 * @param \DBTech\eCommerce\Entity\ShippingZone $shippingZone
	 *
	 * @return \XF\Mvc\Reply\View
	 */
	protected function actionAddEdit(\DBTech\eCommerce\Entity\ShippingZone $shippingZone): \XF\Mvc\Reply\AbstractReply
	{
		$viewParams = [
			'shippingZone' => $shippingZone,
			
			'countries' => $this->getCountryRepo()->getCountrySelectData(false),
			'shippingMethods' => $this->getShippingMethodRepo()->getShippingMethodTitlePairs(),
		];
		return $this->view('DBTech\eCommerce:ShippingZone\Edit', 'dbtech_ecommerce_shipping_zone_edit', $viewParams);
	}
	
	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\View
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionEdit(ParameterBag $params): \XF\Mvc\Reply\AbstractReply
	{
		$shippingZone = $this->assertShippingZoneExists($params->shipping_zone_id);
		return $this->actionAddEdit($shippingZone);
	}
	
	/**
	 * @return \XF\Mvc\Reply\View
	 */
	public function actionAdd(): \XF\Mvc\Reply\AbstractReply
	{
		/** @var \DBTech\eCommerce\Entity\ShippingZone $shippingZone */
		$shippingZone = $this->em()->create('DBTech\eCommerce:ShippingZone');
		return $this->actionAddEdit($shippingZone);
	}
	
	/**
	 * @param \DBTech\eCommerce\Entity\ShippingZone $shippingZone
	 *
	 * @return \XF\Mvc\FormAction
	 */
	protected function actionSaveProcess(\DBTech\eCommerce\Entity\ShippingZone $shippingZone): \XF\Mvc\FormAction
	{
		$form = $this->formAction();

		$input = $this->filter([
			'title' => 'str',
			'active' => 'bool',
			'display_order' => 'int',
		]);
		
		$form->basicEntitySave($shippingZone, $input);
		
		$repoInput = $this->filter([
			'shipping_methods' => 'array-uint',
			'countries' => 'array-str'
		]);

		$form->complete(function () use ($shippingZone, $repoInput)
		{
			/** @var \DBTech\eCommerce\Repository\ShippingMethodShippingZoneMap $shippingMethodMapRepo */
			$shippingMethodMapRepo = $this->repository('DBTech\eCommerce:ShippingMethodShippingZoneMap');
			$shippingMethodMapRepo->updateShippingZoneAssociations($shippingZone->shipping_zone_id, $repoInput['shipping_methods']);
			
			/** @var \DBTech\eCommerce\Repository\CountryShippingZoneMap $countryMapRepo */
			$countryMapRepo = $this->repository('DBTech\eCommerce:CountryShippingZoneMap');
			$countryMapRepo->updateShippingZoneAssociations($shippingZone->shipping_zone_id, $repoInput['countries']);
			
			$shippingZone->rebuildShippingCombination();
		});

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

		if ($params->shipping_zone_id)
		{
			$shippingZone = $this->assertShippingZoneExists($params->shipping_zone_id);
		}
		else
		{
			$shippingZone = $this->em()->create('DBTech\eCommerce:ShippingZone');
		}

		$this->actionSaveProcess($shippingZone)->run();

		return $this->redirect($this->buildLink('dbtech-ecommerce/shipping-zones') . $this->buildLinkHash($shippingZone->shipping_zone_id));
	}
	
	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\Error|\XF\Mvc\Reply\Redirect|\XF\Mvc\Reply\View
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionDelete(ParameterBag $params)
	{
		$shippingZone = $this->assertShippingZoneExists($params->shipping_zone_id);
		
		$deleteParams = [];
		if (!$this->isPost())
		{
			$deleteParams['numOrders'] = $this->finder('DBTech\eCommerce:OrderItem')->where('shipping_method_id', $shippingZone->shipping_methods)->total();
		}
		
		/** @var \XF\ControllerPlugin\Delete $plugin */
		$plugin = $this->plugin('XF:Delete');
		return $plugin->actionDelete(
			$shippingZone,
			$this->buildLink('dbtech-ecommerce/shipping-zones/delete', $shippingZone),
			$this->buildLink('dbtech-ecommerce/shipping-zones/edit', $shippingZone),
			$this->buildLink('dbtech-ecommerce/shipping-zones'),
			$shippingZone->title,
			'dbtech_ecommerce_shipping_zone_delete',
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
		return $plugin->actionToggle('DBTech\eCommerce:ShippingZone');
	}
	
	/**
	 * @param int|null $id
	 * @param array|string|null $with
	 * @param null|string $phraseKey
	 *
	 * @return \DBTech\eCommerce\Entity\ShippingZone
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function assertShippingZoneExists(?int $id, $with = null, ?string $phraseKey = null): \DBTech\eCommerce\Entity\ShippingZone
	{
		return $this->assertRecordExists('DBTech\eCommerce:ShippingZone', $id, $with, $phraseKey);
	}
	
	/**
	 * @return \DBTech\eCommerce\Repository\ShippingZone
	 */
	protected function getShippingZoneRepo(): \DBTech\eCommerce\Repository\ShippingZone
	{
		return $this->repository('DBTech\eCommerce:ShippingZone');
	}
	
	/**
	 * @return \DBTech\eCommerce\Repository\Country
	 */
	protected function getCountryRepo(): \DBTech\eCommerce\Repository\Country
	{
		return $this->repository('DBTech\eCommerce:Country');
	}
	
	/**
	 * @return \DBTech\eCommerce\Repository\ShippingMethod
	 */
	protected function getShippingMethodRepo(): \DBTech\eCommerce\Repository\ShippingMethod
	{
		return $this->repository('DBTech\eCommerce:ShippingMethod');
	}
}