<?php

namespace DBTech\eCommerce\Pub\Controller;

use XF\Mvc\ParameterBag;
use XF\Mvc\Reply\AbstractReply;
use XF\Mvc\Reply\View;

abstract class AbstractController extends \XF\Pub\Controller\AbstractController
{
	protected function postDispatchController($action, ParameterBag $params, AbstractReply &$reply)
	{
		if ($reply instanceof View)
		{
			$viewParams = $reply->getParams();
			$category = null;

			if (isset($viewParams['product']))
			{
				$category = $viewParams['product']->Category;
			}
			if (isset($viewParams['category']))
			{
				$category = $viewParams['category'];
			}
			if ($category)
			{
				$reply->setContainerKey('dbtechEcommerceCategory-' . $category->category_id);
			}
		}
	}
}