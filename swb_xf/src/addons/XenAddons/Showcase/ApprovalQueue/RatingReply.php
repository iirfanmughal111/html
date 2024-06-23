<?php

namespace XenAddons\Showcase\ApprovalQueue;

use XF\ApprovalQueue\AbstractHandler;
use XF\Mvc\Entity\Entity;

class RatingReply extends AbstractHandler
{
	protected function canActionContent(Entity $content, &$error = null)
	{
		/** @var $content \XenAddons\Showcase\Entity\ItemRatingReply */
		return $content->canApproveUnapprove($error);
	}

	public function getEntityWith()
	{
		return ['ItemRating', 'User'];
	}

	public function actionApprove(\XenAddons\Showcase\Entity\ItemRatingReply $reply)
	{
		$this->quickUpdate($reply, 'reply_state', 'visible');
	}

	public function actionDelete(\XenAddons\Showcase\Entity\ItemRatingReply $reply)
	{
		$this->quickUpdate($reply, 'reply_state', 'deleted');
	}
}