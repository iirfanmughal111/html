<?php

namespace DBTech\eCommerce\ControllerPlugin;

use XF\ControllerPlugin\AbstractPermission;

/**
 * Class CouponPermission
 *
 * @package DBTech\eCommerce\ControllerPlugin
 */
class CouponPermission extends AbstractPermission
{
	/**
	 * @var string
	 */
	protected $viewFormatter = 'DBTech\eCommerce:Permission\Coupon%s';

	/**
	 * @var string
	 */
	protected $templateFormatter = 'dbtech_ecommerce_permission_coupon_%s';

	/**
	 * @var string
	 */
	protected $routePrefix = 'permissions/dbtech-ecommerce-coupons';

	/**
	 * @var string
	 */
	protected $contentType = 'dbtech_ecommerce_coupon';

	/**
	 * @var string
	 */
	protected $entityIdentifier = 'DBTech\eCommerce:Coupon';

	/**
	 * @var string
	 */
	protected $primaryKey = 'coupon_id';

	/**
	 * @var string
	 */
	protected $privatePermissionGroupId = 'dbtechEcommerce';

	/**
	 * @var string
	 */
	protected $privatePermissionId = 'useCoupons';
}