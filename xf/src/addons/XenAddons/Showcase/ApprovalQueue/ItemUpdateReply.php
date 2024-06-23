<?php

namespace XenAddons\Showcase\ApprovalQueue;

use XF\ApprovalQueue\AbstractHandler;
use XF\Mvc\Entity\Entity;

class ItemUpdateReply extends AbstractHandler
{
	protected function canActionContent(Entity $content, &$error = null)
	{
		/** @var $content \XenAddons\Showcase\Entity\ItemUpdateReply */
		return $content->canApproveUnapprove($error);
	}

	public function getEntityWith()
	{
		return ['ItemUpdate', 'User'];
	}

	public function actionApprove(\XenAddons\Showcase\Entity\ItemUpdateReply $reply)
	{
		$this->quickUpdate($reply, 'reply_state', 'visible');
	}

	public function actionDelete(\XenAddons\Showcase\Entity\ItemUpdateReply $reply)
	{
		$this->quickUpdate($reply, 'reply_state', 'deleted');
	}
}