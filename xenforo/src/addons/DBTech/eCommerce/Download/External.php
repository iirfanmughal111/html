<?php

namespace DBTech\eCommerce\Download;

use XF\Service\AbstractService;
use DBTech\eCommerce\Entity\Download;

/**
 * Class External
 *
 * @package DBTech\eCommerce\Download
 */
class External extends AbstractHandler
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
	 * @return array|mixed
	 */
	public function getDownloadData(Download $download): array
	{
		$versions = [];
		
		/** @var \DBTech\eCommerce\Entity\DownloadVersion $version */
		foreach ($download->Versions as $version)
		{
			if (!$version->download_url)
			{
				continue;
			}
			
			if (empty($versions[$version->product_version]))
			{
				$versions[$version->product_version] = [];
			}
			$versions[$version->product_version][$version->product_version_type] = $version->download_url;
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
					/** @var \DBTech\eCommerce\Service\DownloadVersion\Preparer $preparer */
					$preparer = $preparers[$versionIndex];
					$preparer->getVersion()->download_url = $value;
				}
			}
		}
	}
}