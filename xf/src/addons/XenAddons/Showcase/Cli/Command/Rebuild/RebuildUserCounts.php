<?php

namespace XenAddons\Showcase\Cli\Command\Rebuild;

use XF\Cli\Command\Rebuild\AbstractRebuildCommand;

class RebuildUserCounts extends AbstractRebuildCommand
{
	protected function getRebuildName()
	{
		return 'xa-sc-user-counts';
	}

	protected function getRebuildDescription()
	{
		return 'Rebuilds showcase related user counters.';
	}

	protected function getRebuildClass()
	{
		return 'XenAddons\Showcase:UserItemCount';
	}
}