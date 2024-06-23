<?php

namespace DBTech\eCommerce\Admin\Controller;

use XF\Admin\Controller\AbstractController;
use XF\Mvc\ParameterBag;

/**
 * Class Coupon
 * @package DBTech\eCommerce\Admin\Controller
 */
class Coupon extends AbstractController
{
	/**
	 * @param $action
	 * @param ParameterBag $params
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function preDispatchController($action, ParameterBag $params)
	{
		$this->assertAdminPermission('dbtechEcomCoupon');
	}

	/**
	 * @return \XF\Mvc\Reply\View
	 */
	public function actionIndex(): \XF\Mvc\Reply\AbstractReply
	{
		/** @var \DBTech\eCommerce\Repository\Coupon $couponRepo */
		$couponRepo = $this->repository('DBTech\eCommerce:Coupon');
		$coupons = $couponRepo->findCouponsForList()->fetch();
		
		/** @var \XF\Repository\PermissionEntry $permissionEntryRepo */
		$permissionEntryRepo = $this->repository('XF:PermissionEntry');
		$customPermissions = $permissionEntryRepo->getContentWithCustomPermissions('dbtech_ecommerce_coupon');
		
		$options = $this->em()->find('XF:Option', 'dbtechEcommerceCoupons');
		
		$viewParams = [
			'coupons' => $coupons,
			'customPermissions' => $customPermissions,
			'options' => [$options]
		];
		return $this->view('DBTech\eCommerce:Coupon\Listing', 'dbtech_ecommerce_coupon_list', $viewParams);
	}

	/**
	 * @param \DBTech\eCommerce\Entity\Coupon $coupon
	 * @return \XF\Mvc\Reply\View
	 */
	protected function couponAddEdit(\DBTech\eCommerce\Entity\Coupon $coupon): \XF\Mvc\Reply\AbstractReply
	{
		$viewParams = [
			'coupon' => $coupon,
			'nextCounter' => count($coupon->product_discounts),
		];
		return $this->view('DBTech\eCommerce:Coupon\Edit', 'dbtech_ecommerce_coupon_edit', $viewParams);
	}

	/**
	 * @param ParameterBag $params
	 * @return \XF\Mvc\Reply\View
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionEdit(ParameterBag $params): \XF\Mvc\Reply\AbstractReply
	{
		/** @var \DBTech\eCommerce\Entity\Coupon $coupon */
		$coupon = $this->assertCouponExists($params->coupon_id);
		return $this->couponAddEdit($coupon);
	}

	/**
	 * @return \DBTech\eCommerce\Service\Coupon\Create
	 * @throws \Exception
	 * @throws \Exception
	 * @throws \Exception
	 */
	protected function setupCouponCreate(): \DBTech\eCommerce\Service\Coupon\Create
	{
		/** @var \DBTech\eCommerce\Service\Coupon\Create $creator */
		$creator = $this->service('DBTech\eCommerce:Coupon\Create');
		
		$bulkInput = $this->filter([
			'coupon_code' => 'str',
			'coupon_type' => 'str',
			'coupon_percent' => 'float',
			'coupon_value' => 'float',
			'discount_excluded' => 'bool',
			'allow_auto_discount' => 'bool',
			'remaining_uses' => 'int',
			'minimum_products' => 'uint',
			'maximum_products' => 'uint',
			'minimum_cart_value' => 'float',
			'maximum_cart_value' => 'float',
		]);
		$creator->getCoupon()->bulkSet($bulkInput);
		
		$creator->setTitle($this->filter('title', 'str'));
		
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
		
		return $creator;
	}
	
	/**
	 * @param \DBTech\eCommerce\Service\Coupon\Create $creator
	 */
	protected function finalizeCouponCreate(\DBTech\eCommerce\Service\Coupon\Create $creator)
	{
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
		
		$copyCouponId = $this->filter('source_coupon_id', 'uint');
		if ($copyCouponId)
		{
			$copyCoupon = $this->assertCouponExists($copyCouponId)->toArray(false);
			foreach ([
				'coupon_id'
			] as $key)
			{
				unset($copyCoupon[$key]);
			}
			
			/** @var \DBTech\eCommerce\Entity\Coupon $coupon */
			$coupon = $this->em()->create('DBTech\eCommerce:Coupon');
			$coupon->bulkSet($copyCoupon);
		}
		else
		{
			/** @var \DBTech\eCommerce\Entity\Coupon $coupon */
			$coupon = $this->em()->create('DBTech\eCommerce:Coupon');
		}
		
		return $this->couponAddEdit($coupon);
	}

	/**
	 * @param \DBTech\eCommerce\Entity\Coupon $coupon
	 *
	 * @return \DBTech\eCommerce\Service\Coupon\Edit
	 * @throws \Exception
	 * @throws \Exception
	 * @throws \Exception
	 * @throws \Exception
	 */
	protected function setupCouponEdit(\DBTech\eCommerce\Entity\Coupon $coupon): \DBTech\eCommerce\Service\Coupon\Edit
	{
		/** @var \DBTech\eCommerce\Service\Coupon\Edit $editor */
		$editor = $this->service('DBTech\eCommerce:Coupon\Edit', $coupon);
		
		$bulkInput = $this->filter([
			'coupon_code' => 'str',
			'coupon_type' => 'str',
			'coupon_percent' => 'float',
			'coupon_value' => 'float',
			'discount_excluded' => 'bool',
			'allow_auto_discount' => 'bool',
			'remaining_uses' => 'int',
			'minimum_products' => 'uint',
			'maximum_products' => 'uint',
			'minimum_cart_value' => 'float',
			'maximum_cart_value' => 'float',
		]);
		$editor->getCoupon()->bulkSet($bulkInput);
		
		$editor->setTitle($this->filter('title', 'str'));
		
		$dateInput = $this->filter([
			'start_date' => 'datetime',
			'start_time' => 'str'
		]);
		$editor->setStartDate($dateInput['start_date'], $dateInput['start_time']);
		
		$dateInput = $this->filter([
			'expiry_date' => 'datetime',
			'expiry_time' => 'str'
		]);
		$editor->setExpiryDate($dateInput['expiry_date'], $dateInput['expiry_time']);
		
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
		
		return $editor;
	}
	
	/**
	 * @param \DBTech\eCommerce\Service\Coupon\Edit $editor
	 */
	protected function finalizeCouponEdit(\DBTech\eCommerce\Service\Coupon\Edit $editor)
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
		
		if ($params->coupon_id)
		{
			/** @var \DBTech\eCommerce\Entity\Coupon $coupon */
			$coupon = $this->assertCouponExists($params->coupon_id);
			
			/** @var \DBTech\eCommerce\Service\Coupon\Edit $editor */
			$editor = $this->setupCouponEdit($coupon);
			//			$editor->checkForSpam();
			
			if (!$editor->validate($errors))
			{
				return $this->error($errors);
			}
			$editor->save();
			$this->finalizeCouponEdit($editor);
		}
		else
		{
			/** @var \DBTech\eCommerce\Service\Coupon\Create $creator */
			$creator = $this->setupCouponCreate();
			//			$creator->checkForSpam();
			
			if (!$creator->validate($errors))
			{
				throw $this->exception($this->error($errors));
			}
			
			/** @var \DBTech\eCommerce\Entity\Coupon $coupon */
			$coupon = $creator->save();
			$this->finalizeCouponCreate($creator);
		}


		return $this->redirect($this->buildLink('dbtech-ecommerce/coupons') . $this->buildLinkHash($coupon->coupon_id));
	}
	
	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\Redirect|\XF\Mvc\Reply\View
	 * @throws \InvalidArgumentException
	 * @throws \LogicException
	 * @throws \Exception
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionDelete(ParameterBag $params)
	{
		$coupon = $this->assertCouponExists($params->coupon_id);
		
		/** @var \DBTech\eCommerce\ControllerPlugin\Delete $plugin */
		$plugin = $this->plugin('DBTech\eCommerce:Delete');
		return $plugin->actionDeleteWithState(
			$coupon,
			'coupon_state',
			'DBTech\eCommerce:Coupon\Delete',
			'dbtech_ecommerce_coupon',
			$this->buildLink('dbtech-ecommerce/coupons/delete', $coupon),
			$this->buildLink('dbtech-ecommerce/coupons/edit', $coupon),
			$this->buildLink('dbtech-ecommerce/coupons'),
			$coupon->title,
			true,
			false
		);
	}
	
	/**
	 * @return \DBTech\eCommerce\ControllerPlugin\CouponPermission
	 */
	protected function getCouponPermissionPlugin(): \DBTech\eCommerce\ControllerPlugin\CouponPermission
	{
		/** @var \DBTech\eCommerce\ControllerPlugin\CouponPermission $plugin */
		$plugin = $this->plugin('DBTech\eCommerce:CouponPermission');
		$plugin->setFormatters('DBTech\eCommerce:Coupon\Permission%s', 'dbtech_ecommerce_coupon_permission_%s');
		$plugin->setRoutePrefix('dbtech-ecommerce/coupons/permissions');
		
		return $plugin;
	}
	
	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\View
	 */
	public function actionPermissions(ParameterBag $params): \XF\Mvc\Reply\AbstractReply
	{
		return $this->getCouponPermissionPlugin()->actionList($params);
	}
	
	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\AbstractReply|\XF\Mvc\Reply\Redirect|\XF\Mvc\Reply\View
	 */
	public function actionPermissionsEdit(ParameterBag $params)
	{
		return $this->getCouponPermissionPlugin()->actionEdit($params);
	}
	
	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\Redirect
	 */
	public function actionPermissionsSave(ParameterBag $params): \XF\Mvc\Reply\Redirect
	{
		return $this->getCouponPermissionPlugin()->actionSave($params);
	}

	/**
	 * @param int|null $id
	 * @param null $with
	 * @param string|null $phraseKey
	 *
	 * @return \DBTech\eCommerce\Entity\Coupon
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function assertCouponExists(?int $id, $with = null, ?string $phraseKey = null): \DBTech\eCommerce\Entity\Coupon
	{
		return $this->assertRecordExists('DBTech\eCommerce:Coupon', $id, $with, $phraseKey);
	}

	/**
	 * @return \DBTech\eCommerce\Repository\Coupon
	 */
	protected function getCouponRepo(): \DBTech\eCommerce\Repository\Coupon
	{
		return $this->repository('DBTech\eCommerce:Coupon');
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