<?php

namespace DBTech\eCommerce\Api\Controller;

use XF\Api\Controller\AbstractController;
use XF\Mvc\ParameterBag;

/**
 * Class AbstractLoggableEndpoint
 *
 * @package DBTech\eCommerce\Api\Controller
 */
abstract class AbstractLoggableEndpoint extends AbstractController
{
	/**
	 * @param $action
	 * @param ParameterBag $params
	 *
	 * @throws \XF\PrintableException
	 * @throws \XF\Mvc\Reply\Exception
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function preDispatchController($action, ParameterBag $params)
	{
		if (!\XF::options()->dbtechEcommerceEnableApi)
		{
			throw $this->exception($this->notFound());
		}
		$this->logApiRequest();
	}

	/**
	 * @throws \XF\PrintableException
	 */
	protected function logApiRequest()
	{
		/** @var \DBTech\eCommerce\XF\Logger $logger */
		$logger = $this->app()->logger();
		$logger->logDbtechEcommerceApiRequest(
			$this->request->getServer('HTTP_XF_API_KEY'),
			$this->request->getRequestUri(),
			$this->request->getServer('HTTP_REFERER'),
			$this->request->getServer('HTTP_X_DRAGONBYTE_BOARDURL'),
			$this->request->getServer('HTTP_X_DRAGONBYTE_HTTPHOST'),
			$this->request->getServer('HTTP_X_DRAGONBYTE_SOFTWARE'),
			$this->request->getServer('HTTP_X_DRAGONBYTE_SOFTWAREVERSION'),
			$_SERVER
		);
	}
}