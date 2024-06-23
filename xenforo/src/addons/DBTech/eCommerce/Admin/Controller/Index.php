<?php

namespace DBTech\eCommerce\Admin\Controller;

use XF\Admin\Controller\AbstractController;

/**
 * Class Index
 * @package DBTech\eCommerce\Admin\Controller
 */
class Index extends AbstractController
{
	/**
	 * @return \XF\Mvc\Reply\View
	 */
	public function actionIndex(): \XF\Mvc\Reply\AbstractReply
	{
		return $this->view('DBTech\eCommerce:Index', 'dbtech_ecommerce');
	}
}