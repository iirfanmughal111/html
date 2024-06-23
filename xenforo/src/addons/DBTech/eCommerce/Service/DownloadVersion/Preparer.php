<?php

namespace DBTech\eCommerce\Service\DownloadVersion;

use DBTech\eCommerce\Entity\DownloadVersion;
use XF\Service\AbstractService;

/**
 * Class Preparer
 * @package DBTech\eCommerce\Service\DownloadVersion
 */
class Preparer extends AbstractService
{
	/** @var \DBTech\eCommerce\Entity\DownloadVersion */
	protected $downloadVersion;

	/** @var string */
	protected $attachmentHash;

	/**
	 * Preparer constructor.
	 *
	 * @param \XF\App $app
	 * @param \DBTech\eCommerce\Entity\DownloadVersion|null $downloadVersion
	 */
	public function __construct(\XF\App $app, ?DownloadVersion $downloadVersion = null)
	{
		parent::__construct($app);
		if ($downloadVersion === null)
		{
			$this->setupDefaults();
		}
		else
		{
			$this->downloadVersion = $downloadVersion;
		}
	}
	
	/**
	 *
	 */
	protected function setupDefaults()
	{
		$this->downloadVersion = $this->em()->create('DBTech\eCommerce:DownloadVersion');
	}

	/**
	 * @return DownloadVersion
	 */
	public function getVersion(): DownloadVersion
	{
		return $this->downloadVersion;
	}

	/**
	 * @param string $hash
	 *
	 * @return $this
	 */
	public function setAttachmentHash(string $hash): Preparer
	{
		$this->attachmentHash = $hash;

		return $this;
	}

	public function postSave()
	{
		if ($this->attachmentHash)
		{
			$this->associateAttachments($this->attachmentHash);
		}
	}

	/**
	 * @param string $hash
	 */
	protected function associateAttachments(string $hash)
	{
		$downloadVersion = $this->downloadVersion;

		/** @var \XF\Service\Attachment\Preparer $inserter */
		$inserter = $this->service('XF:Attachment\Preparer');
		$associated = $inserter->associateAttachmentsWithContent($hash, 'dbtech_ecommerce_version', $downloadVersion->download_version_id);
		if ($associated)
		{
			$downloadVersion->fastUpdate('attach_count', $downloadVersion->attach_count + $associated);
		}
	}
}