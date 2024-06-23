<?php

namespace DBTech\eCommerce\Bookmark;

use XF\Bookmark\AbstractHandler;
use XF\Mvc\Entity\Entity;

class Download extends AbstractHandler
{
	/**
	 * @param Entity $content
	 *
	 * @return \XF\Phrase
	 */
	public function getContentTitle(Entity $content): \XF\Phrase
	{
		/** @var \DBTech\eCommerce\Entity\Download $content */
		
		return \XF::phrase('dbtech_ecommerce_product_update_in_x', [
			'title' => $content->Product->title
		]);
	}
	
	/**
	 * @param Entity $content
	 *
	 * @return string
	 */
	public function getContentRoute(Entity $content): string
	{
		/** @var \DBTech\eCommerce\Entity\Download $content */
		
		return 'dbtech-ecommerce/release';
	}

	/**
	 * @return string
	 */
	public function getCustomIconTemplateName(): string
	{
		return 'public:dbtech_ecommerce_download_bookmark_custom_icon';
	}

	/**
	 * @return array
	 */
	public function getEntityWith(): array
	{
		return ['Product', 'Product.Category'];
	}
}