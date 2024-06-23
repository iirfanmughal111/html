<?php

namespace XenAddons\Showcase\Alert;

use XF\Alert\AbstractHandler;

class Item extends AbstractHandler
{
	public function getEntityWith()
	{
		$visitor = \XF::visitor();

		return ['Category', 'Category.Permissions|' . $visitor->permission_combination_id];
	}

	public function getOptOutActions()
	{
		return [
			'insert',
			'mention',
			'reaction'
		];
	}

	public function getOptOutDisplayOrder()
	{
		return 55570;
	}
}