<?php

namespace DBTech\eCommerce\Admin\Controller;

use XF\Admin\Controller\AbstractController;
use XF\Mvc\ParameterBag;

/**
 * Class Permission
 *
 * @package DBTech\eCommerce\Admin\Controller
 */
class Permission extends AbstractController
{
	/**
	 * @param $action
	 * @param ParameterBag $params
	 *
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function preDispatchController($action, ParameterBag $params)
	{
		switch ($action)
		{
			case 'Category':
				$this->assertAdminPermission('dbtechEcomCategory');
				break;
			
			case 'Coupon':
				$this->assertAdminPermission('dbtechEcomCoupon');
				break;
			
			case 'Product':
				$this->assertAdminPermission('dbtechEcomProduct');
				break;
		}
	}

	/**
	 * @return \DBTech\eCommerce\ControllerPlugin\CategoryPermission
	 */
	protected function getCategoryPermissionPlugin(): \DBTech\eCommerce\ControllerPlugin\CategoryPermission
	{
		/** @var \DBTech\eCommerce\ControllerPlugin\CategoryPermission $plugin */
		$plugin = $this->plugin('DBTech\eCommerce:CategoryPermission');
		$plugin->setFormatters('DBTech\eCommerce\Permission\Category%s', 'dbtech_ecommerce_permission_category_%s');
		$plugin->setRoutePrefix('permissions/dbtech-ecommerce-categories');

		return $plugin;
	}
	
	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\View
	 */
	public function actionCategory(ParameterBag $params): \XF\Mvc\Reply\AbstractReply
	{
		if ($params->category_id)
		{
			return $this->getCategoryPermissionPlugin()->actionList($params);
		}
		
		/** @var \DBTech\eCommerce\Repository\Category $categoryRepo */
		$categoryRepo = $this->repository('DBTech\eCommerce:Category');
		$categories = $categoryRepo->findCategoryList()->fetch();
		$categoryTree = $categoryRepo->createCategoryTree($categories);
		
		/** @var \XF\Repository\PermissionEntry $permissionEntryRepo */
		$permissionEntryRepo = $this->repository('XF:PermissionEntry');
		$customPermissions = $permissionEntryRepo->getContentWithCustomPermissions('dbtech_ecommerce_category');
		
		$viewParams = [
			'categoryTree' => $categoryTree,
			'customPermissions' => $customPermissions
		];
		return $this->view('DBTech\eCommerce:Permission\CategoryOverview', 'dbtech_ecommerce_permission_category_overview', $viewParams);
	}
	
	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\AbstractReply|\XF\Mvc\Reply\Redirect|\XF\Mvc\Reply\View
	 */
	public function actionCategoryEdit(ParameterBag $params)
	{
		return $this->getCategoryPermissionPlugin()->actionEdit($params);
	}
	
	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\Redirect
	 */
	public function actionCategorySave(ParameterBag $params): \XF\Mvc\Reply\Redirect
	{
		return $this->getCategoryPermissionPlugin()->actionSave($params);
	}
	
	/**
	 * @return \DBTech\eCommerce\ControllerPlugin\CouponPermission
	 */
	protected function getCouponPermissionPlugin(): \DBTech\eCommerce\ControllerPlugin\CouponPermission
	{
		/** @var \DBTech\eCommerce\ControllerPlugin\CouponPermission $plugin */
		$plugin = $this->plugin('DBTech\eCommerce:CouponPermission');
		$plugin->setFormatters('DBTech\eCommerce\Permission\Coupon%s', 'dbtech_ecommerce_permission_coupon_%s');
		$plugin->setRoutePrefix('permissions/dbtech-ecommerce-coupons');
		
		return $plugin;
	}
	
	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\View
	 */
	public function actionCoupon(ParameterBag $params): \XF\Mvc\Reply\AbstractReply
	{
		if ($params->coupon_id)
		{
			return $this->getCouponPermissionPlugin()->actionList($params);
		}
		
		/** @var \DBTech\eCommerce\Repository\Coupon $couponRepo */
		$couponRepo = $this->repository('DBTech\eCommerce:Coupon');
		$coupons = $couponRepo->findCouponsForList()->fetch();
		
		/** @var \XF\Repository\PermissionEntry $permissionEntryRepo */
		$permissionEntryRepo = $this->repository('XF:PermissionEntry');
		$customPermissions = $permissionEntryRepo->getContentWithCustomPermissions('dbtech_ecommerce_coupon');
		
		$viewParams = [
			'coupons' => $coupons,
			'customPermissions' => $customPermissions
		];
		return $this->view('DBTech\eCommerce:Permission\CouponOverview', 'dbtech_ecommerce_permission_coupon_overview', $viewParams);
	}
	
	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\AbstractReply|\XF\Mvc\Reply\Redirect|\XF\Mvc\Reply\View
	 */
	public function actionCouponEdit(ParameterBag $params)
	{
		return $this->getCouponPermissionPlugin()->actionEdit($params);
	}
	
	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\Redirect
	 */
	public function actionCouponSave(ParameterBag $params): \XF\Mvc\Reply\Redirect
	{
		return $this->getCouponPermissionPlugin()->actionSave($params);
	}
	
	/**
	 * @return \DBTech\eCommerce\ControllerPlugin\ProductPermission
	 */
	protected function getProductPermissionPlugin(): \DBTech\eCommerce\ControllerPlugin\ProductPermission
	{
		/** @var \DBTech\eCommerce\ControllerPlugin\ProductPermission $plugin */
		$plugin = $this->plugin('DBTech\eCommerce:ProductPermission');
		$plugin->setFormatters('DBTech\eCommerce\Permission\Product%s', 'dbtech_ecommerce_permission_product_%s');
		$plugin->setRoutePrefix('permissions/dbtech-ecommerce-products');
		
		return $plugin;
	}
	
	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\View
	 */
	public function actionProduct(ParameterBag $params): \XF\Mvc\Reply\AbstractReply
	{
		if ($params->product_id)
		{
			return $this->getProductPermissionPlugin()->actionList($params);
		}
		
		/** @var \DBTech\eCommerce\Repository\Product $productRepo */
		$productRepo = $this->repository('DBTech\eCommerce:Product');
		$products = $productRepo->findProductsForList()->fetch();
		
		/** @var \XF\Repository\PermissionEntry $permissionEntryRepo */
		$permissionEntryRepo = $this->repository('XF:PermissionEntry');
		$customPermissions = $permissionEntryRepo->getContentWithCustomPermissions('dbtech_ecommerce_product');
		
		$viewParams = [
			'products' => $products,
			'customPermissions' => $customPermissions
		];
		return $this->view('DBTech\eCommerce:Permission\ProductOverview', 'dbtech_ecommerce_permission_product_overview', $viewParams);
	}
	
	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\AbstractReply|\XF\Mvc\Reply\Redirect|\XF\Mvc\Reply\View
	 */
	public function actionProductEdit(ParameterBag $params)
	{
		return $this->getProductPermissionPlugin()->actionEdit($params);
	}
	
	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\Redirect
	 */
	public function actionProductSave(ParameterBag $params): \XF\Mvc\Reply\Redirect
	{
		return $this->getProductPermissionPlugin()->actionSave($params);
	}
}