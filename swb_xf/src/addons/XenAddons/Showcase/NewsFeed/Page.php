<?php

namespace XenAddons\Showcase\NewsFeed;

use XF\Mvc\Entity\Entity;
use XF\NewsFeed\AbstractHandler;

class Page extends AbstractHandler
{
	public function isPublishable(Entity $entity, $action)
	{
		/** @var \XenAddons\Showcase\Entity\ItemPage $entity */

		if ($action == 'insert')
		{
		
		}
		
		return true;
	}

	public function getEntityWith()
	{
		$visitor = \XF::visitor();

		return ['Item', 'Item.User', 'Item.Category', 'Item.Category.Permissions|' . $visitor->permission_combination_id];
	}
	
	protected function addAttachmentsToContent($content)
	{
		/** @var \XF\Repository\Attachment $attachmentRepo */
		$attachmentRepo = \XF::repository('XF:Attachment');
		$attachmentRepo->addAttachmentsToContent($content, 'sc_page');
	
		return $content;
	}
}