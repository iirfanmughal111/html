<?php

namespace XenAddons\Showcase\Alert;

use XF\Alert\AbstractHandler;

class ItemUpdate extends AbstractHandler
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
		return 55572; // TODO need to update this to the correct display order for Showcase!!!!
	}
}