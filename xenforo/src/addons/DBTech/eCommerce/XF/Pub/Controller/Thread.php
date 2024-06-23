<?php /** @noinspection PhpMissingReturnTypeInspection */

namespace DBTech\eCommerce\XF\Pub\Controller;

use XF\Mvc\ParameterBag;

/**
 * Class Thread
 *
 * @package DBTech\eCommerce\XF\Pub\Controller
 */
class Thread extends XFCP_Thread
{
	public function actionIndex(ParameterBag $params)
	{
		$reply = parent::actionIndex($params);
		
		if ($reply instanceof \XF\Mvc\Reply\View && $reply->getParam('posts'))
		{
			/** @var \DBTech\eCommerce\Repository\Product $productRepo */
			$productRepo = $this->repository('DBTech\eCommerce:Product');
			$productRepo->addProductEmbedsToContent($reply->getParam('posts'));
		}
		
		return $reply;
	}
}