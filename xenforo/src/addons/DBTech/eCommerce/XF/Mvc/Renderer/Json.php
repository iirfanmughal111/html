<?php /** @noinspection PhpMissingReturnTypeInspection */

namespace DBTech\eCommerce\XF\Mvc\Renderer;

/**
 * Class Json
 *
 * @package DBTech\eCommerce\XF\Mvc\Renderer
 */
class Json extends XFCP_Json
{
	/**
	 * @param array $content
	 *
	 * @return array
	 */
	protected function addDefaultJsonParams(array $content)
	{
		$content = parent::addDefaultJsonParams($content);
		
		/** @var \DBTech\eCommerce\XF\Entity\User $visitor */
		$visitor = \XF::visitor();
		$language = \XF::language();
		
		if ($visitor->user_id)
		{
			$cartItems = $visitor->getDbtechEcommerceCartItems();
			
			$content['visitor']['dbtech_ecommerce_cart_items'] = $language->numberFormat($cartItems);
			$content['visitor']['total_unread'] = $language->numberFormat(
				str_replace($language->thousands_separator, '', $content['visitor']['total_unread']) + $cartItems
			);
		}
		
		return $content;
	}
}