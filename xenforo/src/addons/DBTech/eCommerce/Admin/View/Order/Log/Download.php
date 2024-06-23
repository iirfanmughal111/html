<?php

namespace DBTech\eCommerce\Admin\View\Order\Log;

/**
 * Class Download
 *
 * @package DBTech\eCommerce\Admin\View\Order\Log
 */
class Download extends \XF\Mvc\View
{
	/**
	 * @return string|\XF\Http\ResponseStream
	 */
	public function renderRaw()
	{
		$this->response
			->setDownloadFileName($this->params['fileName'])
			->header('Content-type', 'text/csv')
			->header('Content-Length', filesize($this->params['csvPath']))
			->header('ETag', \XF::$time)
			->header('X-Content-Type-Options', 'nosniff');

		return $this->response->responseFile($this->params['csvPath']);
	}
}