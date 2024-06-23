<?php

namespace XenAddons\Showcase\Bookmark;

use XF\Bookmark\AbstractHandler;
use XF\Mvc\Entity\Entity;

class ItemUpdate extends AbstractHandler
{
	/**
	 * @return string
	 */
	public function getCustomIconTemplateName()
	{
		return 'public:xa_sc_update_bookmark_custom_icon';
	}

	public function getEntityWith()
	{
		return ['Item', 'Item.Category', 'Item.CoverImage'];
	}
}