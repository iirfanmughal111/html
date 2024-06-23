<?php

namespace Z61\Classifieds\ControllerPlugin;

use XF\ControllerPlugin\AbstractCategoryTree;

class CategoryTree extends AbstractCategoryTree
{
    protected $viewFormatter = 'Z61\Classifieds:Category\%s';
    protected $templateFormatter = 'z61_classifieds_category_%s';
    protected $routePrefix = 'classifieds/categories';
    protected $entityIdentifier = 'Z61\Classifieds:Category';
    protected $primaryKey = 'category_id';
}