<?php

namespace XenAddons\Showcase\Alert;

use XF\Alert\AbstractHandler;

class RatingReply extends AbstractHandler
{
	public function getOptOutActions()
	{
		return [
			'your_item',
			'your_review',
			'other_replier',
			'reaction'
		];
	}

	public function getOptOutDisplayOrder()
	{
		return 55575;
	}
}