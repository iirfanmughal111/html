<?php

namespace DBTech\eCommerce\ControllerPlugin;

use XF\ControllerPlugin\AbstractPermission;

/**
 * Class CategoryPermission
 *
 * @package DBTech\eCommerce\ControllerPlugin
 */
class CategoryPermission extends AbstractPermission
{
	/**
	 * @var string
	 */
	protected $viewFormatter = 'DBTech\eCommerce:Permission\Category%s';
	
	/**
	 * @var string
	 */
	protected $templateFormatter = 'dbtech_ecommerce_permission_category_%s';
	
	/**
	 * @var string
	 */
	protected $routePrefix = 'permissions/dbtech-ecommerce-categories';
	
	/**
	 * @var string
	 */
	protected $contentType = 'dbtech_ecommerce_category';
	
	/**
	 * @var string
	 */
	protected $entityIdentifier = 'DBTech\eCommerce:Category';
	
	/**
	 * @var string
	 */
	protected $primaryKey = 'category_id';
	
	/**
	 * @var string
	 */
	protected $privatePermissionGroupId = 'dbtechEcommerce';
	
	/**
	 * @var string
	 */
	protected $privatePermissionId = 'view';
}