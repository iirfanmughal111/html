<?php

namespace XenAddons\Showcase\Alert;

use XF\Alert\AbstractHandler;

class ItemUpdateReply extends AbstractHandler
{
	public function getOptOutActions()
	{
		return [
			'your_item',
			'your_update',
			'other_replier',
			'reaction'
		];
	}

	public function getOptOutDisplayOrder()
	{
		return 55573; 
	}
}