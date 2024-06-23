<?php

namespace XenAddons\Showcase\Alert;

use XF\Alert\AbstractHandler;

class Page extends AbstractHandler
{
	public function getOptOutActions()
	{
		return [
			'insert',
			'reaction'
		];
	}

	public function getOptOutDisplayOrder()
	{
		return 55571;
	}
}