<?php

namespace XenAddons\Showcase\ControllerPlugin;

use XF\ControllerPlugin\AbstractPermission;

class CategoryPermission extends AbstractPermission
{
	protected $viewFormatter = 'XenAddons\Showcase:Permission\Category%s';
	protected $templateFormatter = 'xa_sc_permission_category_%s';
	protected $routePrefix = 'permissions/xa-sc-categories';
	protected $contentType = 'sc_category';
	protected $entityIdentifier = 'XenAddons\Showcase:Category';
	protected $primaryKey = 'category_id';
	protected $privatePermissionGroupId = 'xa_showcase';
	protected $privatePermissionId = 'view';
}