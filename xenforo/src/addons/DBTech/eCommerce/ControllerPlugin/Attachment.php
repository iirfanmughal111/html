<?php

namespace DBTech\eCommerce\ControllerPlugin;

use DBTech\eCommerce\Entity\DownloadVersion;
use DBTech\eCommerce\Entity\License;

/**
 * Class Attachment
 *
 * @package DBTech\eCommerce\ControllerPlugin
 */
class Attachment extends AbstractDownload
{
	/**
	 * @param DownloadVersion $version
	 * @param License|null $license
	 *
	 * @return mixed
	 */
	protected function _download(DownloadVersion $version, ?License $license = null)
	{
		/** @var \XF\Entity\Attachment|null $attachment */
		$attachment = null;
		
		$attachments = $version->getRelationFinder('Attachments')->fetch();
		
		$file = $this->filter('file', 'uint');
		if ($attachments->count() == 0)
		{
			return $this->error(\XF::phrase('attachment_cannot_be_shown_at_this_time'));
		}
		elseif ($attachments->count() == 1)
		{
			$attachment = $attachments->first();
		}
		elseif ($file && isset($attachments[$file]))
		{
			$attachment = $attachments[$file];
		}
		
		if (!$attachment)
		{
			return $this->error(\XF::phrase('attachment_cannot_be_shown_at_this_time'));
		}
		
		/** @var \XF\ControllerPlugin\Attachment $attachPlugin */
		$attachPlugin = $this->plugin('XF:Attachment');
		
		return $attachPlugin->displayAttachment($attachment);
	}
}