<?php

namespace XenAddons\Showcase\NewsFeed;

use XF\Mvc\Entity\Entity;
use XF\NewsFeed\AbstractHandler;

class Series extends AbstractHandler
{
	public function getEntityWith()
	{
		$visitor = \XF::visitor();

		return ['User'];
	}
	
	protected function addAttachmentsToContent($content)
	{
		/** @var \XF\Repository\Attachment $attachmentRepo */
		$attachmentRepo = \XF::repository('XF:Attachment');
		$attachmentRepo->addAttachmentsToContent($content, 'sc_series');
	
		return $content;
	}
}