<?php

namespace XenAddons\Showcase\NewsFeed;

use XF\Mvc\Entity\Entity;
use XF\NewsFeed\AbstractHandler;

class Item extends AbstractHandler
{
	public function getEntityWith()
	{
		$visitor = \XF::visitor();

		return ['User', 'Category', 'Category.Permissions|' . $visitor->permission_combination_id];
	}
	
	protected function addAttachmentsToContent($content)
	{
		/** @var \XF\Repository\Attachment $attachmentRepo */
		$attachmentRepo = \XF::repository('XF:Attachment');
		$attachmentRepo->addAttachmentsToContent($content, 'sc_item');
	
		return $content;
	}
}