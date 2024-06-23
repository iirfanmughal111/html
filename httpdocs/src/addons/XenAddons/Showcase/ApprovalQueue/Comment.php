<?php

namespace XenAddons\Showcase\ApprovalQueue;

use XF\ApprovalQueue\AbstractHandler;
use XF\Mvc\Entity\Entity;

class Comment extends AbstractHandler
{
	protected function canActionContent(Entity $content, &$error = null)
	{
		/** @var $content \XenAddons\Showcase\Entity\Comment */
		return $content->canApproveUnapprove($error);
	}

	public function getEntityWith()
	{
		return ['Item', 'User'];
	}

	public function actionApprove(\XenAddons\Showcase\Entity\Comment $comment)
	{
		$this->quickUpdate($comment, 'comment_state', 'visible');
	}

	public function actionDelete(\XenAddons\Showcase\Entity\Comment $comment)
	{
		$this->quickUpdate($comment, 'comment_state', 'deleted');
	}
}