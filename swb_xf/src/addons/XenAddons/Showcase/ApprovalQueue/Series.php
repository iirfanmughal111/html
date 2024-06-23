<?php

namespace XenAddons\Showcase\ApprovalQueue;

use XF\ApprovalQueue\AbstractHandler;
use XF\Mvc\Entity\Entity;

class Series extends AbstractHandler
{
	protected function canActionContent(Entity $content, &$error = null)
	{
		/** @var $content \XenAddons\Showcase\Entity\SeriesItem */
		return $content->canApproveUnapprove($error);
	}

	public function getEntityWith()
	{
		$visitor = \XF::visitor();

		return ['User'];
	}

	public function actionApprove(\XenAddons\Showcase\Entity\SeriesItem $series)
	{
		/** @var \XenAddons\Showcase\Service\Series\Approve $approver */
		$approver = \XF::service('XenAddons\Showcase:Series\Approve', $series);
		$approver->setNotifyRunTime(1); // may be a lot happening
		$approver->approve();
	}

	public function actionDelete(\XenAddons\Showcase\Entity\SeriesItem $series)
	{
		$this->quickUpdate($series, 'series_state', 'deleted');
	}
}