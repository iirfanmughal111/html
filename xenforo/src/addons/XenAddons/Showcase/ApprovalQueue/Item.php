<?php

namespace XenAddons\Showcase\ApprovalQueue;

use XF\ApprovalQueue\AbstractHandler;
use XF\Mvc\Entity\Entity;

class Item extends AbstractHandler
{
	protected function canActionContent(Entity $content, &$error = null)
	{
		/** @var $content \XenAddons\Showcase\Entity\Item */
		return $content->canApproveUnapprove($error);
	}

	public function getEntityWith()
	{
		$visitor = \XF::visitor();

		return ['Category', 'Category.Permissions|' . $visitor->permission_combination_id, 'User'];
	}

	public function actionApprove(\XenAddons\Showcase\Entity\Item $item)
	{
		/** @var \XenAddons\Showcase\Service\Item\Approve $approver */
		$approver = \XF::service('XenAddons\Showcase:Item\Approve', $item);
		$approver->setNotifyRunTime(1); // may be a lot happening
		$approver->approve();
	}

	public function actionDelete(\XenAddons\Showcase\Entity\Item $item)
	{
		$this->quickUpdate($item, 'item_state', 'deleted');
	}
}