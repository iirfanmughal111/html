<?php

namespace DBTech\eCommerce\Download;

use XF\Service\AbstractService;
use DBTech\eCommerce\Entity\Download;

/**
 * Class Attachment
 *
 * @package DBTech\eCommerce\Download
 */
class Attachment extends AbstractHandler
{
	/**
	 * @param Download $download
	 *
	 * @return array
	 * @throws \InvalidArgumentException
	 */
	public function getEditData(Download $download): array
	{
		/** @var \XF\Repository\Attachment $attachmentRepo */
		$attachmentRepo = $this->repository('XF:Attachment');
		$attachmentData = [];
		
		/** @var \DBTech\eCommerce\Entity\DownloadVersion $downloadVersion */
		foreach ($download->Versions as $downloadVersion)
		{
			if (empty($attachmentData[$downloadVersion->product_version]))
			{
				$attachmentData[$downloadVersion->product_version] = [];
			}
			$attachmentData[$downloadVersion->product_version][$downloadVersion->product_version_type] = $attachmentRepo->getEditorData('dbtech_ecommerce_version', $downloadVersion);
		}
		
		foreach ($download->Product->product_versions as $version => $text)
		{
			if (empty($attachmentData[$version]))
			{
				$attachmentData[$version] = [];
			}
			
			if ($download->Product->has_demo && empty($attachmentData[$version]['demo']))
			{
				// Default hash
				$attachmentData[$version]['demo'] = $attachmentRepo->getEditorData('dbtech_ecommerce_version');
			}
			
			if (empty($attachmentData[$version]['full']))
			{
				// Default hash
				$attachmentData[$version]['full'] = $attachmentRepo->getEditorData('dbtech_ecommerce_version');
			}
		}
		
		return ['attachmentData' => $attachmentData];
	}
	
	/**
	 * @param Download $download
	 *
	 * @return array|mixed
	 * @throws \InvalidArgumentException
	 */
	public function getDownloadData(Download $download): array
	{
		$versions = [];
		
		/** @var \DBTech\eCommerce\Entity\DownloadVersion $version */
		foreach ($download->Versions as $version)
		{
			$attachments = $version->Attachments;
			if (!$attachments->count())
			{
				continue;
			}
			
			if (empty($versions[$version->product_version]))
			{
				$versions[$version->product_version] = [];
			}
			$versions[$version->product_version][$version->product_version_type] = $attachments;
		}
		
		return ['versions' => $versions];
	}

	/**
	 * @param \XF\Service\AbstractService $service
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
				$value = json_decode($value);
				$versionIndex = $version . '_' . $type;
				
				if (isset($preparers[$versionIndex]))
				{
					/** @var \DBTech\eCommerce\Service\DownloadVersion\Preparer $preparer */
					$preparer = $preparers[$versionIndex];
					$preparer->setAttachmentHash($value->hash);
				}
			}
		}
	}
}