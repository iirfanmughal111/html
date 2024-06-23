<?php

namespace XenAddons\Showcase\Cli\Command\Rebuild;

use XF\Cli\Command\Rebuild\AbstractRebuildCommand;

class RebuildItemUpdates extends AbstractRebuildCommand
{
	protected function getRebuildName()
	{
		return 'xa-sc-updates';
	}

	protected function getRebuildDescription()
	{
		return 'Rebuilds showcase item update counters.';
	}

	protected function getRebuildClass()
	{
		return 'XenAddons\Showcase:ItemUpdate';
	}
}