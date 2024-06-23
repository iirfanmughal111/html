<?php

namespace XenAddons\Showcase\Alert;

use XF\Alert\AbstractHandler;

class Rating extends AbstractHandler
{
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
		return 55574;
	}
}