<?php

namespace DBTech\eCommerce\ControllerPlugin;

use DBTech\eCommerce\Entity\DownloadVersion;
use DBTech\eCommerce\Entity\License;

/**
 * Class External
 *
 * @package DBTech\eCommerce\ControllerPlugin
 */
class External extends AbstractDownload
{
	/**
	 * @param DownloadVersion $version
	 * @param License|null $license
	 *
	 * @return mixed
	 */
	protected function _download(DownloadVersion $version, ?License $license = null)
	{
		return $this->controller->redirectPermanently($version->download_url);
	}
}