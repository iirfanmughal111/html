<?php

namespace XenAddons\Showcase\Alert;

use XF\Alert\AbstractHandler;

class SeriesPart extends AbstractHandler
{
	public function getOptOutActions()
	{
		return [
			'insert'
		];
	}

	public function getOptOutDisplayOrder()
	{
		return 55577;
	}
}