<?php

namespace Z61\Classifieds\ControllerPlugin;

use XF\ControllerPlugin\AbstractPermission;

class CategoryPermission extends AbstractPermission
{
    protected $viewFormatter = 'Z61\Classifieds:Permission\Category%s';
    protected $templateFormatter = 'classifieds_permission_category_%s';
    protected $routePrefix = 'permissions/classifieds-categories';
    protected $contentType = 'classifieds_category';
    protected $entityIdentifier = 'Z61\Classifieds:Category';
    protected $primaryKey = 'category_id';
    protected $privatePermissionGroupId = 'classifieds';
    protected $privatePermissionId = 'view';
}