<?php

namespace DBTech\eCommerce\ControllerPlugin;

use XF\ControllerPlugin\AbstractPlugin;
use DBTech\eCommerce\Entity\DownloadVersion;
use DBTech\eCommerce\Entity\License;

/**
 * Class AbstractDownload
 *
 * @package DBTech\eCommerce\ControllerPlugin
 */
abstract class AbstractDownload extends AbstractPlugin
{
	/**
	 * @param DownloadVersion $version
	 * @param License|null $license
	 *
	 * @return mixed
	 */
	abstract protected function _download(DownloadVersion $version, ?License $license = null);
	
	/**
	 * @param DownloadVersion $version
	 * @param License|null $license
	 *
	 * @return mixed
	 * @throws \LogicException
	 * @throws \InvalidArgumentException
	 * @throws \Exception
	 * @throws \XF\PrintableException
	 */
	public function download(DownloadVersion $version, ?License $license = null)
	{
		/** @var \DBTech\eCommerce\Repository\Download $downloadRepo */
		$downloadRepo = $this->getDownloadRepo();
		$downloadRepo->logDownload($version, $license);
		
		/** @var \DBTech\eCommerce\XF\Entity\User $visitor */
		$user = $license ? $license->User : \XF::visitor();
		if ($user->user_id != $version->Download->Product->user_id)
		{
			/** @var \DBTech\eCommerce\Repository\ProductWatch $productWatchRepo */
			$productWatchRepo = $this->repository('DBTech\eCommerce:ProductWatch');
			$productWatchRepo->autoWatchProduct($version->Download->Product, $user);
		}
		
		return $this->_download($version, $license);
	}
	
	/**
	 * @return \DBTech\eCommerce\Repository\Download|\XF\Mvc\Entity\Repository
	 */
	protected function getDownloadRepo()
	{
		return $this->repository('DBTech\eCommerce:Download');
	}
}