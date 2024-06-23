<?php

namespace DBTech\eCommerce\ApprovalQueue;

use XF\ApprovalQueue\AbstractHandler;
use XF\Mvc\Entity\Entity;

/**
 * Class Download
 *
 * @package DBTech\eCommerce\ApprovalQueue
 */
class Download extends AbstractHandler
{
	/**
	 * @param Entity $content
	 * @param null $error
	 *
	 * @return bool
	 */
	protected function canActionContent(Entity $content, &$error = null): bool
	{
		/** @var $content \DBTech\eCommerce\Entity\Download */
		return $content->canApproveUnapprove($error);
	}
	
	/**
	 * @return array
	 */
	public function getEntityWith(): array
	{
		return [
			'Product',
			'Product.permissionSet',
			'Product.User'
		];
	}
	
	/**
	 * @param \DBTech\eCommerce\Entity\Download $download
	 *
	 * @throws \LogicException
	 * @throws \Exception
	 * @throws \XF\PrintableException
	 */
	public function actionApprove(\DBTech\eCommerce\Entity\Download $download)
	{
		/** @var \DBTech\eCommerce\Service\Download\Approve $approver */
		$approver = \XF::service('DBTech\eCommerce:Download\Approve', $download);
		$approver->setNotifyRunTime(1); // may be a lot happening
		$approver->approve();
	}
	
	/**
	 * @param \DBTech\eCommerce\Entity\Download $download
	 */
	public function actionDelete(\DBTech\eCommerce\Entity\Download $download)
	{
		$this->quickUpdate($download, 'download_state', 'deleted');
	}
}