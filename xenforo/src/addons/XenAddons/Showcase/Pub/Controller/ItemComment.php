<?php

namespace XenAddons\Showcase\Pub\Controller;

use XF\Mvc\ParameterBag;

class ItemComment extends AbstractComment
{
	protected function assertViewableAndCommentableContent(ParameterBag $params)
	{
		$item = $this->assertViewableItem($params->item_id);
		if ($item->canViewComments() && ($item->canAddComment() || $item->canAddCommentPreReg()))
		{
			return $item;
		}
		else
		{
			throw $this->exception($this->noPermission());
		}
	}

	protected function assertViewableContent(ParameterBag $params)
	{
		$item = $this->assertViewableItem($params->item_id);
		if ($item->canViewComments())
		{
			return $item;
		}
		else
		{
			throw $this->exception($this->noPermission());
		}
	}
	
	protected function getLinkPrefix()
	{
		return 'showcase/item-comments';
	}
}