<?php

namespace XenAddons\Showcase\ApprovalQueue;

use XF\ApprovalQueue\AbstractHandler;
use XF\Mvc\Entity\Entity;

class ItemUpdate extends AbstractHandler
{
	protected function canActionContent(Entity $content, &$error = null)
	{
		/** @var $content \XenAddons\Showcase\Entity\ItemUpdate */
		return $content->canApproveUnapprove($error);
	}

	public function getEntityWith()
	{
		return ['Item', 'User'];
	}

	public function actionApprove(\XenAddons\Showcase\Entity\ItemUpdate $update)
	{
		$this->quickUpdate($update, 'update_state', 'visible');
	}

	public function actionDelete(\XenAddons\Showcase\Entity\ItemUpdate $update)
	{
		$this->quickUpdate($update, 'update_state', 'deleted');
	}
}