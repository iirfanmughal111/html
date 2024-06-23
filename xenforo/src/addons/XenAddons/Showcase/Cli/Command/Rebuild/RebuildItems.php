<?php

namespace XenAddons\Showcase\Cli\Command\Rebuild;

use XF\Cli\Command\Rebuild\AbstractRebuildCommand;

class RebuildItems extends AbstractRebuildCommand
{
	protected function getRebuildName()
	{
		return 'xa-sc-items';
	}

	protected function getRebuildDescription()
	{
		return 'Rebuilds showcase item counters.';
	}

	protected function getRebuildClass()
	{
		return 'XenAddons\Showcase:Item';
	}
}