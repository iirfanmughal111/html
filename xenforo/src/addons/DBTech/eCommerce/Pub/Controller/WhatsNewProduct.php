<?php

namespace DBTech\eCommerce\Pub\Controller;

use XF\Pub\Controller\AbstractWhatsNewFindType;

/**
 * Class WhatsNewProduct
 *
 * @package DBTech\eCommerce\Pub\Controller
 */
class WhatsNewProduct extends AbstractWhatsNewFindType
{
	/**
	 * @return string
	 */
	protected function getContentType(): string
	{
		return 'dbtech_ecommerce_product';
	}
}