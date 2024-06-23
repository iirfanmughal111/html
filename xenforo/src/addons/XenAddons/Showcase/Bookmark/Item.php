<?php

namespace XenAddons\Showcase\Bookmark;

use XF\Bookmark\AbstractHandler;
use XF\Mvc\Entity\Entity;

class Item extends AbstractHandler
{
	public function getContentTitle(Entity $content)
	{
		if ($content->Category->content_term)
		{
			return \XF::phrase('xa_sc_term_x_item_y', [
				'term' => $content->Category->content_term,
				'title' => $content->title
			]);
		}
		else 
		{	
			return \XF::phrase('xa_sc_item_x', [
				'title' => $content->title
			]);
		}		
	}

	/**
	 * @return string
	 */
	public function getContentRoute(Entity $content)
	{
		return 'showcase';
	}

	/**
	 * @return string
	 */
	public function getCustomIconTemplateName()
	{
		return 'public:xa_sc_item_bookmark_custom_icon';
	}

	public function getEntityWith()
	{
		$visitor = \XF::visitor();

		return ['CoverImage', 'Category', 'Category.Permissions|' . $visitor->permission_combination_id, 'User'];
	}
}