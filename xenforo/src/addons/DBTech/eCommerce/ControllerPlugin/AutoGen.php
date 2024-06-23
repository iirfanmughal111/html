<?php

namespace DBTech\eCommerce\ControllerPlugin;

use DBTech\eCommerce\Entity\DownloadVersion;
use DBTech\eCommerce\Entity\License;

/**
 * Class AutoGen
 *
 * @package DBTech\eCommerce\ControllerPlugin
 */
class AutoGen extends AbstractDownload
{
	/**
	 * @param DownloadVersion $version
	 * @param License|null $license
	 *
	 * @return mixed
	 * @throws \LogicException
	 * @throws \InvalidArgumentException
	 * @throws \ErrorException
	 * @throws \XF\PrintableException
	 */
	protected function _download(DownloadVersion $version, ?License $license = null)
	{
		if (!\XF::config('dbtechEcommerceCacheReleases') || !\XF::fs()->has($version->getReleaseAbstractPath($license)))
		{
			/** @var \DBTech\eCommerce\Service\DownloadVersion\Generator $builderService */
			$builderService = \XF::app()->service('DBTech\eCommerce:DownloadVersion\Generator', $version, $license);
			$builderService->setController($this->controller);
			$builderService->build();
			$builderService->finalizeRelease();
		}
		
		$this->setResponseType('raw');
		
		$viewParams = [
			'version' => $version,
			'license' => $license,
			'filename' => $version->getReleaseFileName($license),
			'abstractPath' => $version->getReleaseAbstractPath($license)
		];
		return $this->view('DBTech\eCommerce:DownloadVersion\View', '', $viewParams);
	}
}