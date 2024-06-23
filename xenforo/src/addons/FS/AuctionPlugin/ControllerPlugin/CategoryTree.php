<?php

namespace FS\AuctionPlugin\ControllerPlugin;

use XF\ControllerPlugin\AbstractCategoryTree;

class CategoryTree extends AbstractCategoryTree
{
    protected $viewFormatter = 'FS\AuctionPlugin:Category\%s';
    protected $templateFormatter = 'fs_auction_categories_%s';
    protected $routePrefix = 'auction/categories';
    protected $entityIdentifier = 'FS\AuctionPlugin:Category';
    protected $primaryKey = 'category_id';
}
