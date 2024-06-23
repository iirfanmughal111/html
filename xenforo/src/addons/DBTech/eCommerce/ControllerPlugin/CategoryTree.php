<?php

namespace DBTech\eCommerce\ControllerPlugin;

use XF\ControllerPlugin\AbstractCategoryTree;

/**
 * Class CategoryTree
 *
 * @package DBTech\eCommerce\ControllerPlugin
 */
class CategoryTree extends AbstractCategoryTree
{
	/**
	 * @var string
	 */
	protected $viewFormatter = 'DBTech\eCommerce:Category\%s';
	
	/**
	 * @var string
	 */
	protected $templateFormatter = 'dbtech_ecommerce_category_%s';
	
	/**
	 * @var string
	 */
	protected $routePrefix = 'dbtech-ecommerce/categories';
	
	/**
	 * @var string
	 */
	protected $entityIdentifier = 'DBTech\eCommerce:Category';
	
	/**
	 * @var string
	 */
	protected $primaryKey = 'category_id';
}