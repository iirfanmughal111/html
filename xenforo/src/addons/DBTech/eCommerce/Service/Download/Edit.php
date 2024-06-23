<?php

namespace DBTech\eCommerce\Service\Download;

use DBTech\eCommerce\Entity\Download;
use DBTech\eCommerce\Download\AbstractHandler;

/**
 * Class Edit
 *
 * @package DBTech\eCommerce\Service\Download
 */
class Edit extends \XF\Service\AbstractService
{
	use \XF\Service\ValidateAndSavableTrait;

	/** @var \DBTech\eCommerce\Entity\Download */
	protected $download;
	
	/** @var \DBTech\eCommerce\Service\Download\Preparer */
	protected $changeLogPreparer;
	
	/** @var \DBTech\eCommerce\Service\Download\Preparer */
	protected $releaseNotesPreparer;
	
	/** @var AbstractHandler */
	protected $handler;
	
	/** @var array */
	protected $versionPreparers = [];
	
	/** @var bool */
	protected $performValidations = true;
	
	/** @var bool */
	protected $alert = false;
	
	/** @var string */
	protected $alertReason = '';


	/**
	 * Edit constructor.
	 *
	 * @param \XF\App $app
	 * @param \DBTech\eCommerce\Entity\Download $download
	 *
	 * @throws \Exception
	 */
	public function __construct(\XF\App $app, Download $download)
	{
		parent::__construct($app);

		$this->download = $download;
		$this->changeLogPreparer = $this->service('DBTech\eCommerce:Download\Preparer', $download, 'change_log');
		$this->releaseNotesPreparer = $this->service('DBTech\eCommerce:Download\Preparer', $download, 'release_notes');
		$this->handler = $download->getHandler();
		
		$this->createVersionPreparers();
	}
	
	/**
	 * @return Download
	 */
	public function getDownload(): Download
	{
		return $this->download;
	}
	
	/**
	 * @return AbstractHandler
	 */
	public function getHandler(): AbstractHandler
	{
		return $this->handler;
	}
	
	/**
	 * @return Preparer|\XF\Service\AbstractService
	 */
	public function getChangeLogPreparer()
	{
		return $this->changeLogPreparer;
	}
	
	/**
	 * @return Preparer|\XF\Service\AbstractService
	 */
	public function getReleaseNotesPreparer()
	{
		return $this->releaseNotesPreparer;
	}
	
	/**
	 * @return array
	 */
	public function getVersionPreparers(): array
	{
		return $this->versionPreparers;
	}

	/**
	 * @param bool $perform
	 *
	 * @return $this
	 */
	public function setPerformValidations(bool $perform): Edit
	{
		$this->performValidations = $perform;

		return $this;
	}
	
	/**
	 * @return bool
	 */
	public function getPerformValidations(): bool
	{
		return $this->performValidations;
	}

	/**
	 * @return $this
	 */
	public function setIsAutomated(): Edit
	{
		$this->setPerformValidations(false);

		return $this;
	}
	
	/**
	 * @param string $changeLog
	 * @param bool $format
	 *
	 * @return bool
	 * @throws \LogicException
	 * @throws \InvalidArgumentException
	 */
	public function setChangeLog(string $changeLog, bool $format = true): bool
	{
		return $this->changeLogPreparer->setMessage($changeLog, $format, $this->performValidations);
	}
	
	/**
	 * @param string $releaseNotes
	 * @param bool $format
	 *
	 * @return bool
	 * @throws \LogicException
	 * @throws \InvalidArgumentException
	 */
	public function setReleaseNotes(string $releaseNotes, bool $format = true): bool
	{
		return $this->releaseNotesPreparer->setMessage($releaseNotes, $format, $this->performValidations, true);
	}

	/**
	 * @param string $hash
	 *
	 * @return $this
	 */
	public function setChangeLogAttachmentHash(string $hash): Edit
	{
		$this->changeLogPreparer->setAttachmentHash($hash);

		return $this;
	}

	/**
	 * @param string $hash
	 *
	 * @return $this
	 */
	public function setReleaseNotesAttachmentHash(string $hash): Edit
	{
		$this->releaseNotesPreparer->setAttachmentHash($hash);

		return $this;
	}

	/**
	 * @param bool $alert
	 * @param string|null $reason
	 *
	 * @return $this
	 */
	public function setSendAlert(bool $alert, ?string $reason = null): Edit
	{
		$this->alert = $alert;
		if ($reason !== null)
		{
			$this->alertReason = $reason;
		}

		return $this;
	}
	
	/**
	 *
	 */
	protected function createVersionPreparers()
	{
		/** @var \DBTech\eCommerce\Entity\DownloadVersion $downloadVersions */
		$downloadVersions = $this->download->Versions;
		
		$this->versionPreparers = [];
		foreach ($downloadVersions as $downloadVersion)
		{
			if ($this->download->Product->has_demo || $downloadVersion->product_version_type != 'demo')
			{
				$this->versionPreparers[$downloadVersion->product_version . '_' . $downloadVersion->product_version_type] =
					$this->service('DBTech\eCommerce:DownloadVersion\Preparer', $downloadVersion);
			}
		}
		
		foreach ($this->download->Product->product_versions as $version => $text)
		{
			$versionIndexDemo = $version . '_demo';
			$versionIndexFull = $version . '_full';
			
			if ($this->download->Product->has_demo && empty($this->versionPreparers[$versionIndexDemo]))
			{
				$this->versionPreparers[$versionIndexDemo] =
					$this->service('DBTech\eCommerce:DownloadVersion\Preparer');
				
				$this->versionPreparers[$versionIndexDemo]->getVersion()->bulkSet([
					'product_version' => $version,
					'product_version_type' => 'demo'
				]);
			}
			
			if (empty($this->versionPreparers[$versionIndexFull]))
			{
				$this->versionPreparers[$versionIndexFull] =
					$this->service('DBTech\eCommerce:DownloadVersion\Preparer');
				
				$this->versionPreparers[$versionIndexFull]->getVersion()->bulkSet([
					'product_version' => $version,
					'product_version_type' => 'full'
				]);
			}
		}
	}
	
	/**
	 *
	 */
	public function checkForSpam()
	{
		if ($this->download->download_state == 'visible' && \XF::visitor()->isSpamCheckRequired())
		{
			$this->changeLogPreparer->checkForSpam();
			$this->releaseNotesPreparer->checkForSpam();
		}
	}
	
	/**
	 *
	 */
	protected function finalSetup()
	{
	}
	
	/**
	 * @return array
	 */
	protected function _validate(): array
	{
		$this->finalSetup();
		
		$this->download->preSave();
		return $this->download->getErrors();
	}
	
	/**
	 * @return Download
	 * @throws \LogicException
	 * @throws \Exception
	 * @throws \XF\PrintableException
	 */
	protected function _save(): Download
	{
		$download = $this->download;
		
		$db = $this->db();
		$db->beginTransaction();
		
		$this->beforeUpdate();
		$this->changeLogPreparer->beforeUpdate();
		$this->releaseNotesPreparer->beforeUpdate();

		$download->save(true, false);
		
		$this->afterUpdate();
		$this->changeLogPreparer->afterUpdate();
		$this->releaseNotesPreparer->afterUpdate();
		
		/** @var \DBTech\eCommerce\Service\DownloadVersion\Preparer $versionPreparer */
		foreach ($this->versionPreparers as $versionPreparer)
		{
			$version = $versionPreparer->getVersion();
			$version->download_id = $download->download_id;
			$version->save();
			
			$versionPreparer->postSave();
		}
		
		$visitor = \XF::visitor();
		if ($download->download_state == 'visible' && $this->alert && $download->Product->user_id != $visitor->user_id)
		{
			/** @var \DBTech\eCommerce\Repository\Download $downloadRepo */
			$downloadRepo = $this->repository('DBTech\eCommerce:Download');
			$downloadRepo->sendModeratorActionAlert($this->download, 'edit', $this->alertReason);
		}

		$db->commit();

		return $download;
	}

	/**
	 *
	 */
	public function beforeUpdate()
	{
	}

	/**
	 *
	 */
	public function afterUpdate()
	{
	}
}