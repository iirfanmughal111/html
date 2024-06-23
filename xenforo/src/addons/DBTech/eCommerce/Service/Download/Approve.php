<?php

namespace DBTech\eCommerce\Service\Download;

use DBTech\eCommerce\Entity\Download;

/**
 * Class Approve
 *
 * @package DBTech\eCommerce\Service\Download
 */
class Approve extends \XF\Service\AbstractService
{
	/** @var \DBTech\eCommerce\Entity\Download */
	protected $download;
	
	/** @var int */
	protected $notifyRunTime = 3;
	
	/**
	 * Approve constructor.
	 *
	 * @param \XF\App $app
	 * @param Download $download
	 */
	public function __construct(\XF\App $app, Download $download)
	{
		parent::__construct($app);
		$this->download = $download;
	}

	/**
	 * @return \DBTech\eCommerce\Entity\Download
	 */
	public function getUpdate(): Download
	{
		return $this->download;
	}

	/**
	 * @param int $time
	 *
	 * @return $this
	 */
	public function setNotifyRunTime(int $time): Approve
	{
		$this->notifyRunTime = $time;

		return $this;
	}
	
	/**
	 * @return bool
	 * @throws \LogicException
	 * @throws \Exception
	 * @throws \XF\PrintableException
	 */
	public function approve(): bool
	{
		if ($this->download->download_state == 'moderated')
		{
			$this->download->download_state = 'visible';
			$this->download->save();

			$this->onApprove();
			return true;
		}
		
		return false;
	}

	/**
	 *
	 */
	protected function onApprove()
	{
		// if this is not the last download, then another notification would have been triggered already
		if ($this->download->isLastUpdate())
		{
			/** @var \DBTech\eCommerce\Service\Download\Notify $notifier */
			$notifier = $this->service('DBTech\eCommerce:Download\Notify', $this->download, 'download');
			$notifier->notifyAndEnqueue($this->notifyRunTime);
		}
	}
}