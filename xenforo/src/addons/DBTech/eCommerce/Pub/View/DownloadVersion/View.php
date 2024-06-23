<?php

namespace DBTech\eCommerce\Pub\View\DownloadVersion;

/**
 * Class View
 *
 * @package DBTech\eCommerce\Pub\View\DownloadVersion
 */
class View extends \XF\Mvc\View
{
	/**
	 * @return string|\XF\Http\ResponseStream
	 * @throws \League\Flysystem\FileNotFoundException
	 * @throws \League\Flysystem\FileNotFoundException
	 */
	public function renderRaw()
	{
		$this->response
			->setAttachmentFileParams($this->params['filename']);

		$size = \XF::fs()->getSize($this->params['abstractPath']);
		$resource = \XF::fs()->readStream($this->params['abstractPath']);
		return $this->response->responseStream($resource, $size);
	}
}