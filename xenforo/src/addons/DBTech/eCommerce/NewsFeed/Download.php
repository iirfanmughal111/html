<?php

namespace DBTech\eCommerce\NewsFeed;

use XF\NewsFeed\AbstractHandler;

/**
 * Class Download
 *
 * @package DBTech\eCommerce\NewsFeed
 */
class Download extends AbstractHandler
{
	/**
	 * @return array
	 */
	public function getEntityWith(): array
	{
		return [
			'User',
			'Product',
			'Product.permissionSet'
		];
	}
	
	/**
	 * @param $content
	 *
	 * @return mixed
	 */
	protected function addAttachmentsToContent($content)
	{
		return $this->addAttachments($content);
	}
}