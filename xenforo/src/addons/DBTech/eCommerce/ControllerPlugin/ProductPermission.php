<?php

namespace DBTech\eCommerce\ControllerPlugin;

use XF\ControllerPlugin\AbstractPermission;

/**
 * Class ProductPermission
 *
 * @package DBTech\eCommerce\ControllerPlugin
 */
class ProductPermission extends AbstractPermission
{
	/**
	 * @var string
	 */
	protected $viewFormatter = 'DBTech\eCommerce:Permission\Product%s';

	/**
	 * @var string
	 */
	protected $templateFormatter = 'dbtech_ecommerce_permission_product_%s';

	/**
	 * @var string
	 */
	protected $routePrefix = 'permissions/dbtech-ecommerce-products';

	/**
	 * @var string
	 */
	protected $contentType = 'dbtech_ecommerce_product';

	/**
	 * @var string
	 */
	protected $entityIdentifier = 'DBTech\eCommerce:Product';

	/**
	 * @var string
	 */
	protected $primaryKey = 'product_id';

	/**
	 * @var string
	 */
	protected $privatePermissionGroupId = 'dbtechEcommerce';

	/**
	 * @var string
	 */
	protected $privatePermissionId = 'view';
}