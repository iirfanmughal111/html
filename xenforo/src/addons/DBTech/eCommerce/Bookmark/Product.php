<?php

namespace DBTech\eCommerce\Bookmark;

use XF\Bookmark\AbstractHandler;

/**
 * Class Product
 *
 * @package DBTech\eCommerce\Bookmark
 */
class Product extends AbstractHandler
{
	/**
	 * @return string
	 */
	public function getCustomIconTemplateName(): string
	{
		return 'public:dbtech_ecommerce_product_bookmark_custom_icon';
	}
	
	/**
	 * @return array
	 */
	public function getEntityWith(): array
	{
		return ['User', 'permissionSet'];
	}
}