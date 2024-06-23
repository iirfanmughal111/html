<?php

namespace DBTech\eCommerce\Api\View\DownloadVersion;

/**
 * Class View
 *
 * @package DBTech\eCommerce\Api\View\DownloadVersion
 */
class View extends \XF\Mvc\View
{
	/**
	 * @return \XF\Http\ResponseStream
	 * @throws \League\Flysystem\FileNotFoundException
	 */
	public function renderRaw(): \XF\Http\ResponseStream
	{
		$this->response
			->setAttachmentFileParams($this->params['filename']);

		$size = \XF::fs()->getSize($this->params['abstractPath']);
		$resource = \XF::fs()->readStream($this->params['abstractPath']);
		return $this->response->responseStream($resource, $size);
	}
}