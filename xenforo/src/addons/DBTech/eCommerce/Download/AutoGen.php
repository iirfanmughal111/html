<?php

namespace DBTech\eCommerce\Download;

use XF\Service\AbstractService;
use DBTech\eCommerce\Entity\Download;

/**
 * Class AutoGen
 *
 * @package DBTech\eCommerce\Download
 */
class AutoGen extends AbstractHandler
{
	/**
	 * @param Download $download
	 *
	 * @return array
	 * @throws \InvalidArgumentException
	 */
	public function getEditData(Download $download): array
	{
		/** @var \DBTech\eCommerce\Entity\DownloadVersion $downloadVersions */
		$downloadVersions = $download->getRelationOrDefault('Versions', false);
		//		$downloadVersions->populate();
		//		\XF::dump($downloadVersions['xf1']);
		//		die();
		
		$downloadVersionsGrouped = [];
		
		/** @var \DBTech\eCommerce\Entity\DownloadVersion $downloadVersion */
		foreach ($downloadVersions as $downloadVersion)
		{
			if (empty($downloadVersionsGrouped[$downloadVersion->product_version]))
			{
				$downloadVersionsGrouped[$downloadVersion->product_version] = [];
			}
			$downloadVersionsGrouped[$downloadVersion->product_version][$downloadVersion->product_version_type] = $downloadVersion;
		}
		
		return ['downloadVersions' => $downloadVersionsGrouped];
	}
	
	/**
	 * @param Download $download
	 *
	 * @return array
	 */
	public function getDownloadData(Download $download): array
	{
		$versions = [];
		
		/** @var \DBTech\eCommerce\Entity\DownloadVersion $version */
		foreach ($download->Versions as $version)
		{
			if (!$version->directories)
			{
				continue;
			}
			
			if (empty($versions[$version->product_version]))
			{
				$versions[$version->product_version] = [];
			}
			$versions[$version->product_version][$version->product_version_type] = true;
		}
		
		return ['versions' => $versions];
	}
	
	/**
	 * @param AbstractService $service
	 * @param array $data
	 */
	public function setEditData(AbstractService $service, array $data = [])
	{
		/** @var \DBTech\eCommerce\Service\Download\Create|\DBTech\eCommerce\Service\Download\Edit $service */
		$preparers = $service->getVersionPreparers();
		
		foreach ($data as $version => $types)
		{
			foreach ($types as $type => $value)
			{
				$versionIndex = $version . '_' . $type;
				
				if (isset($preparers[$versionIndex]))
				{
					$directoryList = preg_split('#\r?\n#', $value, -1, PREG_SPLIT_NO_EMPTY);
					foreach ($directoryList as $dir)
					{
						if (
							!is_dir($dir)
							|| !is_readable($dir)
						) {
							$service->getDownload()->error(\XF::phrase('dbtech_ecommerce_download_dir_not_readable', ['directory' => $dir]));
						}
					}
					
					/** @var \DBTech\eCommerce\Service\DownloadVersion\Preparer $preparer */
					$preparer = $preparers[$versionIndex];
					$preparer->getVersion()->directories = $value;
				}
			}
		}
	}
}