<?php

namespace XenAddons\Showcase\ControllerPlugin;

use XF\ControllerPlugin\AbstractCategoryTree;

class CategoryTree extends AbstractCategoryTree
{
	protected $viewFormatter = 'XenAddons\Showcase:Category\%s';
	protected $templateFormatter = 'xa_sc_category_%s';
	protected $routePrefix = 'xa-sc/categories';
	protected $entityIdentifier = 'XenAddons\Showcase:Category';
	protected $primaryKey = 'category_id';
}