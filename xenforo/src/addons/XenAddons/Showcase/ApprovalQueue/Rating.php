<?php

namespace XenAddons\Showcase\ApprovalQueue;

use XF\ApprovalQueue\AbstractHandler;
use XF\Mvc\Entity\Entity;

class Rating extends AbstractHandler
{
	protected function canActionContent(Entity $content, &$error = null)
	{
		/** @var $content \XenAddons\Showcase\Entity\ItemRating */
		return $content->canApproveUnapprove($error);
	}

	public function getEntityWith()
	{
		return ['Item', 'User'];
	}

	public function actionApprove(\XenAddons\Showcase\Entity\ItemRating $rating)
	{
		$this->quickUpdate($rating, 'rating_state', 'visible');
	}

	public function actionDelete(\XenAddons\Showcase\Entity\ItemRating $rating)
	{
		$this->quickUpdate($rating, 'rating_state', 'deleted');
	}
}