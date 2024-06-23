<?php

namespace XenAddons\Showcase\Cli\Command\Rebuild;

use XF\Cli\Command\Rebuild\AbstractRebuildCommand;

class RebuildSeries extends AbstractRebuildCommand
{
	protected function getRebuildName()
	{
		return 'xa-sc-series';
	}

	protected function getRebuildDescription()
	{
		return 'Rebuilds showcase series counters.';
	}

	protected function getRebuildClass()
	{
		return 'XenAddons\Showcase:Series';
	}
}