<?php

namespace XenAddons\Showcase\Cli\Command\Rebuild;

use XF\Cli\Command\Rebuild\AbstractRebuildCommand;

class RebuildReviews extends AbstractRebuildCommand
{
	protected function getRebuildName()
	{
		return 'xa-sc-reviews';
	}

	protected function getRebuildDescription()
	{
		return 'Rebuilds showcase review counters.';
	}

	protected function getRebuildClass()
	{
		return 'XenAddons\Showcase:Review';
	}
}