<?php

namespace XenAddons\Showcase\Bookmark;

use XF\Bookmark\AbstractHandler;
use XF\Mvc\Entity\Entity;

class Series extends AbstractHandler
{
	public function getContentTitle(Entity $content)
	{
		return \XF::phrase('xa_sc_series_x', [
			'title' => $content->title
		]);
	}

	/**
	 * @return string
	 */
	public function getContentRoute(Entity $content)
	{
		return 'showcase/series';
	}

	/**
	 * @return string
	 */
	public function getCustomIconTemplateName()
	{
		return 'public:xa_sc_series_bookmark_custom_icon';
	}

	public function getEntityWith()
	{
		return ['User'];
	}
}